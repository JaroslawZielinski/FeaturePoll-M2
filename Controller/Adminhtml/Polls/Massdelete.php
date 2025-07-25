<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Polls;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use JaroslawZielinski\FeaturePoll\Api\PollRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Poll\CollectionFactory;
use JaroslawZielinski\FeaturePoll\Model\Poll;

class Massdelete extends Action
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var PollRepositoryInterface
     */
    private $pollRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        PollRepositoryInterface $pollRepository,
        Context $context
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->pollRepository = $pollRepository;
        parent::__construct($context);
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $count = 0;
        /** @var Poll $item */
        foreach ($collection as $item) {
            try {
                $itemData = $item->getDataModel();
                $this->pollRepository->delete($itemData);
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                continue;
            }
            $count++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $count));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::polls');
    }
}
