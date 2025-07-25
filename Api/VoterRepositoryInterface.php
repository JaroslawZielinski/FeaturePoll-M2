<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Api;


use JaroslawZielinski\FeaturePoll\Api\Data\VoterInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoterSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface VoterRepositoryInterface
{
    /**
     * @param VoterInterface $voter
     * @return VoterInterface
     * @throws LocalizedException
     */
    public function save(
        VoterInterface $voter
    );

    /**
     * @param string $voterId
     * @return VoterInterface
     * @throws LocalizedException
     */
    public function get($voterId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return VoterSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * @param VoterInterface $voter
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        VoterInterface $voter
    );

    /**
     * @param string $voterId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($voterId);
}
