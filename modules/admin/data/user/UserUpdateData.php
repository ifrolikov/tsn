<?php

namespace app\modules\admin\data\user;

use app\components\managers\PasswordManager;
use app\components\tools\factory\AbstractData;

/**
 * Class UserUpdateData
 * @package app\modules\admin\data\user
 */
class UserUpdateData extends AbstractData
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
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['email', 'email'],
            [['phone', 'first_name', 'second_name', 'father_name'], 'string']
        ]);
    }
}