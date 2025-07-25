<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Voters;

use JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Grid;

class Index extends Grid
{
    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::voters');
    }

    /**
     * @inheritDoc
     */
    protected function getTitle(): string
    {
        return (string)__('Voters');
    }
}
