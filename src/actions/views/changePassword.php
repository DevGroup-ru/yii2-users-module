<?php
use DevGroup\Users\widgets\UserChangePasswordFormWidget;

?>
    <h1><?= Yii::t('users', 'Change Password') ?></h1>

<?= UserChangePasswordFormWidget::widget([
    'model' => $model
]) ?>