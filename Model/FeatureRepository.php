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
use JaroslawZielinski\FeaturePoll\Api\Data\FeatureInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\FeatureInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Api\Data\FeatureSearchResultsInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\FeatureSearchResultsInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Api\FeatureRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Feature as ResourceFeature;
use JaroslawZielinski\FeaturePoll\Model\FeatureFactory;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Feature\CollectionFactory as FeatureCollectionFactory;

class FeatureRepository implements FeatureRepositoryInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ResourceFeature
     */
    private $resource;

    /**
     * @var FeatureInterfaceFactory
     */
    private $dataFeatureFactory;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    /**
     * @var FeatureSearchResultsInterfaceFactory
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
     * @var FeatureFactory
     */
    private $featureFactory;

    /**
     * @var FeatureCollectionFactory
     */
    private $featureCollectionFactory;

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
        ResourceFeature $resource,
        FeatureFactory $featureFactory,
        FeatureInterfaceFactory $dataFeatureFactory,
        FeatureCollectionFactory $featureCollectionFactory,
        FeatureSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->featureFactory = $featureFactory;
        $this->dataFeatureFactory = $dataFeatureFactory;
        $this->featureCollectionFactory = $featureCollectionFactory;
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
        FeatureInterface $feature
    ) {
        $featureData = $this->extensibleDataObjectConverter->toNestedArray(
            $feature,
            [],
            FeatureInterface::class
        );

        $featureModel = $this->featureFactory->create()->setData($featureData);

        try {
            $this->resource->save($featureModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the Feature: %1',
                $exception->getMessage()
            ));
        }
        return $featureModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($featureId)
    {
        $feature = $this->featureFactory->create();
        $this->resource->load($feature, $featureId);
        if (!$feature->getId()) {
            throw new NoSuchEntityException(__('Feature with id "%1" does not exist.', $featureId));
        }
        return $feature->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->featureCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            FeatureInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        /** @var FeatureSearchResultsInterface $searchResults */
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
        FeatureInterface $feature
    ) {
        try {
            $featureModel = $this->featureFactory->create();
            $this->resource->load($featureModel, $feature->getFeatureId());
            $this->resource->delete($featureModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Feature: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($featureId)
    {
        return $this->delete($this->get($featureId));
    }
}
