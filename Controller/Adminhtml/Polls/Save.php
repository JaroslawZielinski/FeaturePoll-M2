<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Polls;

use JaroslawZielinski\FeaturePoll\Api\PollRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\PollFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Psr\Log\LoggerInterface;

class Save extends Action
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var PollFactory
     */
    private $pollFactory;

    /**
     * @var PollRepositoryInterface
     */
    private $pollRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        LoggerInterface $logger,
        PollFactory $pollFactory,
        PollRepositoryInterface $pollRepository,
        Context $context
    ) {
        $this->logger = $logger;
        $this->pollFactory = $pollFactory;
        $this->pollRepository = $pollRepository;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $isNew = false;
        if (empty($data['poll_id'])) {
            unset($data['poll_id']);
            $isNew = true;
        }
        try {
            $model = $this->pollFactory->create();
            $model->setData($data);
            $modelData = $model->getDataModel();
            $modelData = $this->pollRepository->save($modelData);
            $this->messageManager->addSuccessMessage(__('You saved the poll [ID: %1].', $modelData->getPollId()));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            $this->messageManager->addErrorMessage(nl2br($e->getMessage()));
            return $resultRedirect->setPath('*/*/index');
        }
        if (!empty($this->getRequest()->getParam('back'))) {
            $pollId = (int)($isNew ? $modelData->getPollId() : $this->getRequest()->getParam('poll_id'));
            return $resultRedirect->setPath('*/*/edit', ['poll_id' => $pollId, '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/index');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::polls');
    }
}
