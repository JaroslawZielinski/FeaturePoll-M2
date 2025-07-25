<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Config\Source\Features;

use JaroslawZielinski\FeaturePoll\Model\Feature;
use JaroslawZielinski\FeaturePoll\Model\Data\Feature as DataFeature;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Feature\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

class Features implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var array
     */
    protected $options;

    /**
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray(): ?array
    {
        if (empty($this->options)) {
            $options = [];
            $optionsCollection = $this->collectionFactory->create()->load();
            /** @var Feature $feature */
            foreach ($optionsCollection as $feature) {
                /** @var DataFeature $featureData */
                $featureData = $feature->getDataModel();
                $options[] = [
                    'value' => $featureData->getFeatureId(),
                    'label' => __($featureData->getDescription())
                ];
            }
            $this->options = $options;
        }
        return $this->options;
    }
}
