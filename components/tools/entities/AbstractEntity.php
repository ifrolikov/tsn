<?php

namespace app\components\tools\entities;

use yii\base\Model;

class AbstractEntity extends Model
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            [$this->attributes(), 'safe']
        ]);
    }
}