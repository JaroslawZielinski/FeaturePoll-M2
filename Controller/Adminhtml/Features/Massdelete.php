<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Features;

use JaroslawZielinski\FeaturePoll\Api\FeatureRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\Feature;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Feature\CollectionFactory;
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
     * @var FeatureRepositoryInterface
     */
    private $featureRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        FeatureRepositoryInterface $featureRepository,
        Context $context
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->featureRepository = $featureRepository;
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
        /** @var Feature $item */
        foreach ($collection as $item) {
            try {
                $itemData = $item->getDataModel();
                $this->featureRepository->delete($itemData);
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
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::features');
    }
}
