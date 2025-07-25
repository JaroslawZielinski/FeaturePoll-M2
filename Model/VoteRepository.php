<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model;


use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Api\Data\VoteSearchResultsInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoteSearchResultsInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Api\VoteRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Vote as ResourceVote;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Vote\CollectionFactory as VoteCollectionFactory;
use JaroslawZielinski\FeaturePoll\Model\VoteFactory;


class VoteRepository implements VoteRepositoryInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ResourceVote
     */
    private $resource;

    /**
     * @var VoteInterfaceFactory
     */
    private $dataVoteFactory;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    /**
     * @var VoteSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var VoteFactory
     */
    private $voteFactory;

    /**
     * @var VoteCollectionFactory
     */
    private $voteCollectionFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     */
    public function __construct(
        ResourceVote $resource,
        VoteFactory $voteFactory,
        VoteInterfaceFactory $dataVoteFactory,
        VoteCollectionFactory $voteCollectionFactory,
        VoteSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->voteFactory = $voteFactory;
        $this->dataVoteFactory = $dataVoteFactory;
        $this->voteCollectionFactory = $voteCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * @inheritDoc
     */
    public function save(VoteInterface $vote)
    {
        $voteData = $this->extensibleDataObjectConverter->toNestedArray(
            $vote,
            [],
            VoteInterface::class
        );

        $voteModel = $this->voteFactory->create()->setData($voteData);

        try {
            $this->resource->save($voteModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the Vote: %1',
                $exception->getMessage()
            ));
        }
        return $voteModel->getDataModel();
    }

    /**
     * @inheritDoc
     */
    public function get($voteId)
    {
        $vote = $this->voteFactory->create();
        $this->resource->load($vote, $voteId);
        if (!$vote->getId()) {
            throw new NoSuchEntityException(__('Vote with id "%1" does not exist.', $voteId));
        }
        return $vote->getDataModel();
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->voteCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            VoteInterface::class
        );

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var VoteSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(VoteInterface $vote)
    {
        try {
            $voteModel = $this->voteFactory->create();
            $this->resource->load($voteModel, $vote->getVoteId());
            $this->resource->delete($voteModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Vote: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($voteId)
    {
        return $this->delete($this->get($voteId));
    }
}
