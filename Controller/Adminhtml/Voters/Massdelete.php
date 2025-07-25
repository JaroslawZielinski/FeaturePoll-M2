<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Voters;

use JaroslawZielinski\FeaturePoll\Model\Voter;
use JaroslawZielinski\FeaturePoll\Api\VoterRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Voter\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

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
     * @var VoterRepositoryInterface
     */
    private $voterRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        VoterRepositoryInterface $voterRepository,
        Context $context
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->voterRepository = $voterRepository;
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
        /** @var Voter $item */
        foreach ($collection as $item) {
            try {
                $itemData = $item->getDataModel();
                $this->voterRepository->delete($itemData);
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
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::voters');
    }
}
