<?php

namespace app\data\token;

use app\components\managers\PasswordManager;
use app\components\tools\factory\AbstractData;

/**
 * Class TokenCreateData
 * @package app\data\token
 */
class TokenCreateData extends AbstractData
{
    /**
     * @var string
     */
    public $phone;
    /**
     * @var string
     */
    public $password;
    /**
     * @var PasswordManager
     */
    private $passwordManager;

    /**
     * TokenCreateData constructor.
     * @param array $config
     * @param PasswordManager $passwordManager
     */
    public function __construct(array $config = [], PasswordManager $passwordManager)
    {
        parent::__construct($config);
        $this->passwordManager = $passwordManager;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [$this->attributes(), 'required'],
            [['password', 'phone'], 'string']
        ]);
    }

    /**
     * @param array $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (isset($values['password'])) {
            $values['password'] = $this->passwordManager->setPassword($values['password'])->encrypt();
        }
        parent::setAttributes($values, $safeOnly);
    }
}