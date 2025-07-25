<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Data;

use JaroslawZielinski\FeaturePoll\Api\Data\FeatureInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class Feature extends AbstractExtensibleObject implements FeatureInterface
{
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
    public function setFeatureId($featureId): FeatureInterface
    {
        return $this->setData(self::FEATURE_ID, $featureId);
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
    public function setGroupName(string $groupName): FeatureInterface
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
    public function setDescription(?string $description): FeatureInterface
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getQuestions(): ?string
    {
        return $this->_get(self::QUESTIONS);
    }

    /**
     * @inheritDoc
     */
    public function setQuestions(?string $questions): FeatureInterface
    {
        return $this->setData(self::QUESTIONS, $questions);
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
    public function setCreatedAt(string $createdAt): FeatureInterface
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
    public function setUpdatedAt(string $updatedAt): FeatureInterface
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
        \JaroslawZielinski\FeaturePoll\Api\Data\FeatureExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
