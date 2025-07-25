<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface VoteSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return VoteInterface[]
     */
    public function getItems();

    /**
     * @param VoterInterface[] $items
     * @return VoteSearchResultsInterface
     */
    public function setItems(array $items);
}
