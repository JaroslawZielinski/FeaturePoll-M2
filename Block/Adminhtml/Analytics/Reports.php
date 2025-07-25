<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\Analytics;

use Magento\Backend\Block\Template;

class Reports extends Legend
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->setTemplate('JaroslawZielinski_FeaturePoll::analytics/reports.phtml');
        Template::_construct();
    }
}
