<?php
/**
 * @var DevGroup\Users\models\UserModuleConfiguration $model
 * @var array $configurable
 * @var \yii\bootstrap\ActiveForm $form
 * @codeCoverageIgnore
 */
?>
<?= $form->field($model, 'emailConfirmationNeeded')->checkbox(); ?>
<?= $form->field($model, 'allowLoginInactiveAccounts')->checkbox(); ?>
<?= $form->field($model, 'enableSocialNetworks')->checkbox(); ?>
<?= $form->field($model, 'logLastLoginTime')->checkbox(); ?>
<?= $form->field($model, 'logLastLoginData')->checkbox(); ?>

<?= $form->field($model, 'passwordResetTokenExpire'); ?>
<?= $form->field($model, 'loginDuration'); ?>
<?= $form->field($model, 'generatedPasswordLength');
