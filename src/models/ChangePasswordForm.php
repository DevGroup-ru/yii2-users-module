<?php

namespace DevGroup\Users\models;

use DevGroup\Users\helpers\PasswordHelper;
use DevGroup\Users\UsersModule;
use yii\base\DynamicModel;
use Yii;

/**
 * Class ChangePasswordForm
 * @package DevGroup\Users\models
 */
class ChangePasswordForm extends DynamicModel
{

    /**
     * @var string
     */
    public $oldPassword;

    /**
     * @var string
     */
    public $newPassword;

    /**
     * @var string
     */
    public $confirmPassword;


    /***
     * @return array
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'confirmPassword'], 'required'],
            [
                ['newPassword', 'confirmPassword'],
                'string',
                'min' => UsersModule::module()->authorizationScenario()->minPasswordLength
            ],
            [['confirmPassword'], 'compare', 'compareAttribute' => 'newPassword']
        ];
    }


    public function changePassword()
    {

        if ($this->validate()) {

            /** @var User $user */
            $user = Yii::$app->user->identity;
            if ($user === null) {
                throw new ServerErrorHttpException("No user identity found");
            }

            if (PasswordHelper::validate($this->oldPassword, $user->password_hash) !== true) {
                $this->addError('oldPassword', Yii::t('users', 'Old Password not valid'));
                return false;
            } else {
                return $user->changePassword($this->newPassword);
            }


        }


        return false;

    }


    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'oldPassword' => Yii::t('users', 'Old Password'),
            'newPassword' => Yii::t('users', 'New Password'),
            'confirmPassword' => Yii::t('users', 'Confirm Password'),
        ];
    }

}