<?php
/**
 * @var User $user
 * @var \yii\web\View $this
 */
use DevGroup\Users\models\User;
use yii\helpers\Url;
?>

<p><?= Yii::t('users', 'Hello'); ?>, <?= $user->username ?>!</p>
<p>
    <?= Yii::t(
        'users',
        '<a href="{link}">This</a> is link for reset your password. If you did not request it ignore this letter.',
        [
            'link' => Url::toRoute(
                [
                    '@reset-password',
                    'token' => $user->password_reset_token
                ],
                true
            )
        ]
    ); ?>
</p>