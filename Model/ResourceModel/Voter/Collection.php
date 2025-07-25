<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\ResourceModel\Voter;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use JaroslawZielinski\FeaturePoll\Model\Voter;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'voter_id';

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(
            Voter::class,
            ResourceModel\Voter::class
        );
    }
}
