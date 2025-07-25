<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use JaroslawZielinski\FeaturePoll\Api\Data\FeatureInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\FeatureInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Feature\Collection;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class Feature extends AbstractModel
{
    /**
     * @var JsonSerializer
     */
    protected $jsonSerializer;

    /**
     * @var FeatureInterfaceFactory
     */
    protected $featureDataFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var string
     */
    protected $_eventPrefix = 'feature_poll_features';

    /**
     * @inheritDoc
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        FeatureInterfaceFactory $featureDataFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context,
        Registry $registry,
        ResourceModel\Feature $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->featureDataFactory = $featureDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function getDataModel(): FeatureInterface
    {
        $featureData = $this->getData();
        $dynamicRows = $featureData['dynamic_rows_container'] ?? null;
        if (!empty($dynamicRows)) {
            usort($dynamicRows, function($a, $b) {
                return [$a['position']] <=> [$b['position']];
            });
            $questions = $this->jsonSerializer->serialize($dynamicRows);
            $featureData['questions'] = $questions;
            unset($featureData['dynamic_rows_container']);
        } else {
            if (!isset($featureData['questions'])) {
                $featureData['questions'] = '[]';
            }
        }
        $featureDataObject = $this->featureDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $featureDataObject,
            $featureData,
            Feature::class
        );

        return $featureDataObject;
    }
}
