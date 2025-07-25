<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Poll\Panel;


use Magento\Framework\View\Element\Template;


/**
 * @method getHtmlId() string
 * @method setHtmlId(string $htmlId) self
 * @method getFeaturePoll() array
 * @method setFeaturePoll(array $featurePoll) self
 */
class Content extends Template
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();

        $this->setTemplate('JaroslawZielinski_FeaturePoll::poll/panel/content.phtml');
    }
}
