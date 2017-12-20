<?php

namespace app\controllers;

use app\components\controller\RestController;
use app\config\di\DiAliases;
use app\data\token\TokenCreateFactory;
use yii\di\Instance;

/**
 * Class TokenController
 * @package app\controllers
 */
class TokenController extends RestController
{
    protected $auth = false;

    /**
     * @return \app\components\tools\entities\AbstractEntity|\app\entities\token\TokenEntity
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        /** @var TokenCreateFactory $factory */
        $factory = Instance::of(DiAliases::TOKEN_CREATE)->get();
        $factory->getData()->setAttributes(\Yii::$app->getRequest()->getBodyParams());
        return $factory->create();
    }
}