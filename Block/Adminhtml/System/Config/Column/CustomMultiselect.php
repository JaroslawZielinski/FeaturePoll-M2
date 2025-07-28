<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\System\Config\Column;

use Magento\Framework\View\Element\Html\Select;
use Magento\Framework\View\Element\Context;
use JaroslawZielinski\FeaturePoll\Model\Config\Source\Page\PageTypes;
use JaroslawZielinski\FeaturePoll\Block\Adminhtml\System\Config\Poll\Field\Items;

class CustomMultiselect extends Select
{
    public const SELECT_PLACEHOLDER = 'Select Page';

    /**
     * @var PageTypes
     */
    private $pageTypesSource;

    /**
     * Constructor
     */
    public function __construct(
        PageTypes $pageTypesSource,
        Context $context,
        array $data = []
    ) {
        $this->pageTypesSource = $pageTypesSource;
        parent::__construct($context, $data);
    }

    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value . '[]');
    }

    /**
     * Set "id" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->pageTypesSource->toOptionArray());
        }
        $this->setClass('multiselect admin__control-multiselect');
        $this->setExtraParams('multiple="multiple" data-validate="{\'required-entry\': true}"');
        $html = parent::_toHtml();
        // Add custom option functionality
        $html .= $this->getCustomOptionScript();
        return $html;
    }

    /**
     * Get custom option script
     */
    private function getCustomOptionScript()
    {
        $selectId = $this->getId();
        $routesWidth = Items::ROUTES_WIDTH;
        $placeholder = __(self::SELECT_PLACEHOLDER);
        return <<<EOT
<script>
    require(['jquery', 'chosen', 'mage/translate'], function($, chosen, \$t) {
        $(document).ready(function() {
            initCustomMultiselect('{$selectId}');
        });
        function initCustomMultiselect(elementId) {
            var selectElement = $('#' + elementId);
            if (selectElement.data('chosen-initialized')) {
                return;
            }
            selectElement.chosen({
                width: '{$routesWidth}',
                placeholder_text: '{$placeholder}'
            });
        }
        $(document).on('contentUpdated', function() {
            $('select.multiselect:not([data-chosen-initialized])').each(function() {
                initCustomMultiselect($(this).attr('id'));
            });
        });
    });
</script>
EOT;
    }

    /**
     * @inheritDoc
     */
    public function calcOptionHash($optionValue)
    {
        return sprintf('%u', crc32($this->getName() . '_' . $optionValue));
    }
}
