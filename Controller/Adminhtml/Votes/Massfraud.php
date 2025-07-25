<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Votes;

use JaroslawZielinski\FeaturePoll\Model\Vote;
use JaroslawZielinski\FeaturePoll\Api\VoteRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Vote\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class Massfraud extends Action
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
     * @var VoteRepositoryInterface
     */
    private $voteRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        VoteRepositoryInterface $voteRepository,
        Context $context
    ) {
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
        $count = 0;
        /** @var Vote $item */
        foreach ($collection as $item) {
            try {
                $itemData = $item->getDataModel();
                $itemData->setIsFraud(true);
                $this->voteRepository->save($itemData);
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                continue;
            }
            $count++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been marked as fraud.', $count));
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
