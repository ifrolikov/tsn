<?php

use app\config\di\DiAliases;
use yii\di\Instance;

return [
    DiAliases::TOKEN_CREATE => [
        ['class' => \app\data\token\TokenCreateFactory::class],
        [
            Instance::of(\app\data\token\TokenCreateData::class),
            Instance::of(\app\models\User::class),
            Instance::of(\app\models\Token::class),
            Instance::of(\app\entities\token\TokenEntity::class)
        ]
    ]
];