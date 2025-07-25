<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Data;

use JaroslawZielinski\FeaturePoll\Api\Data\VoterInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class Voter extends AbstractExtensibleObject implements VoterInterface
{
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
    public function setVoterId($voterId): VoterInterface
    {
        return $this->setData(self::VOTER_ID, $voterId);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return (string)$this->_get(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): VoterInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getSurname(): ?string
    {
        return $this->_get(self::SURNAME);
    }

    /**
     * @inheritDoc
     */
    public function setSurname(?string $surname): VoterInterface
    {
        return $this->setData(self::SURNAME, $surname);
    }

    /**
     * @inheritDoc
     */
    public function getToken(): string
    {
        return (string)$this->_get(self::TOKEN);
    }

    /**
     * @inheritDoc
     */
    public function setToken(string $token): VoterInterface
    {
        return $this->setData(self::TOKEN, $token);
    }

    /**
     * @inheritDoc
     */
    public function getOrigin(): bool
    {
        return !!$this->_get(self::ORIGIN);
    }

    /**
     * @inheritDoc
     */
    public function setOrigin(bool $origin): VoterInterface
    {
        return $this->setData(self::ORIGIN, $origin);
    }

    /**
     * @inheritDoc
     */
    public function getVoteCount(): int
    {
        return (int)$this->_get(self::VOTE_COUNT);
    }

    /**
     * @inheritDoc
     */
    public function setVoteCount(int $voteCount): VoterInterface
    {
        return $this->setData(self::VOTE_COUNT, $voteCount);
    }

    /**
     * @inheritDoc
     */
    public function getVoteBan(): ?string
    {
        return $this->_get(self::VOTE_BAN);
    }

    /**
     * @inheritDoc
     */
    public function setVoteBan(?string $voteBan): VoterInterface
    {
        return $this->setData(self::VOTE_BAN, $voteBan);
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
    public function setCreatedAt(string $createdAt): VoterInterface
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
    public function setUpdatedAt(string $updatedAt): VoterInterface
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
        \JaroslawZielinski\FeaturePoll\Api\Data\VoterExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
