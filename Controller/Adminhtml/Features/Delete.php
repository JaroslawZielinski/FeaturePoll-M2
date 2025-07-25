<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Features;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use JaroslawZielinski\FeaturePoll\Api\FeatureRepositoryInterface;

class Delete extends Action
{
    /**
     * @var FeatureRepositoryInterface
     */
    private $featureRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        FeatureRepositoryInterface $featureRepository,
        Context $context
    ) {
        $this->featureRepository = $featureRepository;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $featureId = (int)$this->getRequest()->getParam('feature_id');
        if (!empty($featureId)) {
            try {
                $this->featureRepository->deleteById($featureId);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This feature no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->messageManager->addSuccessMessage(__('The feature successfully deleted [ID: %1].', $featureId));
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
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
