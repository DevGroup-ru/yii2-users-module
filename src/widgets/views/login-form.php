<?php
/** @var \yii\web\View $this */
/** @var \DevGroup\Users\models\LoginForm $model */
/** @var array $formOptions */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use DevGroup\Frontend\RedirectHelper;

$loginChildView = \DevGroup\Users\UsersModule::module()->authorizationScenario()->loginFormPartialView();
?>
<?php $form = ActiveForm::begin($formOptions); ?>
<?= Html::hiddenInput('returnUrl', RedirectHelper::getReturnUrl()) ?>
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
        <div class="m-form__line">
            <div class="m-form__col">
                <?=
                Html::submitButton(
                    (
                    Yii::t('users', 'Login')
                    ),
                    [
                        'class' => 'btn-blue g--btn-full login-form__login-button',
                    ]
                );
                ?>
            </div>
            <div class="m-form__col">
              <?=
              Html::a(
                  Yii::t('users', 'Register'),
                  ['@registration'],
                  [
                      'class' => 'btn-brd-blue g--btn-full registration-form__register-button',
                  ]
              );
              ?>
            </div>
            <div class="m-form__col">
                <?=
                Html::a(
                    Yii::t('users', 'Reset password'),
                    ['@reset-password']
                );
                ?>
            </div>
        </div>


    </div>

<?php
if (\DevGroup\Users\UsersModule::module()->enableSocialNetworks === true):
    ?>
    <div class="login-form__social-networks">
        <div class="m-form__line">
            <div class="m-form__col">
                <div class="title-soc-login"><?= Yii::t('users', 'Login using social network')?>:</div>
            </div>
        </div>
        <div class="m-form__line">
            <div class="m-form__col">
                <?= \DevGroup\Users\widgets\SocialNetworksWidget::widget([
                    'baseAuthUrl' => ['@social', 'returnUrl' => RedirectHelper::getReturnUrl()],
                ]) ?>
            </div>
        </div>
    </div>
    <?php
endif;
?>

<?php ActiveForm::end(); ?>