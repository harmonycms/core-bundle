<?php

namespace Harmony\Bundle\CoreBundle\Component\Routing;

use Symfony\Component\Routing\RouteCollectionBuilder as BaseRouteCollectionBuilder;

/**
 * Class RouteCollectionBuilder
 *
 * @package Harmony\Bundle\CoreBundle\Component\Routing
 */
class RouteCollectionBuilder extends BaseRouteCollectionBuilder
{

    /**
     * @param null|string $name
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function hasRoute(?string $name): bool
    {
        if (null !== $name) {
            $reflectionClass          = new \ReflectionClass($this);
            $reflectionRoutesProperty = $reflectionClass->getParentClass()->getProperty('routes');
            $reflectionRoutesProperty->setAccessible(true);
            $routes = $reflectionRoutesProperty->getValue($this);

            foreach ($routes as $route) {
                if ($route instanceof BaseRouteCollectionBuilder) {
                    $reflectionRouteProperty = (new \ReflectionClass($route))->getProperty('routes');
                    $reflectionRouteProperty->setAccessible(true);
                    $routeArray = $reflectionRouteProperty->getValue($route);
                    if (isset($routeArray[$name])) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}