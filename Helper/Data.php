<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Phrase;


class Data extends AbstractHelper
{
    public static function assocArrayKeySearch(string $needle, array $array, string $subPath = null): ?string
    {
        $found = null;
        array_walk($array, function ($k, $v) use ($needle, $subPath, &$found) {
            $hay = !empty($subPath) ? $k[$subPath] ?? $k : $k;
            if (in_array($needle, $hay)) {
                $found = $v;
            }
        });
        return $found;
    }

    /**
     * @@see https://colordesigner.io/gradient-generator
     * @see https://cats2d.com/colormaps/rainbow.png
     */
    public static function getPercentageColor(float $percents, bool $greyscale = false): string
    {
        if ($greyscale) {
            return '#b5b5b1';
        }
        switch ($percents) {
            default:
            case $percents > 0.0 && $percents <= 10.0:
                return '#0000ff';
            case $percents > 10.0 && $percents <= 20.0:
                return '#0c87ff';
            case $percents > 20.0 && $percents <= 30.0:
                return '#00f1ff';
            case $percents > 30.0 && $percents <= 40.0:
                return '#00ffa0';
            case $percents > 40.0 && $percents <= 50.0:
                return '#00ff36';
            case $percents > 50.0 && $percents <= 60.0:
                return '#36ff0c';
            case $percents > 60.0 && $percents <= 70.0:
                return '#a0ff01';
            case $percents > 70.0 && $percents <= 80.0:
                return '#fef305';
            case $percents > 80.0 && $percents <= 90.0:
                return '#ff8500';
            case $percents > 90.0 && $percents < 100.0:
                return '#ff2500';
            case $percents >= 100.0:
                return '#ff0000';
        }
    }

    /**
     * @@see https://colordesigner.io/gradient-generator
     * @see https://cats2d.com/colormaps/rainbow.png
     */
    public static function getPercentageColorClass(float $percents, bool $greyscale = false): string
    {
        $progress = (int)($percents + 0.5);
        return sprintf('result-progress-%s', $progress);
    }


    public static function getRequired(bool $isRequired = true, string $requiredMessage = 'This is a required field.'): string
    {
        return sprintf(
            'data-msg-required=\'%s\' data-validate=\'{"required":%s}\'',
            __($requiredMessage),
            $isRequired ? 'true' : 'false'
        );
    }

    /**
     * invert - shows sign of delta 0 means DateTime1 >= DateTime2, 1 means DateTime1 < DateTime2
     */
    public static function getTimeDiff(\DateTime $dateTime1, \DateTime $dateTime2): array
    {
        $diff = $dateTime1->diff($dateTime2);
        $invert = $diff->invert;
        $days = (int)$diff->format('%r%a');
        $hours = $diff->h;
        $minutes = $diff->i;
        $seconds = $diff->s;
        return [$invert, $days, $hours, $minutes, $seconds];
    }

    public static function getDateDiff(\DateTime $date1, \DateTime $date2): int
    {
        list ($invert, $days, $hours, $minutes, $seconds) = self::getTimeDiff($date2, $date1);
        $sign = $invert <= 0 ? 1 : -1;
        return $sign * $days;
    }

    /**
     */
    public static function getEmailByToken(string $token): string
    {
        return base64_decode($token);
    }

    /**
     */
    public static function getTokenByEmail(string $email): string
    {
        return base64_encode($email);
    }

    public static function escapeQuotes(?string $input): ?string
    {
        if (empty($input)) {
            return null;
        }
        return str_replace(['\''], ['&#39;'], $input);
    }

    public static function implodeFeaturePollId(int $pollId, int $featureId, int $questionId): string
    {
        return sprintf('featurepoll-%s-%s-%s', $pollId, $featureId, $questionId);
    }

    public static function explodeFeaturePollId(string $featurePollId): array
    {
        $featurePoll = str_replace('featurepoll-', '', $featurePollId);
        $featurePollArray = explode('-', $featurePoll);
        $pollId = $featurePollArray[0];
        $featureId = $featurePollArray[1];
        $questionId = $featurePollArray[2];
        return [
            (int)$pollId,
            (int)$featureId,
            (int)$questionId
        ];
    }

    public static function formatMinutes(int $minutes): Phrase
    {
        if ($minutes < 60) {
            $minutesPhraseString = 1 === $minutes ? '%1 minute' : '%1 minutes';
            return __($minutesPhraseString, $minutes);
        }
        if (0 === $minutes % 60) {
            $hours = $minutes / 60;
            $minutesPhraseString = 1 === $hours ? '%1 hour' : '%1 hours';
            return __($minutesPhraseString, $hours);
        }
        $hours = intval($minutes / 60);
        $remainingMinutes = $minutes % 60;
        $hoursPhraseString = 1 === $hours ? '%1 hour' : '%1 hours';
        $minutesPhraseString = 1 === $remainingMinutes ? '%2 minute' : '%2 minutes';
        return __($hoursPhraseString . ' ' . $minutesPhraseString, $hours, $remainingMinutes);
    }

    public static function getProgressPercents(int $value, int $total): float
    {
        return $value > 0
            ? (float)number_format((100 * $value) / $total, 2, '.', '')
            : 0.0;
    }

    public static function getResults(int $value, int $total): string
    {
        $progressPercents = self::getProgressPercents((int)$value, (int)$total);
        return sprintf('%s&#37; [%s/%s]', (int)$progressPercents, $value, $total);
    }
}
