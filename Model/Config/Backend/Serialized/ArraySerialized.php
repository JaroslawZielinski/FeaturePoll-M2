<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Config\Backend\Serialized;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Phrase;
use Magento\Config\Model\Config\Backend\Serialized;

class ArraySerialized extends \Magento\Config\Model\Config\Backend\Serialized\ArraySerialized
{
    /**
     * @inheritDoc
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        if (is_array($value)) {
            unset($value['__empty']);
        }
        $this->validateRouteIntersection($value);
        $this->setValue($value);
        return Serialized::beforeSave();
    }

    /**
     * Validate that routes don't intersect between different rows
     * @throws LocalizedException
     */
    private function validateRouteIntersection(array $configData)
    {
        $usedRoutes = [];
        $routeToRowMapping = [];
        foreach ($configData as $rowIndex => $row) {
            $routes = $row['routes'];
            if (is_string($routes)) {
                $routes = explode(',', $routes);
            }
            $rowName = sprintf('Row %s', $rowIndex);
            foreach ($routes as $route) {
                $route = trim($route);
                if (isset($usedRoutes[$route])) {
                    $conflictingRowName = $routeToRowMapping[$route];
                    throw new LocalizedException(
                        new Phrase(
                            "The route '%1' cannot be in more than one row. It's already used in '%2' and you're trying to add it to '%3'.",
                            [$route, $conflictingRowName, $rowName]
                        )
                    );
                }
                $usedRoutes[$route] = true;
                $routeToRowMapping[$route] = $rowName;
            }
        }
    }
}
