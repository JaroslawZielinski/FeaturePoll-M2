<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use JaroslawZielinski\FeaturePoll\Api\Data\PollInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\PollSearchResultsInterface;

interface PollRepositoryInterface
{
    /**
     * @param PollInterface $poll
     * @return PollInterface
     * @throws LocalizedException
     */
    public function save(
        PollInterface $poll
    );

    /**
     * @param string $pollId
     * @return PollInterface
     * @throws LocalizedException
     */
    public function get($pollId);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return PollSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $criteria
    );

    /**
     * @param PollInterface $poll
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        PollInterface $poll
    );

    /**
     * @param string $pollId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($pollId);
}
