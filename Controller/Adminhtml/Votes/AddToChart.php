<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Votes;

use JaroslawZielinski\FeaturePoll\Api\Data\VoteInterface;
use JaroslawZielinski\FeaturePoll\Api\VoteRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\Vote\VoteResultsBarChartDataBuilder as BarChart;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session as BackendSession;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Vote\CollectionFactory;

class AddToChart extends Action
{
    /**
     * @var BackendSession
     */
    private $backendSession;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var VoteRepositoryInterface
     */
    private $voteRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        BackendSession $backendSession,
        Filter $filter,
        CollectionFactory $collectionFactory,
        VoteRepositoryInterface $voteRepository,
        Context $context
    ) {
        $this->backendSession = $backendSession;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->voteRepository = $voteRepository;
        parent::__construct($context);
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collection->addFieldToFilter(VoteInterface::IS_FRAUD, ['eq' => 0]);
        $this->backendSession->getData(BarChart::BARCHART_VOTES_COLECTION_TAG, $clear = true);
        $this->backendSession->setData(BarChart::BARCHART_VOTES_COLECTION_TAG, $collection->getAllIds());
        $size = $collection->getSize();
        $this->messageManager->addSuccess(__('A total of %1 record(s) added to the chart.', $size));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::votes');
    }
}
