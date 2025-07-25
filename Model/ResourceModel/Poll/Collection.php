<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\ResourceModel\Poll;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel;
use JaroslawZielinski\FeaturePoll\Model\Poll;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'poll_id';

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(
            Poll::class,
            ResourceModel\Poll::class
        );
    }
}
