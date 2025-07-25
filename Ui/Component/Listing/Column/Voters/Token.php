<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Ui\Component\Listing\Column\Voters;

use Magento\Ui\Component\Listing\Columns\Column;
use JaroslawZielinski\FeaturePoll\Helper\Data;

class Token extends Column
{
    public const COLUMN_TOKEN = 'token';

    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[self::COLUMN_TOKEN])) {
                    $item[self::COLUMN_TOKEN] = $this->getTokenToEmail($item);
                }
            }
        }

        return $dataSource;
    }

    private function getEmailSpan(string $token): string
    {
        $email = Data::getEmailByToken($token);
        return <<<EOT
<span title="{$email}">{$token}</span>
EOT;
    }

    private function getTokenToEmail(array $item): string
    {
        $token = $item[self::COLUMN_TOKEN];
        return $this->getEmailSpan($token);
    }
}
