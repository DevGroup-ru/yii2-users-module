<?php
/** @var \yii\web\View $this */
/** @var \DevGroup\Users\models\RegistrationForm $model */
/** @var \yii\bootstrap\ActiveForm $form */
use yii\helpers\Html;

$registrationChildView = \DevGroup\Users\UsersModule::module()->authorizationScenario()->registrationFormPartialView();
?>
<h1><?= Yii::t('users', 'Registration') ?></h1>
<div class="registration-form__authorization-pair">
  <?=
      $this->render(
          $registrationChildView,
          [
              'model' => $model,
              'form' => $form,
          ]
      )
  ?>
</div>
<?php
if (\DevGroup\Users\UsersModule::module()->enableSocialNetworks === true):
?>
<div class="registration-form__social-networks">
  <?= \yii\authclient\widgets\AuthChoice::widget() ?>
</div>
<?php
endif;
?>
<div class="form-group">
    <div class="col-sm-6 col-sm-offset-3">
        <?= Html::submitButton(
            (
            Yii::t('users', 'Register')
            ),
            [
                'class' => 'btn registration-form__register-button',
            ]
        ); ?>

    </div>
</div>
