<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $second_name
 * @property string $father_name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $created_at
 * @property string $deleted_at
 *
 * @property Token[] $tokens
 * @property UserApartment[] $userApartments
 * @property Apartment[] $apartments
 * @property UserRole[] $userRoles
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'email', 'password'], 'required'],
            [['created_at', 'deleted_at'], 'safe'],
            [['first_name', 'second_name', 'father_name', 'email', 'phone', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'second_name' => 'Second Name',
            'father_name' => 'Father Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserApartments()
    {
        return $this->hasMany(UserApartment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApartments()
    {
        return $this->hasMany(Apartment::className(), ['id' => 'apartment_id'])->viaTable('user_apartment', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::className(), ['user_id' => 'id']);
    }
}
