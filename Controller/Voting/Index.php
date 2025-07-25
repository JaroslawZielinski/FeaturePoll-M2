<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Voting;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use JaroslawZielinski\FeaturePoll\Model\Config;

class Index extends Action
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        Config $config,
        PageFactory $resultPageFactory,
        Context $context
    ) {
        $this->config = $config;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__($this->config->getSettingsDescription()));
        return $resultPage;
    }
}
