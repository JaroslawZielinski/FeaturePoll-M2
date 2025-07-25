<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use JaroslawZielinski\FeaturePoll\Model\Config;

class RulesAndRegulations implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     */
    public function getRulesAndRegulations(): ?string
    {
        return $this->config->getSettingsRulesAndRegulations();
    }

    /**
     */
    public function getPaneHtmlId(): ?string
    {
        return $this->config->getPaneHtmlId();
    }
}
