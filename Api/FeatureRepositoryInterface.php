<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use JaroslawZielinski\FeaturePoll\Api\Data\FeatureInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\FeatureSearchResultsInterface;

interface FeatureRepositoryInterface
{
    /**
     * @param FeatureInterface $feature
     * @return FeatureInterface
     * @throws LocalizedException
     */
    public function save(
        FeatureInterface $feature
    );

    /**
     * @param string $featureId
     * @return FeatureInterface
     * @throws LocalizedException
     */
    public function get($featureId);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return FeatureSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $criteria
    );

    /**
     * @param FeatureInterface $feature
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        FeatureInterface $feature
    );

    /**
     * @param string $featureId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($featureId);
}
