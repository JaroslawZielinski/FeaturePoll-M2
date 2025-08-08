<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model;

use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoterInterface;
use JaroslawZielinski\FeaturePoll\Api\VoteRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Api\VoterRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Block\Poll\Panel\Content\Feature as WidgetForm;
use JaroslawZielinski\FeaturePoll\Helper\Data;
use JaroslawZielinski\FeaturePoll\Model\Data\Vote as VoteDataModel;
use JaroslawZielinski\FeaturePoll\Model\Data\Voter as VoterDataModel;
use JaroslawZielinski\FeaturePoll\ViewModel\Content as ContentView;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\EmailAddress as EmailAddressValidator;


class VoteManagement
{
    public const CUSTOMER_NON_GUEST = 'customer';

    /**
     * @var ContentView
     */
    private $contentView;

    /**
     * @var VoteRepositoryInterface
     */
    private $voteRepository;

    /**
     * @var VoterRepositoryInterface
     */
    private $voterRepository;

    /**
     * @var EmailAddressValidator
     */
    private $emailAddressValidator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var VoteFactory
     */
    private $voteFactory;

    /**
     * @var VoterFactory
     */
    private $voterFactory;

    /**
     */
    public function __construct(
        ContentView $contentView,
        VoteRepositoryInterface $voteRepository,
        VoterRepositoryInterface $voterRepository,
        EmailAddressValidator $emailAddressValidator,
        Config $config,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        VoteFactory $voteFactory,
        VoterFactory $voterFactory
    ) {
        $this->contentView = $contentView;
        $this->voteRepository = $voteRepository;
        $this->voterRepository = $voterRepository;
        $this->emailAddressValidator = $emailAddressValidator;
        $this->config = $config;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->voteFactory = $voteFactory;
        $this->voterFactory = $voterFactory;
    }

    /**
     * @throws LocalizedException
     */
    public function vote(
        string $voterName,
        string $voterEmail,
        string $featurePollId,
        ?string $featurePollValue,
        ?string $voterIp = null,
        ?string $voterDetails = null,
        ?string $voterSurname = null,
        bool $suppressTimeBan = false
    ): array {
        $now = new \DateTime();
        $nonVotingMinutes = (int)$this->config->getSettingsNonVotingPeriod();
        $voteBan = clone $now;
        if ($nonVotingMinutes > 0) {
            /** @see https://stackoverflow.com/questions/11386308/add-x-number-of-hours-to-date#answer-23103855 */
            $voteBan = (clone $now)->add(new \DateInterval("PT{$nonVotingMinutes}M"));
        }
        if ($this->validate(
                $now,
                $voterName,
                $voterEmail,
                $featurePollId,
                $featurePollValue,
                $voterSurname,
                $suppressTimeBan
            )) {
            $voter = $this->getVoter($voterEmail);
            if (empty($voter)) {
                $origin = VoterInterface::GUEST_CUSTOMER_ORIGIN;
                if (self::CUSTOMER_NON_GUEST === $voterIp && (int)$voterDetails > 0) {
                    $origin = VoterInterface::CUSTOMER_ORIGIN;
                }
                $voter = $this->createVoter($voterName, $voterSurname, $voterEmail, $origin);
            }
            $voteCount = $voter->getVoteCount();
            $vote = $this->voteFactory->create();
            list ($pollId, $featureId, $questionId) = Data::explodeFeaturePollId($featurePollId);
            /** @var VoteDataModel $voteDataModel */
            $voteDataModel = $vote->getDataModel();
            $voteDataModel
                ->setVoterId((int)$voter->getVoterId())
                ->setVoterIp($voterIp)
                ->setVoterDetails($voterDetails)
                ->setPollId($pollId)
                ->setFeatureId($featureId)
                ->setDetails($featurePollValue)
                ->setQuestionId($questionId);
            $this->voteRepository->save($voteDataModel);
            $voter
                ->setVoteCount(++$voteCount)
                ->setVoteBan($voteBan->format(WidgetForm::STANDARD_DATETIME_FORMAT));
            $this->voterRepository->save($voter);
            $featurePollArray = $this->contentView->getFeaturePollArray((string)$pollId);
            $questionIds = array_keys($featurePollArray[$pollId][$featureId]);
            $statistics = [];
            foreach ($questionIds as $questionId) {
                $statisticsId = Data::implodeFeaturePollId($pollId, $featureId, $questionId);
                $statistics[$statisticsId] = $this->contentView->getResults($pollId, $featureId, $questionId);
            }
            return $statistics;
        }
        return [];
    }

