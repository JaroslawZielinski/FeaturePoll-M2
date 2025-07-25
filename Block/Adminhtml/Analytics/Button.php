<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\Analytics;

use Magento\Backend\Block\Template;

/**
 * @method getHtmlId(): string
 * @method getHtmlClass(): string
 * @method getLabel(): string
 * @method getRedirectUrl(): string
 */
class Button extends Template
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->setTemplate('JaroslawZielinski_FeaturePoll::analytics/button.phtml');;
        parent::_construct();
    }
}
