<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Vote\Collection;

class Vote extends AbstractModel
{
    /**
     * @var VoteInterfaceFactory
     */
    protected $voteDataFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var string
     */
    protected $_eventPrefix = 'feture_poll_votes';

    /**
     * @inheritDoc
     */
    public function __construct(
        VoteInterfaceFactory $voteDataFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context,
        Registry $registry,
        ResourceModel\Vote $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->voteDataFactory = $voteDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function getDataModel(): VoteInterface
    {
        $voteData = $this->getData();

        $voteDataObject = $this->voteDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $voteDataObject,
            $voteData,
            Vote::class
        );

        return $voteDataObject;
    }
}
