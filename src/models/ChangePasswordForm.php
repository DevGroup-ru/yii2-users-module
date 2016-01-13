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

    const EVENT_BEFORE_PASSWORD_CHANGE = 'event_before_passwod_change';
    const EVENT_PASSWORD_CHANGE = 'event_password_change';

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

        /** @var User $user */
        $this->trigger(self::EVENT_BEFORE_PASSWORD_CHANGE);
        $user = Yii::$app->user->identity;
        if ($user === null) {
            throw new ServerErrorHttpException("No user identity found");
        }
        if (PasswordHelper::validate($this->oldPassword, $user->password_hash) !== true) {
            $this->addError('oldPassword', Yii::t('users', 'Old Password not valid'));
            return false;
        } else {
            $this->trigger(self::EVENT_PASSWORD_CHANGE);
            $user->password = $this->newPassword;
            return $user->changePassword();
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