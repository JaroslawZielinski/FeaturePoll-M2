<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Colour extends Field
{
    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $html = $element->getElementHtml();
        $value = $element->getData('value');
        $htmlId = $element->getHtmlId();
        $html .= <<<script
        <script type="text/javascript">
            require(['jquery', 'jquery/colorpicker/js/colorpicker'], function ($) {
                $(document).ready(function () {
                    const thisElement = $("#{$htmlId}");
                    thisElement.css('backgroundColor', "{$value}");
                    thisElement.ColorPicker({
                        color: "{$value}",
                        onChange: function (hsb, hex, rgb) {
                            thisElement.css('backgroundColor', '#' + hex).val('#' + hex);
                        }
                    });
                });
            });
        </script>
script;
        return $html;
    }
}
