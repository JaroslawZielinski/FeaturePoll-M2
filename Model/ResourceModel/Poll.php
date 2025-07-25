<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\ResourceModel;

use JaroslawZielinski\FeaturePoll\Api\Data\PollInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Poll extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('feature_poll_polls', PollInterface::POLL_ID);
    }
}
