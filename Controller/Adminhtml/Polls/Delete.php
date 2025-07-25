<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Polls;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use JaroslawZielinski\FeaturePoll\Api\PollRepositoryInterface;

class Delete extends Action
{
    /**
     * @var PollRepositoryInterface
     */
    private $pollRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        PollRepositoryInterface $pollRepository,
        Context $context
    ) {
        $this->pollRepository = $pollRepository;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $pollId = (int)$this->getRequest()->getParam('poll_id');
        if (!empty($pollId)) {
            try {
                $this->pollRepository->deleteById($pollId);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This poll no longer exists.'));
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
        $this->messageManager->addSuccessMessage(__('The poll successfully deleted [ID: %1].', $pollId));
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
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
