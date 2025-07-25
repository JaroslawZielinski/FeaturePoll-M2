<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface FeatureInterface extends ExtensibleDataInterface
{
    public const FEATURE_ID = 'feature_id';

    public const GROUP_NAME = 'group_name';

    public const DESCRIPTION = 'description';

    public const QUESTIONS = 'questions';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    /**
     * @return ?int
     */
    public function getFeatureId();

    /**
     * @param ?int $featureId
     * @return FeatureInterface
     */
    public function setFeatureId($featureId): self;

    /**
     * @return string
     */
    public function getGroupName(): string;

    /**
     * @param string $groupName
     * @return FeatureInterface
     */
    public function setGroupName(string $groupName): self;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @param string|null $description
     * @return FeatureInterface
     */
    public function setDescription(?string $description): self;

    /**
     * @return string|null
     */
    public function getQuestions(): ?string;

    /**
     * @param string|null $questions
     * @return FeatureInterface
     */
    public function setQuestions(?string $questions): self;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return FeatureInterface
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string $updatedAt
     * @return FeatureInterface
     */
    public function setUpdatedAt(string $updatedAt): self;

    /**
     * @return \JaroslawZielinski\FeaturePoll\Api\Data\FeatureExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \JaroslawZielinski\FeaturePoll\Api\Data\FeatureExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \JaroslawZielinski\FeaturePoll\Api\Data\FeatureExtensionInterface $extensionAttributes
    );
}
