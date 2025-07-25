<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PollSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return PollInterface[]
     */
    public function getItems();

    /**
     * @param PollInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
