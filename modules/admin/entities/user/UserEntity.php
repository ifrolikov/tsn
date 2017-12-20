<?php

namespace app\modules\admin\entities\user;

use app\components\tools\entities\AbstractEntity;

/**
 * Class UserEntity
 * @package app\modules\admin\entities\user
 */
class UserEntity extends AbstractEntity
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
}