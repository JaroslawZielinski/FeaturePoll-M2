<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Data;

use JaroslawZielinski\FeaturePoll\Api\Data\PollInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class Poll extends AbstractExtensibleObject implements PollInterface
{
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
    public function setPollId($pollId): PollInterface
    {
        return $this->setData(self::POLL_ID, $pollId);
    }

    /**
     * @inheritDoc
     */
    public function getGroupName(): string
    {
        return $this->_get(self::GROUP_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setGroupName(string $groupName): PollInterface
    {
        return $this->setData(self::GROUP_NAME, $groupName);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): ?string
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription(?string $description): PollInterface
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getFeatures(): ?string
    {
        return $this->_get(self::FEATURES);
    }

    /**
     * @inheritDoc
     */
    public function setFeatures(?string $features): PollInterface
    {
        return $this->setData(self::FEATURES, $features);
    }

    /**
     * @inheritDoc
     */
    public function getDateFrom(): ?string
    {
        return $this->_get(self::DATE_FROM);
    }

    /**
     * @inheritDoc
     */
    public function setDateFrom(?string $dateFrom): PollInterface
    {
        return $this->setData(self::DATE_FROM, $dateFrom);
    }

    /**
     * @inheritDoc
     */
    public function getDateTo(): ?string
    {
        return $this->_get(self::DATE_TO);
    }

    /**
     * @inheritDoc
     */
    public function setDateTo(?string $dateTo): PollInterface
    {
        return $this->setData(self::DATE_TO, $dateTo);
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
    public function setCreatedAt(string $createdAt): PollInterface
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
    public function setUpdatedAt(string $updatedAt): PollInterface
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
        \JaroslawZielinski\FeaturePoll\Api\Data\PollExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
