<?php

namespace DevGroup\Users\handlers;

use yii\base\Event;
use Yii;

class EmailHandler
{
    public static function sendMailAfterResetPassword(Event $event)
    {

        if (Yii::$app->has('mailer') && Yii::$app->mailer instanceof \yii\mail\MailerInterface) {
            Yii::$app->mailer->compose(
                '@vendor/devgroup/yii2-users-module/src/views/mails/reset-password',
                [
                    'user' => $event->sender->getUser()
                ]
            )->setTo($event->sender->getUser()->email)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject(Yii::t('users', 'Reset password'))
                ->send();
        }
    }
}