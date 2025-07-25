<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\DTOHandler;

/**
 * @see https://medium.com/@sjoerd_bol/creating-a-dto-with-traits-in-php-1dea36d9d195#734e
 */
trait ToArray
{
    public function toArray(): array
    {
        $object = get_object_vars($this);
        return $this->objectToArray($object);
    }

    public function toNotEmptyArray(): array
    {
        $emptyArray = $this->toArray();
        return $this->array_filter_recursive($emptyArray);
    }

    /**
     * @see https://stackoverflow.com/questions/2476876/how-do-i-convert-an-object-to-an-array#answer-2476954
     */
    private function objectToArray ($object)
    {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }
        return array_map([$this, 'objectToArray'], (array) $object);
    }

    /**
     * @see https://stackoverflow.com/questions/10214531/how-to-remove-empty-values-from-multidimensional-array-in-php#answer-32382871
     */
    private function array_filter_recursive(array $input): array
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->array_filter_recursive($value);
            } elseif (is_object($value)) {
                $arrayValue = $this->objectToArray($value);
                $value = $this->array_filter_recursive($arrayValue);
            }
        }
        return array_filter($input, function($value, $key) {
            return !empty($value);
        }, ARRAY_FILTER_USE_BOTH);
    }
}
