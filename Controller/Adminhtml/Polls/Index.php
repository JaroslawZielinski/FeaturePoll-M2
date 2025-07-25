<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Polls;

use JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Grid;

class Index extends Grid
{
    /**
     * @inheritDoc
     */
    protected function getTitle(): string
    {
        return (string)__('Polls');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::polls');
    }
}
