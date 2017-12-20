<?php

namespace app\components\auth;

use app\models\Token;
use app\models\User;
use yii\base\NotSupportedException;
use yii\di\Instance;
use yii\web\IdentityInterface;

/**
 * Class UserIdentity
 * @package app\components\auth
 * @depends User, Token, NotSupportedException
 */
class UserIdentity implements IdentityInterface
{
    /**
     * @var User
     */
    public static $userClass = User::class;
    /**
     * @var Token
     */
    public static $tokenClass = Token::class;
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     * @return UserIdentity|null|object
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public static function findIdentity($id)
    {
        $user = static::$userClass::findOne($id);
        if (!$user) {
            return null;
        }
        /** @var UserIdentity $identity */
        $identity = \Yii::$container->get(static::class, [$user]);
        return $identity;
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($token = static::$tokenClass::findOne(['token' => $token])) {
            /** @var UserIdentity $identity */
            $identity = \Yii::$container->get(static::class, [$token->user]);
            return $identity;
        }
        return null;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->user->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     * @throws NotSupportedException
     */
    public function getAuthKey()
    {
        throw new NotSupportedException();
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @see getAuthKey()
     * @throws NotSupportedException
     */
    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException();
    }
}