    /**
     * @throws LocalizedException
     */
    private function getVoter(string $voterEmail): ?VoterDataModel
    {
        $token = Data::getTokenByEmail($voterEmail);
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(VoterInterface::TOKEN, $token)
            ->create();
        $voterList = $this->voterRepository->getList($searchCriteria);
        $voterItems = $voterList->getItems();
        if (0 === count($voterItems)) {
            return null;
        }
        /** @var VoterDataModel $voterItem */
        $voterItem = reset($voterItems);
        return $voterItem;
    }

    /**
     * @throws LocalizedException
     */
    private function createVoter(
        string $voterName,
        ?string $voterSurname,
        string $voterEmail,
        bool $origin = VoterInterface::GUEST_CUSTOMER_ORIGIN
    ): VoterDataModel
    {
        $voter = $this->voterFactory->create();
        $voterDataModel = $voter->getDataModel();
        $voterDataModel
            ->setName($voterName)
            ->setVoteCount(0)
            ->setSurname($voterSurname)
            ->setToken(Data::getTokenByEmail($voterEmail))
            ->setOrigin($origin);
        return $this->voterRepository->save($voterDataModel);
    }

    /**
     * @throws LocalizedException
     */
    private function validate(
        \DateTime $now,
        string $voterName,
        string $voterEmail,
        string $featurePollId,
        ?string $featurePollValue,
        ?string $voterSurname,
        $suppressTimeBan
    ): bool {
        // 1. validation if email correct
        if (!$this->emailAddressValidator->isValid($voterEmail)) {
            throw new LocalizedException(__('Email of a voter is invalid.'));
        }
        // 2. validation if questionID exists
        list ($pollId, $featureId, $questionId) = Data::explodeFeaturePollId($featurePollId);
        $featurePollArray = $this->contentView->getFeaturePollArray((string)$pollId);
        if (!isset($featurePollArray[$pollId][$featureId][$questionId])) {
            throw new LocalizedException(__('FeaturePoll does not exist (with id: %1).', $featurePollId));
        }
        // 3. validation if voter exists
        $voter = $this->getVoter($voterEmail);
        $isVoterExisting = !empty($voter);
        if ($isVoterExisting) {
            $voterId = $voter->getVoterId();
            // 4. Check vote_count/vote_ban
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(VoteInterface::VOTER_ID, $voterId)
                ->create();
            $voteList = $this->voteRepository->getList($searchCriteria);
            $voteItems = $voteList->getItems();
            // Fixing issue that user can not vote in other polls...
            $voteItems = array_filter($voteItems, function ($item) use ($pollId) {
                $votePollId = (int)$item->getPollId();
                return $pollId === $votePollId;
            });
            $realVoteCount = count($voteItems);
            $maxVote = count($featurePollArray[$pollId]);
            if ($realVoteCount >= $maxVote) {
                throw new LocalizedException(
                    __('Vote limit is exhausted. You cannot vote anymore during the poll within this feature.')
                );
            }
            if (!$suppressTimeBan) {
                $nonVotingMinutes = (int)$this->config->getSettingsNonVotingPeriod();
                if ($nonVotingMinutes > 0) {
                    $voteBan = $voter->getVoteBan();
                    $voteBanDateTime = new \DateTime($voteBan);
                    if ($now <= $voteBanDateTime) {
                        throw new LocalizedException(
                            __('You cannot vote more times during %1 than allowed. Please do it later.',
                                (string)Data::formatMinutes($nonVotingMinutes))
                        );
                    }
                }
            }
            // 5. Check if feature is Ok
            $featureIdsNotAllowed = [];
            /** @var \JaroslawZielinski\FeaturePoll\Model\Data\Vote $voteItem */
            foreach($voteItems as $voteItem) {
                $featureIdsNotAllowed[] = (int)$voteItem->getFeatureId();
            }
            if (in_array($featureId, $featureIdsNotAllowed)) {
                throw new LocalizedException(
                    __('You have already voted for the feature in this form.')
                );
            }
        } else {
            return true;
        }
        return true;
    }
}
