<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Voters;

use JaroslawZielinski\FeaturePoll\Api\VoterRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends Action
{
    /**
     * @var VoterRepositoryInterface
     */
    private $voterRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        VoterRepositoryInterface $voterRepository,
        Context $context
    ) {
        $this->voterRepository = $voterRepository;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        if (!empty($id)) {
            try {
                $this->voterRepository->deleteById($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This voter no longer exists.'));
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
        $this->messageManager->addSuccessMessage(__('The voter successfully deleted [ID: %1].', $id));
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
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
