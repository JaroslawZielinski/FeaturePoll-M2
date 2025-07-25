<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\ViewModel;

use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\LocalizedException;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Vote\CollectionFactory as VoteCollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use JaroslawZielinski\FeaturePoll\Api\PollRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Helper\Data;
use JaroslawZielinski\FeaturePoll\Model\Config;
use JaroslawZielinski\FeaturePoll\Model\Poll\CreatePollDTO;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Customer\Model\Session as CustomerSession;

class Content implements ArgumentInterface
{
    /**
     * @var VoteCollectionFactory
     */
    private $voteCollectionFactory;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var CreatePollDTO
     */
    private $createPollDTO;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var PollRepositoryInterface
     */
    private $pollRepository;

    /**
     */
    public function __construct(
        VoteCollectionFactory $voteCollectionFactory,
        CustomerSession $customerSession,
        JsonSerializer $jsonSerializer,
        CreatePollDTO $createPollDTO,
        PollRepositoryInterface $pollRepository,
        Config $config
    ) {
        $this->voteCollectionFactory = $voteCollectionFactory;
        $this->customerSession = $customerSession;
        $this->jsonSerializer = $jsonSerializer;
        $this->createPollDTO = $createPollDTO;
        $this->pollRepository = $pollRepository;
        $this->config = $config;
    }

    /**
     */
    public function getHtmlId(): ?string
    {
        return $this->config->getPaneHtmlId();
    }

    /**
     * @throws LocalizedException
     */
    public function getFeaturePoll(string $pollId): ?array
    {
        $pollDTO = $this->createPollDTO->get($pollId);
        return $pollDTO->toArray();
    }

    /**
     * @throws LocalizedException
     */
    public function getFeaturePollArray(string $pollId): array
    {
        $featurePoll = $this->getFeaturePoll($pollId);
        return $this->getFeaturesArray($featurePoll);
    }

    /**
     * @throws LocalizedException
     * @throws \DateMalformedStringException
     */
    public function canShow(string $pollId): bool
    {
        $today = new \Datetime(date('Y-m-d'));
        $poll = $this->pollRepository->get($pollId);
        $strDateFrom = $poll->getDateFrom();
        $strDateTo = $poll->getDateTo();
        $dateFrom = !empty($strDateFrom) ? new \DateTime($strDateFrom) : null;
        $dateTo = !empty($strDateTo) ? new \DateTime($strDateTo) : null;
        switch (true) {
            case empty($dateFrom) && empty($dateTo):
                return true;
            case !empty($dateFrom) && empty($dateTo):
                return Data::getDateDiff($today, $dateFrom) >= 0;
            case empty($dateFrom) && !empty($dateTo):
                return Data::getDateDiff($today, $dateTo) < 0;
            case !empty($dateFrom) && !empty($dateTo):
                if (Data::getDateDiff($dateFrom, $dateTo) > 0) {
                    return false;
                }
                return Data::getDateDiff($today, $dateFrom) >= 0 && Data::getDateDiff($today, $dateTo) < 0;
            default:
                return false;
        }
    }

    public function getResults(int $pollId, int $featureId, int $position): array
    {
        $voteCollection = $this->voteCollectionFactory->create();
        $voteCollection
            ->addFieldToFilter('poll_id', ['eq' => $pollId])
            ->addFieldToFilter('feature_id', ['eq' => $featureId])
            ->addFieldToFilter('question_id', ['eq' => $position]);
        $value = $voteCollection->count();
        $voteCollectionTotal = $this->voteCollectionFactory->create();
        $voteCollectionTotal
            ->addFieldToFilter('poll_id', ['eq' => $pollId])
            ->addFieldToFilter('feature_id', ['eq' => $featureId]);
        $total = $voteCollectionTotal->count();
        return [$value, $total];
    }

    public function getFeaturesArray(array $featurePoll): array
    {
        $featuresArray = [];
        $pollId = $featurePoll['pollId'];
        foreach ($featurePoll['features'] as $feature) {
            $featureId = $feature['featureId'];
            $groupName = $feature['groupName'];
            foreach ($feature['questions'] as $questionItem) {
                $questionId = $questionItem['position'];
                $question = $questionItem['question'];
                $description = $questionItem['description'];
                $featuresArray[$pollId][$featureId][$questionId] = sprintf(
                    '%s: %s - %s',
                    $groupName,
                    $question,
                    $description
                );
            }
        }
        return $featuresArray;
    }

    public function getFeaturesJson(array $featurePoll): string
    {
        $featuresArray = $this->getFeaturesArray($featurePoll);
        return $this->jsonSerializer->serialize($featuresArray);
    }

    public function isCustomerLogged(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getCustomer(): Customer
    {
        return $this->customerSession->getCustomer();
    }

    public function getGTMEventsName1(): ?string
    {
        return $this->config->getGTMEventsName1();
    }

    public function getGTMEventsName2(): ?string
    {
        return $this->config->getGTMEventsName2();
    }

    public function getGTMEventsName3(): ?string
    {
        return $this->config->getGTMEventsName3();
    }

    public function getGTMEventsName4(): ?string
    {
        return $this->config->getGTMEventsName4();
    }

    public function getPaneHtmlId(): ?string
    {
        return $this->config->getPaneHtmlId();
    }
}
