<?php
/** @var \yii\web\View $this */
/** @var \DevGroup\Users\models\RegistrationForm $model */
/** @var array $formOptions */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$registrationChildView = \DevGroup\Users\UsersModule::module()->authorizationScenario()->registrationFormPartialView();
?>
<h1><?= Yii::t('users', 'Registration') ?></h1>
<?php $form = ActiveForm::begin($formOptions); ?>
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
  <div class="m-form__line">
    <div class="m-form__col">
        <?=
            Html::submitButton(
                (
                Yii::t('users', 'Register')
                ),
                [
                    'class' => 'btn-blue g--btn-full registration-form__register-button',
                ]
            );
        ?>
    </div>
  </div>
</div>
<?php
if (\DevGroup\Users\UsersModule::module()->enableSocialNetworks === true) :
?>
<div class="registration-form__social-networks">
  <div class="m-form__line">
    <div class="m-form__col">
      <div class="title-soc-login"><?= Yii::t('users', 'Register using social network')?>:</div>
    </div>
  </div>
  <div class="m-form__line">
    <div class="m-form__col">
        <?=
          \DevGroup\Users\widgets\SocialNetworksWidget::widget([
              'baseAuthUrl' => ['@social', 'returnUrl' => \DevGroup\Frontend\RedirectHelper::getReturnUrl()],
          ])
        ?>
    </div>
  </div>
</div>
<?php
endif;
?>

<?php ActiveForm::end(); ?>