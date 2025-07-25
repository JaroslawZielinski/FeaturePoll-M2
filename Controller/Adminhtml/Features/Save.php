<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Features;

use JaroslawZielinski\FeaturePoll\Api\FeatureRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Model\FeatureFactory;
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
     * @var FeatureFactory
     */
    private $featureFactory;

    /**
     * @var FeatureRepositoryInterface
     */
    private $featureRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        LoggerInterface $logger,
        FeatureFactory $featureFactory,
        FeatureRepositoryInterface $featureRepository,
        Context $context
    ) {
        $this->logger = $logger;
        $this->featureFactory = $featureFactory;
        $this->featureRepository = $featureRepository;
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
        if (empty($data['feature_id'])) {
            unset($data['feature_id']);
            $isNew = true;
        }
        try {
            $model = $this->featureFactory->create();
            $model->setData($data);
            $modelData = $model->getDataModel();
            $modelData = $this->featureRepository->save($modelData);
            $this->messageManager->addSuccessMessage(__('You saved the feature [ID: %1].', $modelData->getFeatureId()));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            $this->messageManager->addErrorMessage(nl2br($e->getMessage()));
            return $resultRedirect->setPath('*/*/index');
        }
        if (!empty($this->getRequest()->getParam('back'))) {
            $featureId = (int)($isNew ? $modelData->getFeatureId() : $this->getRequest()->getParam('feature_id'));
            return $resultRedirect->setPath('*/*/edit', ['feature_id' => $featureId, '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/index');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::features');
    }
}
