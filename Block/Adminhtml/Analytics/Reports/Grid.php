<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\Analytics\Reports;

use JaroslawZielinski\FeaturePoll\Model\Vote\LegendCollectionFactory;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Data\Collection;
use Magento\Framework\Json\Helper\Data as JsonHelper;

/**
 * @method getFormHtmlId(): string
 * @method setFormHtmlId(string $htmlId): self
 * @method getFeaturepollId(): string
 * @method setFeaturepollId(string $featurePollId): self
 */
class Grid extends Template
{
    /**
     * @var LegendCollectionFactory
     */
    private $legendCollectionFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        LegendCollectionFactory $legendCollectionFactory,
        Context $context,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        $this->legendCollectionFactory = $legendCollectionFactory;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->setTemplate('JaroslawZielinski_FeaturePoll::analytics/reports/grid.phtml');
        parent::_construct();
    }

    /**
     * @throws \Exception
     */
    public function getInitialCollection(string $featurePollId): Collection
    {
        return $this->legendCollectionFactory->create($featurePollId);
    }
}
