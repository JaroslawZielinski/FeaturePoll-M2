<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Config\Source\Voters;

use Magento\Framework\Option\ArrayInterface;

class Origin implements ArrayInterface
{
    public const CUSTOMER_ORIGIN = 'Customer';

    public const GUEST_CUSTOMER_ORIGIN = 'Guest Customer';

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
                ['value' => 0, 'label' => __(self::GUEST_CUSTOMER_ORIGIN)],
                ['value' => 1, 'label' => __(self::CUSTOMER_ORIGIN)]
            ];
        }
        return $this->options;
    }
}
