<?php

namespace app\components\tools\module;

use ReflectionClass;
use yii\base\Module;

class BaseModule extends Module
{
    public function init()
    {
        parent::init();
        $this->configureContainer();
    }

    public function configureContainer()
    {
        $reflection = new ReflectionClass($this);
        $parentClassDirectory = dirname($reflection->getFileName());

        if (file_exists($parentClassDirectory)) {
            require_once $parentClassDirectory . '/di/bootstrap.php';
        }
    }
}