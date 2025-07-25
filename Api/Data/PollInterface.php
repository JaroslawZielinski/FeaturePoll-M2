<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PollInterface extends ExtensibleDataInterface
{
    public const POLL_ID = 'poll_id';

    public const GROUP_NAME = 'group_name';

    public const DESCRIPTION = 'description';

    public const FEATURES = 'features';

    public const DATE_FROM = 'date_from';

    public const DATE_TO = 'date_to';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    /**
     * @return ?int
     */
    public function getPollId();

    /**
     * @param ?int $pollId
     * @return PollInterface
     */
    public function setPollId($pollId): self;

    /**
     * @return string
     */
    public function getGroupName(): string;

    /**
     * @param string $groupName
     * @return PollInterface
     */
    public function setGroupName(string $groupName): self;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @param string|null $description
     * @return PollInterface
     */
    public function setDescription(?string $description): self;

    /**
     * @return string|null
     */
    public function getFeatures(): ?string;

    /**
     * @param string|null $features
     * @return PollInterface
     */
    public function setFeatures(?string $features): self;

    /**
     * @return string|null
     */
    public function getDateFrom(): ?string;

    /**
     * @param string|null $dateFrom
     * @return PollInterface
     */
    public function setDateFrom(?string $dateFrom): self;

    /**
     * @return string|null
     */
    public function getDateTo(): ?string;

    /**
     * @param string|null $dateTo
     * @return PollInterface
     */
    public function setDateTo(?string $dateTo): self;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return PollInterface
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return PollInterface
     */
    public function setUpdatedAt(string $updatedAt): self;

    /**
     * @return \JaroslawZielinski\FeaturePoll\Api\Data\PollExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \JaroslawZielinski\FeaturePoll\Api\Data\PollExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \JaroslawZielinski\FeaturePoll\Api\Data\PollExtensionInterface $extensionAttributes
    );
}
