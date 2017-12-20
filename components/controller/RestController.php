<?php

namespace app\components\controller;

use app\components\auth\AuthMethod;
use app\components\managers\ContainerManager;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\rest\Controller;

/**
 * Class RestController
 * @package app\components\controller
 * @depends AuthMethod, AccessControl
 */
class RestController extends Controller
{
    protected $auth = true;
    protected $authRules = [];

    /**
     * @var ContainerManager
     */
    protected $containerManager;

    public function __construct(string $id, Module $module, array $config = [], ContainerManager $containerManager)
    {
        parent::__construct($id, $module, $config);
        $this->containerManager = $containerManager;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if ($this->auth) {
            $behaviors['authenticator'] = AuthMethod::class;

            if ($this->authRules) {
                $behaviors['access'] = [
                    'class' => AccessControl::class,
                    'rules' => $this->authRules
                ];
            }
        }

        return $behaviors;
    }
}