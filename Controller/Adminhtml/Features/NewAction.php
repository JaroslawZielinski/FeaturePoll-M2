<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Features;

use JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Grid;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\Request\DataPersistorInterface;

class NewAction extends Grid implements HttpGetActionInterface
{
    /**
     * @var ForwardFactory
     */
    private $resultForwardFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        ForwardFactory $resultForwardFactory,
        DataPersistorInterface $dataPersistor,
        Context $context
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($dataPersistor, $context);
    }

    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }

    /**
     * @inheritDoc
     */
    protected function getTitle(): string
    {
        return (string)__('New Feature');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_FeaturePoll::features');
    }
}
