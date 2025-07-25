<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Votes;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\Model\Session as BackendSession;
use JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Grid;
use JaroslawZielinski\FeaturePoll\Model\Vote\VoteResultsBarChartDataBuilder as BarChart;

class Clear extends Grid
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
        DataPersistorInterface $dataPersistor,
        Context $context
    ) {
        $this->backendSession = $backendSession;
        parent::__construct($dataPersistor, $context);
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::clear');
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    public function execute()
    {
        $this->backendSession->getData(BarChart::BARCHART_VOTES_COLECTION_TAG, $clear = true);
        $this->backendSession->getData(BarChart::BARCHART_VOTES_COLECTION_LEGEND_TAG, $clear = true);
        $this->messageManager->addSuccess(__('Graphic data has been cleared.'));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('jaroslawzielinski_featurepoll/votes/index');
    }

    /**
     * @inheritDoc
     */
    protected function getTitle(): string
    {
        return (string)__('Clearing graphic data');
    }
}
