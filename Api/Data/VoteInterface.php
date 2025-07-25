<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface VoteInterface extends ExtensibleDataInterface
{
    public const VOTE_ID = 'vote_id';

    public const VOTER_ID = 'voter_id';

    public const VOTER_IP = 'voter_ip';

    public const VOTER_DETAILS = 'voter_details';

    public const POLL_ID = 'poll_id';

    public const FEATURE_ID = 'feature_id';

    public const QUESTION_ID = 'question_id';

    public const DETAILS = 'details';

    public const IS_FRAUD = 'is_fraud';


    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    /**
     * @return ?int
     */
    public function getVoteId();

    /**
     * @param ?int $voteId
     * @return VoteInterface
     */
    public function setVoteId($voteId): self;

    /**
     * @return ?int
     */
    public function getVoterId();

    /**
     * @param ?int $voterId
     * @return VoteInterface
     */
    public function setVoterId($voterId): self;

    /**
     * @return string
     */
    public function getVoterIp(): string;

    /**
     * @param string $voterIp
     * @return VoteInterface
     */
    public function setVoterIp(string $voterIp): self;

    /**
     * @return ?string
     */
    public function getVoterDetails(): ?string;

    /**
     * @param ?string $voterDetails
     * @return VoteInterface
     */
    public function setVoterDetails(?string $voterDetails): self;

    /**
     * @return ?int
     */
    public function getPollId();

    /**
     * @param ?int $pollId
     * @return VoteInterface
     */
    public function setPollId($pollId): self;

    /**
     * @return ?int
     */
    public function getFeatureId();

    /**
     * @param ?int $featureId
     * @return VoteInterface
     */
    public function setFeatureId($featureId): self;

    /**
     * @return ?int
     */
    public function getQuestionId();

    /**
     * @param ?int $questionId
     * @return VoteInterface
     */
    public function setQuestionId($questionId): self;

    /**
     * @return ?string
     */
    public function getDetails();

    /**
     * @param ?string $details
     * @return VoteInterface
     */
    public function setDetails($details): self;

    /**
     * @return bool
     */
    public function getIsFraud(): bool;

    /**
     * @param bool $isFraud
     * @return VoteInterface
     */
    public function setIsFraud(bool $isFraud): self;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return VoteInterface
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return VoteInterface
     */
    public function setUpdatedAt(string $updatedAt): self;

    /**
     * @return \JaroslawZielinski\FeaturePoll\Api\Data\VoteExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \JaroslawZielinski\FeaturePoll\Api\Data\VoteExtensionInterface $extensionAttributes
     * @return VoterInterface
     */
    public function setExtensionAttributes(
        \JaroslawZielinski\FeaturePoll\Api\Data\VoteExtensionInterface $extensionAttributes
    );
}
