<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml;

use JaroslawZielinski\FeaturePoll\Model\Vote\VoteResultsBarChartDataBuilder;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Session as BackendSession;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;

/**
 * @method getUrlKey(): string
 * @method getButtonLabel(): string
 * @method getButtonPosition(): string
 */
class Analytics extends Template
{
    /**
     * @var BackendSession
     */
    private $backendSession;

    /**
     * @inheritDoc
     */
    public function __construct(
        BackendSession $backendSession,
        Context $context,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    )
    {
        $this->backendSession = $backendSession;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        /** @var array $collectionIds */
        $collectionIds = $this->backendSession
            ->getData(VoteResultsBarChartDataBuilder::BARCHART_VOTES_COLECTION_TAG);
        if (!empty($collectionIds)) {
            $this->setTemplate('JaroslawZielinski_FeaturePoll::analytics.phtml');
        }
        parent::_construct();
    }
}
