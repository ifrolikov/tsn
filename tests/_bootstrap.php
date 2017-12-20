<?php

use app\components\managers\PasswordManager;
use app\models\Token;
use app\models\User;

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/console.php');
$application = new yii\console\Application($config);


/**
 * @param array $roles
 * @return \app\models\Token|\yii\db\BaseActiveRecord
 * @throws Exception
 */
function createToken(array $roles = []): \app\models\Token
{
    $faker = \Faker\Factory::create();

    /** @var User $user */
    $user = createModel(User::class, [
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'first_name' => $faker->firstName,
        'father_name' => $faker->firstName,
        'second_name' => $faker->firstName,
        'password' => (new PasswordManager())->setPassword($faker->password)->encrypt()
    ]);

    if ($roles) {
        foreach ($roles as $role) {
            createModel(\app\models\UserRole::class, [
                'user_id' => $user->id,
                'role' => $role
            ]);
        }
    }

    return createModel(Token::class, [
        'token' => $faker->uuid,
        'user_id' => $user->id,
        'data' => json_encode(json_encode($user->toArray(), JSON_UNESCAPED_UNICODE))
    ]);
}

/**
 * @param string $class
 * @param array $attributes
 * @return \yii\db\BaseActiveRecord
 * @throws Exception
 */
function createModel(string $class, array $attributes): \yii\db\BaseActiveRecord
{
    /** @var \yii\db\BaseActiveRecord $model */
    $model = new $class();

    $model->setAttributes($attributes);
    if (!$model->save()) {
        throw new \Exception(json_encode([
            'class' => $class,
            'errors' => $model->errors
        ], JSON_UNESCAPED_UNICODE));
    }
    $model->refresh();
    return $model;
}