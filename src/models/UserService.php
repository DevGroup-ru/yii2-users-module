<?php

namespace DevGroup\Users\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class UserService represents base model for user social services bindings
 *
 * @package DevGroup\Users\models
 * @property integer $id
 * @property integer $user_id
 * @property integer $social_service_id
 * @property string  $service_id
 */
class UserService extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_service}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'social_service_id'], 'integer'],
            [['service_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'service_id' => Yii::t('users', 'User ID in social service'),
            'social_service_id' => Yii::t('users', 'Social service'),
            'user_id' => Yii::t('users', 'User ID'),
        ];
    }
}
