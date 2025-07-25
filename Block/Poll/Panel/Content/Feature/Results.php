<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Poll\Panel\Content\Feature;

use Magento\Framework\View\Element\Template;

/**
 * @method setValue(int $value): self
 * @method getValue(): int
 * @method setTotal(int $total): self
 * @method getTotal(): int
 */
class Results extends Template
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();

        $this->setTemplate('JaroslawZielinski_FeaturePoll::poll/panel/content/feature/results.phtml');
    }
}
