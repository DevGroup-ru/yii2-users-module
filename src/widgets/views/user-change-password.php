<?php
/** @var \yii\web\View $this */
/** @var array $formOptions */
/** @var \DevGroup\Users\models\User $model */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<div class="user-change-password-form">
    <?php
    $form = ActiveForm::begin($formOptions);
    ?>

    <div class="m-form__line">
        <?= $form->field($model, 'oldPassword')->passwordInput(); ?>
    </div>

    <div class="m-form__line">
        <?= $form->field($model, 'newPassword')->passwordInput();  ?>
    </div>

    <div class="m-form__line">
        <?= $form->field($model, 'confirmPassword')->passwordInput(); ?>
    </div>
    <div class="m-form__line">
        <div class="m-form__col">
            <?= Html::submitButton(
                (
                Yii::t('users', 'Save')
                ),
                [
                    'class' => 'btn-blue g--btn-full profile-form__save-button',
                ]
            ); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
