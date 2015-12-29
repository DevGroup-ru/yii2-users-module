<?php
/** @var \yii\web\View $this */
/** @var \DevGroup\Users\models\LoginForm $model */
/** @var array $formOptions */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$loginChildView = \DevGroup\Users\UsersModule::module()->authorizationScenario()->loginFormPartialView();
?>
    <h1><?= Yii::t('users', 'Login to site') ?></h1>
<?php $form = ActiveForm::begin($formOptions); ?>
    <div class="login-form__authorization-pair">
        <?=
        $this->render(
            $loginChildView,
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
    <div class="login-form__social-networks">
        <?= \yii\authclient\widgets\AuthChoice::widget([
            'baseAuthUrl' => ['/users/auth/social']
        ]) ?>
    </div>
    <?php
endif;
?>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <?= Html::submitButton(
                (
                Yii::t('users', 'Login')
                ),
                [
                    'class' => 'btn login-form__login-button',
                ]
            ); ?>

        </div>
    </div>
<?php ActiveForm::end(); ?>