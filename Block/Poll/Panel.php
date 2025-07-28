<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Poll;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use JaroslawZielinski\FeaturePoll\Helper\Data;
use JaroslawZielinski\FeaturePoll\Model\Config;
use Magento\Framework\App\RequestInterface;

/**
 * @method setFeaturePoll(array $featurePoll): self
 * @method getFeaturePoll(): array
 */
class Panel extends Template
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Config
     */
    private $config;

    /**
     * @inheirtDoc
     */
    public function __construct(
        RequestInterface $request,
        Config $config,
        Context $context,
        array $data = []
    ) {
        $this->request = $request;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     */
    public function getCustomStyles(): ?string
    {
        return $this->config->getModuleCustomStyles();
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     * @throws \DateMalformedStringException
     */
    protected function _construct(): void
    {
        parent::_construct();
        $route = sprintf(
            '%s_%s_%s',
            $this->request->getRouteName(),
            $this->request->getControllerName(),
            $this->request->getActionName()
        );
        $pollItems = $this->config->getPanePollsItems();
        $key = Data::assocArrayKeySearch($route, $pollItems, 'routes');
        if (!empty($key)) {
            $pollId = $pollItems[$key]['item'];
            //use viewModel
            /** @var \JaroslawZielinski\FeaturePoll\ViewModel\Content $viewModel */
            $viewModel = $this->getViewModel();
            if ($viewModel->canShow($pollId)) {
                $featurePoll = $viewModel->getFeaturePoll($pollId);
                $this->setFeaturePoll($featurePoll);
                $this->setTemplate('JaroslawZielinski_FeaturePoll::poll/panel.phtml');
            }
        }
    }

    /**
     */
    public function getHtmlId(): ?string
    {
        return $this->config->getPaneHtmlId();
    }

    /**
     */
    public function getPollBadge(): ?string
    {
        return $this->config->getPanePollBadge();
    }
}
