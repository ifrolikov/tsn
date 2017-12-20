<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_apartment".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $apartment_id
 *
 * @property Apartment $apartment
 * @property User $user
 */
class UserApartment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_apartment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'apartment_id'], 'required'],
            [['user_id', 'apartment_id'], 'integer'],
            [['user_id', 'apartment_id'], 'unique', 'targetAttribute' => ['user_id', 'apartment_id'], 'message' => 'The combination of User ID and Apartment ID has already been taken.'],
            [['apartment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Apartment::className(), 'targetAttribute' => ['apartment_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'apartment_id' => 'Apartment ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApartment()
    {
        return $this->hasOne(Apartment::className(), ['id' => 'apartment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
