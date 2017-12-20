<?php

namespace app\data\token;

use app\components\tools\entities\AbstractEntity;
use app\entities\token\TokenEntity;
use app\models\Token;
use app\models\User;
use thamtech\uuid\helpers\UuidHelper;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class TokenCreateFactory
{
    /**
     * @var TokenCreateData
     */
    private $data;
    /**
     * @var User
     */
    private $user;
    /**
     * @var Token
     */
    private $token;
    /**
     * @var AbstractEntity
     */
    private $entity;
    /**
     * @var string
     */
    private $notFoundExceptionClass;
    /**
     * @var UuidHelper
     */
    private $uuidManager;
    /**
     * @var string
     */
    private $internalExceptionClass;
    /**
     * @var string
     */
    private $validateExceptionClass;

    public function __construct(TokenCreateData $data, User $user, Token $token, TokenEntity $entity, UuidHelper $uuidManager,
                                string $notFoundExceptionClass = NotFoundHttpException::class,
                                string $internalExceptionClass = \Exception::class,
                                string $validateExceptionClass = UnprocessableEntityHttpException::class)
    {
        $this->data = $data;
        $this->user = $user;
        $this->token = $token;
        $this->entity = $entity;
        $this->notFoundExceptionClass = $notFoundExceptionClass;
        $this->uuidManager = $uuidManager;
        $this->internalExceptionClass = $internalExceptionClass;
        $this->validateExceptionClass = $validateExceptionClass;
    }

    public function create()
    {
        $this->validateData();
        if (!$this->user = $this->user::findOne($this->data->toArray())) {
            $this->except($this->notFoundExceptionClass);
        }
        $this->token->setAttributes([
            'token' => $this->uuidManager::uuid(),
            'user_id' => $this->user->id,
            'data' => json_encode($this->user->toArray(), JSON_UNESCAPED_UNICODE)
        ]);
        $this->validateToken();
        if (!$this->token->save(false)) {
            $this->except($this->internalExceptionClass, 'cant save token');
        }
        $this->entity->setAttributes($this->token->toArray());
        return $this->entity;
    }

    /**
     * @return TokenCreateData
     */
    public function getData(): TokenCreateData
    {
        return $this->data;
    }

    /**
     * @param string $exceptionClass
     * @param string|null $message
     */
    private function except(string $exceptionClass, string $message = null)
    {
        throw new $exceptionClass($message);
    }

    private function validateData()
    {
        if (!$this->data->validate()) {
            $this->except($this->validateExceptionClass, json_encode($this->data->errors, JSON_UNESCAPED_UNICODE));
        }
    }

    private function validateToken()
    {
        if (!$this->token->validate()) {
            $this->except($this->internalExceptionClass, json_encode($this->token->errors, JSON_UNESCAPED_UNICODE));
        }
    }
}