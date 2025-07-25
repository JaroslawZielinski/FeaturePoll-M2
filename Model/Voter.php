<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use JaroslawZielinski\FeaturePoll\Api\Data\VoterInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\VoterInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Voter\Collection;

class Voter extends AbstractModel
{
    /**
     * @var VoterInterfaceFactory
     */
    protected $voterDataFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var string
     */
    protected $_eventPrefix = 'feature_poll_voters';

    /**
     * @inheritDoc
     */
    public function __construct(
        VoterInterfaceFactory $voterDataFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context,
        Registry $registry,
        ResourceModel\Voter $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->voterDataFactory = $voterDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function getDataModel(): VoterInterface
    {
        $voterData = $this->getData();

        $voterDataObject = $this->voterDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $voterDataObject,
            $voterData,
            Voter::class
        );

        return $voterDataObject;
    }
}
