<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Features;

use JaroslawZielinski\MySurvey\Controller\Adminhtml\Grid;

class Index extends Grid
{
    /**
     * @inheritDoc
     */
    protected function getTitle(): string
    {
        return (string)__('Features');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::features');
    }
}
