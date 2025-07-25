<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\DTOHandler;

/**
 * @see https://medium.com/@sjoerd_bol/creating-a-dto-with-traits-in-php-1dea36d9d195#1631
 */
trait StaticCreateSelf
{
    public static function create(array $values): self
    {
        $dto = new self();
        foreach ($values as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }
        }
        return $dto;
    }
}
