<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Config\Source\Features;

use Magento\Framework\Option\ArrayInterface;

class IsFraud implements ArrayInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @inheritDoc
     */
    public function toOptionArray(): ?array
    {
        if (empty($this->options)) {
            $this->options = [
                ['value' => 0, 'label' => 'false'],
                ['value' => 1, 'label' => 'true']
            ];
        }
        return $this->options;
    }
}
