<?php

use app\modules\admin\di\DiAliases;
use yii\di\Instance;

return [
    DiAliases::USER_CREATE => [
        ['class' => \app\components\tools\factory\Factory::class],
        [
            Instance::of(\app\modules\admin\data\user\UserCreateData::class),
            Instance::of(\app\models\User::class),
            Instance::of(\app\modules\admin\entities\user\UserEntity::class)
        ]
    ],
    DiAliases::USER_UPDATE => [
        ['class' => \app\components\tools\factory\Factory::class],
        [
            Instance::of(\app\modules\admin\data\user\UserUpdateData::class),
            Instance::of(\app\models\User::class),
            Instance::of(\app\modules\admin\entities\user\UserEntity::class)
        ]
    ],
    DiAliases::USER_DELETE => [
        ['class' => \app\components\tools\factory\Factory::class],
        [
            null,
            Instance::of(\app\models\User::class)
        ]
    ],
    DiAliases::USER_INDEX => [
        ['class' => \app\components\tools\factory\Factory::class],
        [
            Instance::of(\app\modules\admin\data\user\UserIndexData::class),
            Instance::of(\app\models\User::class),
            Instance::of(\app\modules\admin\entities\user\UserEntity::class)
        ]
    ]
];