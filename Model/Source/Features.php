<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Source;

use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Feature\CollectionFactory as FeatureCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Features implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $attributeOptionsList;

    /**
     * @var FeatureCollectionFactory
     */
    private $featureCollectionFactory;

    /**
     */
    public function __construct(FeatureCollectionFactory $featureCollectionFactory)
    {
        $this->featureCollectionFactory = $featureCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        if (empty($this->attributeOptionsList)) {
            $this->attributeOptionsList = [
                ['value' => null, 'label' => __('Chose item')]
            ];
            $formCollection = $this->featureCollectionFactory
                ->create()
                ->load();
            foreach ($formCollection as $item) {
               $this->attributeOptionsList[] = [
                   'value' => $item['feature_id'],
                   'label' => $item['group_name']
               ];
            }
        }
        return $this->attributeOptionsList;
    }
}
