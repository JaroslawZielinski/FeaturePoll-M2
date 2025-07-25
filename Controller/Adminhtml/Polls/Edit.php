<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Polls;

use JaroslawZielinski\FeaturePoll\Api\PollRepositoryInterface;
use JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Grid;
use JaroslawZielinski\FeaturePoll\Model\Data\Poll as PollData;
use JaroslawZielinski\FeaturePoll\Model\PollFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Edit extends Grid implements HttpGetActionInterface
{
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
        PollFactory $pollFactory,
        PollRepositoryInterface $pollRepository,
        DataPersistorInterface $dataPersistor,
        Context $context
    ) {
        $this->pollFactory = $pollFactory;
        $this->pollRepository = $pollRepository;
        parent::__construct($dataPersistor, $context);
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    public function execute()
    {
        $pollId = (int)$this->getRequest()->getParam('poll_id');
        $model = $this->pollFactory->create();
        if (!empty($pollId)) {
            /** @var PollData $modelData */
            $modelData = $this->pollRepository->get($pollId);
            $model->setData($modelData->__toArray());
            if (empty($modelData->getPollId())) {
                $this->messageManager->addErrorMessage(__('This poll no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->dataPersistor->set('feature_poll_polls', $model);
        return parent::execute();
    }

    /**
     * @inheritDoc
     */
    protected function getTitle(): string
    {
        return (string)__('Edit Survey');
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski_MySurvey::surveys');
    }
}
