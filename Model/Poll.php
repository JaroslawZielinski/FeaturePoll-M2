<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use JaroslawZielinski\FeaturePoll\Api\Data\PollInterface;
use JaroslawZielinski\FeaturePoll\Api\Data\PollInterfaceFactory;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Poll\Collection;

class Poll extends AbstractModel
{
    /**
     * @var JsonSerializer
     */
    protected $jsonSerializer;

    /**
     * @var PollInterfaceFactory
     */
    protected $pollDataFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var string
     */
    protected $_eventPrefix = 'feature_poll_polls';

    /**
     * @inheritDoc
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        PollInterfaceFactory $pollDataFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context,
        Registry $registry,
        ResourceModel\Poll $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->pollDataFactory = $pollDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function getDataModel(): PollInterface
    {
        $pollData = $this->getData();
        $dynamicRows = $pollData['dynamic_rows_container'] ?? null;
        if (!empty($dynamicRows)) {
            usort($dynamicRows, function($a, $b) {
                return [$a['position']] <=> [$b['position']];
            });
            $features = $this->jsonSerializer->serialize($dynamicRows);
            $pollData['features'] = $features;
            unset($pollData['dynamic_rows_container']);
        } else {
            if (!isset($pollData['features'])) {
                $pollData['features'] = '[]';
            }
        }
        $pollDataObject = $this->pollDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $pollDataObject,
            $pollData,
            Poll::class
        );

        return $pollDataObject;
    }
}
