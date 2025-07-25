<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Config\Source\Page;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Layout\PageType\Config as PageTypeConfig;

class PageTypes extends AbstractSource implements OptionSourceInterface
{
    /**
     * @var PageTypeConfig
     */
    private $config;

    /**
     */
    public function __construct(PageTypeConfig $config)
    {
        $this->config = $config;
    }

    public function getOptions(): array
    {
        $pageTypes = $this->config->getPageTypes();
        $label = [];
        // Sort list of page types by label
        foreach ($pageTypes as $key => $row) {
            $label[$key] = $row['label'];
        }
        array_multisort($label, SORT_STRING, $pageTypes);
        foreach ($pageTypes as $pageTypeName => $pageTypeInfo) {
            $options[] = ['value' => $pageTypeName, 'label' => $pageTypeInfo['label'], 'params' => []];
        }
        return $options;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions(): ?array
    {
        if (empty($this->_options)) {
            $this->_options = $this->getOptions();
        }
        return $this->_options;
    }
}
