<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api;


use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoteSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface VoteRepositoryInterface
{
    /**
     * @param VoteInterface $vote
     * @return VoteInterface
     * @throws LocalizedException
     */
    public function save(
        VoteInterface $vote
    );

    /**
     * @param string $voteId
     * @return VoteInterface
     * @throws LocalizedException
     */
    public function get($voteId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return VoteSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * @param VoteInterface $vote
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        VoteInterface $vote
    );

    /**
     * @param string $voteId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($voteId);
}
