<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Features;

use JaroslawZielinski\FeaturePoll\Api\FeatureRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Grid;
use JaroslawZielinski\FeaturePoll\Model\Data\Feature as FeatureData;
use JaroslawZielinski\FeaturePoll\Model\FeatureFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Edit extends Grid implements HttpGetActionInterface
{
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
        FeatureFactory $featureFactory,
        FeatureRepositoryInterface $featureRepository,
        DataPersistorInterface $dataPersistor,
        Context $context
    ) {
        $this->featureFactory = $featureFactory;
        $this->featureRepository = $featureRepository;
        parent::__construct($dataPersistor, $context);
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    public function execute()
    {
        $featureId = (int)$this->getRequest()->getParam('feature_id');
        $model = $this->featureFactory->create();
        if (!empty($featureId)) {
            /** @var FormData $modelData */
            $modelData = $this->featureRepository->get($featureId);
            $model->setData($modelData->__toArray());
            if (empty($modelData->getFormId())) {
                $this->messageManager->addErrorMessage(__('This feature no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->dataPersistor->set('feature_poll_features', $model);
        return parent::execute();
    }

    /**
     * @inheritDoc
     */
    protected function getTitle(): string
    {
        return (string)__('Edit Feature');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::features');
    }
}
