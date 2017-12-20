<?php

namespace app\modules\admin\data\user;

use app\components\tools\factory\AbstractData;

/**
 * Class UserIndexData
 * @package app\modules\admin\data\user
 */
class UserIndexData extends AbstractData
{
    /**
     * @var array
     */
    public $id = [];

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['id', 'each', 'rule' => ['integer']]
        ]);
    }
}