<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface VoterInterface extends ExtensibleDataInterface
{
    public const VOTER_ID = 'voter_id';

    public const NAME = 'name';

    public const SURNAME = 'surname';

    public const TOKEN = 'token';

    public const ORIGIN = 'origin';

    public const VOTE_COUNT = 'vote_count';

    public const VOTE_BAN = 'vote_ban';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    public const GUEST_CUSTOMER_ORIGIN = false;

    public const CUSTOMER_ORIGIN = true;

    /**
     * @return ?int
     */
    public function getVoterId();

    /**
     * @param ?int $voterId
     * @return VoterInterface
     */
    public function setVoterId($voterId): self;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return VoterInterface
     */
    public function setName(string $name): self;

    /**
     * @return string
     */
    public function getSurname(): ?string;

    /**
     * @param string $surname
     * @return VoterInterface
     */
    public function setSurname(?string $surname): self;

    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @param string $token
     * @return VoterInterface
     */
    public function setToken(string $token): self;

    /**
     * @return bool
     */
    public function getOrigin(): bool;

    /**
     * @param bool $origin
     * @return VoterInterface
     */
    public function setOrigin(bool $origin): self;

    /**
     * @return int
     */
    public function getVoteCount(): int;

    /**
     * @param int $voteCount
     * @return VoterInterface
     */
    public function setVoteCount(int $voteCount): self;

    /**
     * @return string|null
     */
    public function getVoteBan(): ?string;

    /**
     * @param string|null $voteBan
     * @return VoterInterface
     */
    public function setVoteBan(?string $voteBan): self;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return VoterInterface
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return VoterInterface
     */
    public function setUpdatedAt(string $updatedAt): self;

    /**
     * @return \JaroslawZielinski\FeaturePoll\Api\Data\VoterExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \JaroslawZielinski\FeaturePoll\Api\Data\VoterExtensionInterface $extensionAttributes
     * @return VoterInterface
     */
    public function setExtensionAttributes(
        \JaroslawZielinski\FeaturePoll\Api\Data\VoterExtensionInterface $extensionAttributes
    );
}
