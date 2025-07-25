<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\ResourceModel\Feature;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use JaroslawZielinski\FeaturePoll\Model\Feature;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'feature_id';

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(
            Feature::class,
            ResourceModel\Feature::class
        );
    }
}
