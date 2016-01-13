<?php
use DevGroup\Users\models\ChangePasswordForm;
use DevGroup\Users\widgets\UserChangePasswordFormWidget;
/** @var ChangePasswordForm $model */
?>
    <h1><?= Yii::t('users', 'Change Password') ?></h1>

<?= UserChangePasswordFormWidget::widget([
    'model' => $model
]); ?>