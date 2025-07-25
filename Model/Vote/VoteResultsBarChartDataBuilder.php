<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Vote;

use BroCode\Chartee\Api\Data\ChartDataConfigurationInterfaceFactory;
use BroCode\Chartee\Model\DataBuilder\BarChartDataBuilder;
use BroCode\Chartee\Model\DataBuilder\DataSetBuilderFactory;
use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterface;
use JaroslawZielinski\FeaturePoll\Api\FeatureRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Api\VoterRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Helper\Data;
use JaroslawZielinski\FeaturePoll\Model\Data\Vote as VoteDataModel;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Vote\CollectionFactory as VoteCollectionFactory;
use JaroslawZielinski\FeaturePoll\Model\Vote as VoteModel;
use Magento\Backend\Model\Session as BackendSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class VoteResultsBarChartDataBuilder extends BarChartDataBuilder
{
    public const BARCHART_VOTES_COLECTION_TAG = 'barchart_votes_collection_tag';

    public const BARCHART_VOTES_COLECTION_LEGEND_TAG = 'barchart_votes_collection_legened_tag';

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var VoterRepositoryInterface
     */
    private $voterRepository;

    /**
     * @var FeatureRepositoryInterface
     */
    private $featureRepository;

    /**
     * @var VoteCollectionFactory
     */
    private $voteCollectionFactory;

    /**
     * @var BackendSession
     */
    private $backendSession;

    /**
     * @inheritDoc
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        VoterRepositoryInterface $voterRepository,
        FeatureRepositoryInterface $featureRepository,
        VoteCollectionFactory $voteCollectionFactory,
        BackendSession $backendSession,
        ChartDataConfigurationInterfaceFactory $chartDataConfigurationFactory,
        DataSetBuilderFactory $dataSetBuilderFactory
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->voterRepository = $voterRepository;
        $this->featureRepository = $featureRepository;
        $this->voteCollectionFactory = $voteCollectionFactory;
        $this->backendSession = $backendSession;
        parent::__construct($chartDataConfigurationFactory, $dataSetBuilderFactory);
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    public function build()
    {
        $values = [];
        $labels = [];
        $features = [];
        $details = [];
        $emails = [];
        $chartLabels = [];
        $surveyResults = [];
        /** @var array $collectionIds */
        $collectionIds = $this->backendSession->getData(self::BARCHART_VOTES_COLECTION_TAG) ?? null;
        $this->backendSession->getData(self::BARCHART_VOTES_COLECTION_LEGEND_TAG, $clear = true);
        if (!empty($collectionIds)) {
            $collection = $this->voteCollectionFactory->create();
            $collection->addFieldToFilter(VoteInterface::VOTE_ID, ['in' => $collectionIds]);
            /** @var VoteModel $item */
            foreach ($collection->getItems() as $item) {
                $featurePollId = $item->getFeaturepollId();
                $featurePollValue = $item->getFeaturepollValue();
                /** @var VoteDataModel $vote */
                $vote = $item->getDataModel();
                $feature = $this->featureRepository->get($vote->getFeatureId());
                $voter = $this->voterRepository->get($vote->getVoterId());
                $email = Data::getEmailByToken($voter->getToken());
                $question = $this->jsonSerializer->unserialize($feature->getQuestions());
                $questionI = $question[(int)$vote->getQuestionId() - 1];
                $questionName = $questionI['question'];
                $previousValue = $values[$featurePollId] ?? 0;
                $values[$featurePollId] = ++$previousValue;
                $labels[$featurePollId] = $questionName;
                $features[$featurePollId] = $feature->getGroupName();
                if (!empty($featurePollValue)) {
                    $details[$featurePollId][] = $featurePollValue;
                    $emails[$featurePollId][] = $email;
                }
            }
            if (!empty($values)) {
                $this->sortValues($values);
                foreach ($values as $featurePollId => $value) {
                    $surveyResults[$featurePollId] = [
                        'questionName' => $labels[$featurePollId],
                        'value' => $value,
                        'featureName' => $features[$featurePollId],
                        'details' => $details[$featurePollId] ?? [],
                        'emails' => $emails[$featurePollId] ?? []
                    ];
                }
                $this->backendSession->setData(self::BARCHART_VOTES_COLECTION_LEGEND_TAG, $surveyResults);
                $chartLabels = array_keys($surveyResults);
            }
        }
        $this->setDataLabels($chartLabels)
            ->createDataSet()
            ->setLabel('FeaturePoll Results')
            ->setDataValues(...$values)
            ->setBackgroundColor([
                'rgba(255, 159, 64, 0.2)',
            ])->setBorderColor([
                'rgba(255, 159, 64, 1)',
            ])->setBorderWidth(1)
            ->build();

        return parent::build();
    }

    private function sortValues(array &$array): void
    {
        uksort($array, function($a, $b) use ($array) {
            $partsA = explode('-', $a);
            $partsB = explode('-', $b);
            // X (poll) ASC
            $xComparison = $partsA[1] <=> $partsB[1];
            if ($xComparison !== 0) {
                return $xComparison;
            }
            // Y (form) ASC
            $yComparison = $partsA[2] <=> $partsB[2];
            if ($yComparison !== 0) {
                return $yComparison;
            }
            // Z: sort by values DESC
            return $array[$b] <=> $array[$a];
        });
    }
}
