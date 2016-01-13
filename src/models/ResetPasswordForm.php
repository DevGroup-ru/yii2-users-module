<?php

namespace DevGroup\Users\models;

use yii\base\DynamicModel;
use Yii;

class ResetPasswordForm extends DynamicModel
{

    const EVENT_BEFORE_RESET_PASSWORD = 'event-before-reset-password';
    const EVENT_AFTER_RESET_PASSWORD = 'event-after-reset-password';

    public $email;
    protected $user;

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('users', 'E-Mail')
        ];
    }


    public function getUser()
    {
        return $this->user;
    }

    public function resetPassword()
    {
        /** @var User $user */
        $this->user = User::findOne(['email' => $this->email]);
        if ($this->user === null) {
            throw new ServerErrorHttpException("No user identity found");
        }

        $this->trigger(self::EVENT_BEFORE_RESET_PASSWORD);
        $this->user->generatePasswordResetToken();
        if ($this->user->save()) {
            $this->trigger(self::EVENT_AFTER_RESET_PASSWORD);
            return true;
        }

        return false;
    }

}