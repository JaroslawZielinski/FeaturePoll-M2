<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Poll;

use Magento\Framework\Exception\LocalizedException;
use JaroslawZielinski\FeaturePoll\Model\Data\Feature;
use JaroslawZielinski\FeaturePoll\Model\Data\Poll;
use JaroslawZielinski\FeaturePoll\Model\Poll\CreatePollDTO\PollDTO;
use JaroslawZielinski\FeaturePoll\Api\FeatureRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Api\PollRepositoryInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use JaroslawZielinski\FeaturePoll\Model\Poll\CreatePollDTO\PollDTO\FeaturesDTO;
use JaroslawZielinski\FeaturePoll\Model\Poll\CreatePollDTO\PollDTO\FeaturesDTO\QuestionsDTO;

class CreatePollDTO
{
    /**
     * @var FeatureRepositoryInterface
     */
    private $featureRepository;

    /**
     * @var PollRepositoryInterface
     */
    private $pollRepository;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     */
    public function __construct(
        FeatureRepositoryInterface $featureRepository,
        PollRepositoryInterface $pollRepository,
        JsonSerializer $jsonSerializer
    ) {
        $this->featureRepository = $featureRepository;
        $this->pollRepository = $pollRepository;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @throws LocalizedException
     */
    public function get(string $pollId): PollDTO
    {
        $poll = $this->pollRepository->get($pollId);
        $pollId = (int)$poll->getPollId();
        $groupName = $poll->getGroupName();
        $description = $poll->getDescription();
        $dateFrom = $poll->getDateFrom();
        $dateTo = $poll->getDateTo();
        return PollDTO::create([
            'pollId' => $pollId,
            'groupName' => $groupName,
            'description' => $description,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'features' => $this->getItemFeatures($poll)
        ]);
    }

    /**
     * @throws LocalizedException
     */
    private function getItemFeatures(Poll $poll): array
    {
        $itemForms = [];
        $features = $this->jsonSerializer->unserialize($poll->getFeatures());
        foreach ($features as $feature) {
            $featureId = (int)$feature['feature'];
            $featureModel = $this->featureRepository->get($featureId);
            $groupName = $featureModel->getGroupName();
            $description = $featureModel->getDescription();
            $itemForms[] = FeaturesDTO::create([
                'featureId' => $featureId,
                'groupName' => $groupName,
                'description' => $description,
                'questions' => $this->getItemQuestions($featureModel)
            ]);
        }
        return $itemForms;
    }


    /**
     * @return QuestionsDTO[]
    */
    private function getItemQuestions(Feature $featureModel): array
    {
        $itemQuestions = [];
        $questions = $this->jsonSerializer->unserialize($featureModel->getQuestions());
        foreach ($questions as $questionItem) {
            $position = $questionItem['position'];
            $question = $questionItem['question'];
            $description = $questionItem['description'];
            $type = $questionItem['type'];
            $itemQuestions[] = QuestionsDTO::create([
                'position' => $position,
                'question' => $question,
                'description' => $description,
                'type' => $type
            ]);
        }
        return $itemQuestions;
    }
}
