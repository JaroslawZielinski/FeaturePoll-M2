<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface VoterSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return VoterInterface[]
     */
    public function getItems();

    /**
     * @param VoterInterface[] $items
     * @return VoterSearchResultsInterface
     */
    public function setItems(array $items);
}
