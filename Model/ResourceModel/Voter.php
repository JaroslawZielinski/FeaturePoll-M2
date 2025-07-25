<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\ResourceModel;

use JaroslawZielinski\FeaturePoll\Api\Data\VoterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Voter extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('feature_poll_voters', VoterInterface::VOTER_ID);
    }
}
