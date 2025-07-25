<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Data;

use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class Vote extends AbstractExtensibleObject implements VoteInterface
{
    /**
     * @inheritDoc
     */
    public function getVoteId()
    {
        return $this->_get(self::VOTE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setVoteId($voteId): VoteInterface
    {
        return $this->setData(self::VOTE_ID, $voteId);
    }

    /**
     * @inheritDoc
     */
    public function getVoterId()
    {
        return $this->_get(self::VOTER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setVoterId($voterId): VoteInterface
    {
        return $this->setData(self::VOTER_ID, $voterId);
    }

    /**
     * @inheritDoc
     */
    public function getVoterIp(): string
    {
        return (string)$this->_get(self::VOTER_IP);
    }

    /**
     * @inheritDoc
     */
    public function setVoterIp(?string $voterIp): VoteInterface
    {
        return $this->setData(self::VOTER_IP, $voterIp);
    }

    /**
     * @inheritDoc
     */
    public function getVoterDetails(): ?string
    {
        return $this->_get(self::VOTER_DETAILS);
    }

    /**
     * @inheritDoc
     */
    public function setVoterDetails(?string $voterDetails): VoteInterface
    {
        return $this->setData(self::VOTER_DETAILS, $voterDetails);
    }

    /**
     * @inheritDoc
     */
    public function getPollId()
    {
        return $this->_get(self::POLL_ID);
    }

    /**
     * @inheritDoc
     */
    public function setPollId($pollId): VoteInterface
    {
        return $this->setData(self::POLL_ID, $pollId);
    }

    /**
     * @inheritDoc
     */
    public function getFeatureId()
    {
        return $this->_get(self::FEATURE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setFeatureId($featureId): VoteInterface
    {
        return $this->setData(self::FEATURE_ID, $featureId);
    }

    /**
     * @inheritDoc
     */
    public function getQuestionId()
    {
        return $this->_get(self::QUESTION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setQuestionId($questionId): VoteInterface
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    /**
     * @inheritDoc
     */
    public function getDetails()
    {
        return $this->_get(self::DETAILS);
    }

    /**
     * @inheritDoc
     */
    public function setDetails($details): VoteInterface
    {
        return $this->setData(self::DETAILS, $details);
    }

    /**
     * @inheritDoc
     */
    public function getIsFraud(): bool
    {
        return !!$this->_get(self::IS_FRAUD);
    }

    /**
     * @inheritDoc
     */
    public function setIsFraud(bool $isFraud): VoteInterface
    {
        return $this->setData(self::IS_FRAUD, $isFraud);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return (string)$this->_get(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $createdAt): VoteInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): string
    {
        return (string)$this->_get(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $updatedAt): VoteInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @inheritDoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritDoc
     */
    public function setExtensionAttributes(
        \JaroslawZielinski\FeaturePoll\Api\Data\VoteExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
