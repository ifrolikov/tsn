<?php

namespace app\modules\admin\controllers;

use app\components\controller\RestController;
use app\components\managers\ContainerManager;
use app\components\rbac\Permissions;
use app\modules\admin\di\DiAliases;
use yii\base\Module;
use yii\di\Instance;

/**
 * Class UserController
 * @package app\modules\admin\controllers
 * @depends DiAliases, Permissions
 */
class UserController extends RestController
{
    protected $authRules = [
        [
            'actions' => ['create'],
            'allow' => true,
            'permissions' => [Permissions::USER_CREATE]
        ],
        [
            'actions' => ['update'],
            'allow' => true,
            'permissions' => [Permissions::USER_UPDATE]
        ],
        [
            'actions' => ['delete'],
            'allow' => true,
            'permissions' => [Permissions::USER_DELETE]
        ],
        [
            'actions' => ['index'],
            'allow' => true,
            'permissions' => [Permissions::USER_INDEX]
        ]
    ];

    /**
     * @return \app\components\tools\entities\AbstractEntity
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $factory = $this->containerManager->getFactory(DiAliases::USER_CREATE);
        $factory->getData()->setAttributes(\Yii::$app->getRequest()->getBodyParams());
        return $factory->create();
    }

    /**
     * @param int $id
     * @return \app\components\tools\entities\AbstractEntity
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate(int $id)
    {
        $factory = $this->containerManager->getFactory(DiAliases::USER_UPDATE);
        $factory->getData()->setAttributes(\Yii::$app->getRequest()->getBodyParams());
        return $factory->update($id);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    public function actionDelete()
    {
        $factory = $this->containerManager->getFactory(DiAliases::USER_DELETE);
        $factory->delete(\Yii::$app->getRequest()->getBodyParam('ids', []));
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionIndex()
    {
        $factory = $this->containerManager->getFactory(DiAliases::USER_INDEX);
        $factory->getData()->setAttributes(\Yii::$app->getRequest()->get());
        return $this->containerManager->getContainer()->invoke([$factory, 'index']);
    }
}
