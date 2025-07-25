<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\ResourceModel;


use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Vote extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('feature_poll_votes', VoteInterface::VOTE_ID);
    }
}
