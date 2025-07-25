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
use JaroslawZielinski\FeaturePoll\Api\Data\VoterInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoterInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Api\Data\VoterSearchResultsInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoterSearchResultsInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Api\VoterRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Voter as ResourceVoter;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Voter\CollectionFactory as VoterCollectionFactory;
use JaroslawZielinski\FeaturePoll\Model\VoterFactory;

class VoterRepository implements VoterRepositoryInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ResourceVoter
     */
    private $resource;

    /**
     * @var VoterInterfaceFactory
     */
    private $dataVoterFactory;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    /**
     * @var VoterSearchResultsInterfaceFactory
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
     * @var VoterFactory
     */
    private $voterFactory;

    /**
     * @var VoterCollectionFactory
     */
    private $voterCollectionFactory;

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
        ResourceVoter $resource,
        VoterFactory $voterFactory,
        VoterInterfaceFactory $dataVoterFactory,
        VoterCollectionFactory $voterCollectionFactory,
        VoterSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->voterFactory = $voterFactory;
        $this->dataVoterFactory = $dataVoterFactory;
        $this->voterCollectionFactory = $voterCollectionFactory;
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
    public function save(VoterInterface $voter)
    {
        $voterData = $this->extensibleDataObjectConverter->toNestedArray(
            $voter,
            [],
            VoterInterface::class
        );

        $voterModel = $this->voterFactory->create()->setData($voterData);

        try {
            $this->resource->save($voterModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the Voter: %1',
                $exception->getMessage()
            ));
        }
        return $voterModel->getDataModel();
    }

    /**
     * @inheritDoc
     */
    public function get($voterId)
    {
        $voter = $this->voterFactory->create();
        $this->resource->load($voter, $voterId);
        if (!$voter->getId()) {
            throw new NoSuchEntityException(__('Voter with id "%1" does not exist.', $voterId));
        }
        return $voter->getDataModel();
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->voterCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            VoterInterface::class
        );

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var VoterSearchResultsInterface $searchResults */
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
    public function delete(VoterInterface $voter)
    {
        try {
            $voterModel = $this->voterFactory->create();
            $this->resource->load($voterModel, $voter->getVoterId());
            $this->resource->delete($voterModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Voter: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($voterId)
    {
        return $this->delete($this->get($voterId));
    }
}
