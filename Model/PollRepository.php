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
use JaroslawZielinski\FeaturePoll\Api\Data\PollInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\PollInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Api\Data\PollSearchResultsInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\PollSearchResultsInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Api\PollRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Poll as ResourcePoll;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Poll\CollectionFactory as PollCollectionFactory;
use JaroslawZielinski\FeaturePoll\Model\PollFactory;

class PollRepository implements PollRepositoryInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ResourcePoll
     */
    private $resource;

    /**
     * @var PollInterfaceFactory
     */
    private $dataPollFactory;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    /**
     * @var PollSearchResultsInterfaceFactory
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
     * @var PollFactory
     */
    private $pollFactory;

    /**
     * @var PollCollectionFactory
     */
    private $pollCollectionFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    public function __construct(
        ResourcePoll $resource,
        PollFactory $pollFactory,
        PollInterfaceFactory $dataPollFactory,
        PollCollectionFactory $pollCollectionFactory,
        PollSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->pollFactory = $pollFactory;
        $this->dataPollFactory = $dataPollFactory;
        $this->pollCollectionFactory = $pollCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        PollInterface $poll
    ) {
        $pollData = $this->extensibleDataObjectConverter->toNestedArray(
            $poll,
            [],
            PollInterface::class
        );

        $pollModel = $this->pollFactory->create()->setData($pollData);

        try {
            $this->resource->save($pollModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the Poll: %1',
                $exception->getMessage()
            ));
        }
        return $pollModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($pollId)
    {
        $poll = $this->pollFactory->create();
        $this->resource->load($poll, $pollId);
        if (!$poll->getId()) {
            throw new NoSuchEntityException(__('Survey with id "%1" does not exist.', $pollId));
        }
        return $poll->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->pollCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            PollInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        /** @var PollSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        PollInterface $poll
    ) {
        try {
            $pollModel = $this->pollFactory->create();
            $this->resource->load($pollModel, $poll->getPollId());
            $this->resource->delete($pollModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Poll: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($pollId)
    {
        return $this->delete($this->get($pollId));
    }
}
