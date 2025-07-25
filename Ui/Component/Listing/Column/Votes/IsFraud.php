<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Ui\Component\Listing\Column\Votes;

use Magento\Ui\Component\Listing\Columns\Column;

class IsFraud extends Column
{
    public const COLUMN_ISFRAUD = 'is_fraud';

    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[self::COLUMN_ISFRAUD])) {
                    $item[self::COLUMN_ISFRAUD] = $this->getIsFraudHtml($item);
                }
            }
        }

        return $dataSource;
    }

    private function getOkBadge(bool $ok = true): string
    {
        $badge = $ok ? ['is-fraud', '✓'] : ['is-not-fraud', 'x'];
        list($badgeClass, $text) = $badge;
        return <<<EOT
<div class="{$badgeClass}">{$text}</div>
EOT;
    }

    private function getIsFraudHtml(array $item): string
    {
        $isActive = !!$item[self::COLUMN_ISFRAUD];
        return $this->getOkBadge($isActive);
    }
}
