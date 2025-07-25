<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface FeatureSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return FeatureInterface[]
     */
    public function getItems();

    /**
     * @param FeatureInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
