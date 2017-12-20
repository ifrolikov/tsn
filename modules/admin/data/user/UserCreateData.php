<?php

namespace app\modules\admin\data\user;

use app\components\managers\PasswordManager;
use app\components\tools\factory\AbstractData;

/**
 * Class UserCreateData
 * @package app\modules\admin\data\user
 */
class UserCreateData extends AbstractData
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $phone;
    /**
     * @var string
     */
    public $first_name;
    /**
     * @var string
     */
    public $second_name;
    /**
     * @var string
     */
    public $father_name;
    /**
     * @var string
     */
    public $password;
    /**
     * @var PasswordManager
     */
    private $passwordManager;

    /**
     * CreateData constructor.
     * @param PasswordManager $passwordManager
     * @param array $config
     */
    public function __construct(PasswordManager $passwordManager, array $config = [])
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
            [['email', 'phone', 'first_name', 'password'], 'required'],
            ['email', 'email'],
            [['phone', 'first_name', 'second_name', 'father_name'], 'string']
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