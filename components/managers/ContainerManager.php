<?php

namespace app\components\managers;

use app\components\tools\factory\Factory;
use yii\di\Container;
use yii\di\Instance;

/**
 * Class ContainerManager
 * @package app\components\managers
 * @depends Instance, Factory, \Yii, Container
 */
class ContainerManager
{
    public function getFactory(string $class): Factory
    {
        /** @var Factory $factory */
        $factory = Instance::of($class)->get();
        return $factory;
    }

    public function getContainer(): Container
    {
        return \Yii::$container;
    }
}