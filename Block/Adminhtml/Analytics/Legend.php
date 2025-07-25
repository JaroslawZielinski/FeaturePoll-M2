<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\Analytics;

use JaroslawZielinski\FeaturePoll\Model\Vote\VoteResultsBarChartDataBuilder;
use JaroslawZielinski\FeaturePoll\ViewModel\Content as ContentView;
use JaroslawZielinski\FeaturePoll\Helper\Data;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Legend extends Template
{
    /**
     * @var ContentView
     */
    private $contentView;

    /**
     * @inheritDoc
     */
    public function __construct(
        ContentView $contentView,
        Context $context,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        $this->contentView = $contentView;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    public function getPollResults(): array
    {
        return $this->_backendSession
                ->getData(VoteResultsBarChartDataBuilder::BARCHART_VOTES_COLECTION_LEGEND_TAG) ?? [];
    }

    public function getSortedKeys(): array
    {
        return array_keys($this->getPollResults());
    }

    /**
     * @throws LocalizedException
     */
    public function getDescriptions(string $surveyId): array
    {
        return $this->contentView->getFeaturePollArray($surveyId);
    }

    public function getResults(int $pollId, int $featureId, int $questionId): array
    {
        return $this->contentView->getResults($pollId, $featureId, $questionId);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->setTemplate('JaroslawZielinski_FeaturePoll::analytics/legend.phtml');
        parent::_construct();
    }
}
