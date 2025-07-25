<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Ui\Component\Listing\Column\Polls;

class Date extends \Magento\Ui\Component\Listing\Columns\Date
{
    /**
     * @inheritDoc
     */
    public function prepare(): void
    {
        parent::prepare();
        $config = $this->getConfiguration();
        if (isset($config['filter'])) {
            $filterDate = $config['filterDate'] ?? $this->timezone->getDateFormatWithLongYear();
            $config['options']['dateFormat'] = $filterDate;
            $config['filter'] = [
                'filterType' => 'dateRange',
                'templates' => [
                    'date' => [
                        'options' => [
                            'dateFormat' => $filterDate
                        ]
                    ]
                ]
            ];
        }
        $this->setData('config', $config);
    }
}
