<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\ResourceModel;

use JaroslawZielinski\FeaturePoll\Api\Data\FeatureInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Feature extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('feature_poll_features', FeatureInterface::FEATURE_ID);
    }
}
