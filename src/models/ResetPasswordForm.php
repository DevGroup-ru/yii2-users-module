<?php
namespace DevGroup\Users\models;

use DevGroup\Users\UsersModule;
use yii\base\DynamicModel;
use Yii;

class ResetPasswordForm extends DynamicModel
{
    /**
     * @var string
     */
    public $newPassword;

    /***
     * @return array
     */
    public function rules()
    {
        return [
            [['newPassword'], 'required'],
            [
                ['newPassword'],
                'string',
                'min' => UsersModule::module()->authorizationScenario()->minPasswordLength
            ]
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'newPassword' => Yii::t('users', 'New Password'),
        ];
    }

}