<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\ResourceModel\Vote;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use JaroslawZielinski\FeaturePoll\Model\Vote;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'vote_id';

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(
            Vote::class,
            ResourceModel\Vote::class
        );
    }

    /**
     * @inheritDoc
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->columns([
            'featurepoll_id' => new \Zend_Db_Expr("CONCAT('featurepoll-', main_table.poll_id, '-', main_table.feature_id, '-', main_table.question_id)"),
            'featurepoll_value' => 'main_table.details',
        ]);
        $this->addFilterToMap('featurepoll_id', 'main_table.featurepoll_id');
        $this->addFilterToMap('featurepoll_value', 'main_table.details');
        return $this;
    }
}
