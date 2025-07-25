<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Type extends AbstractSource implements OptionSourceInterface
{
    public const TYPE_OPEN = 1;

    public const TYPE_CLOSED = 2;

    public const TYPES = [
        self::TYPE_OPEN => 'Open',
        self::TYPE_CLOSED => 'Closed'
    ];

    public function getOptions(): array
    {
        $options = [];
        foreach (self::TYPES as $value => $label) {
            $options[] = [
                'label' => __($label),
                'value' => $value
            ];
        }
        return $options;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions(): array
    {
        if (empty($this->_options)) {
            $this->_options = $this->getOptions();
        }
        return $this->_options;
    }
}
