<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "apartment".
 *
 * @property integer $id
 * @property integer $zip
 * @property string $address
 * @property string $bill
 * @property string $created_at
 * @property string $deleted_at
 *
 * @property UserApartment[] $userApartments
 * @property User[] $users
 */
class Apartment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apartment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['zip', 'address', 'bill'], 'required'],
            [['zip'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['address', 'bill'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zip' => 'Zip',
            'address' => 'Address',
            'bill' => 'Bill',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserApartments()
    {
        return $this->hasMany(UserApartment::className(), ['apartment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_apartment', ['apartment_id' => 'id']);
    }
}
