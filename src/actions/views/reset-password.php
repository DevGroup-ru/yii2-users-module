<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="user-reset-password-form">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'class' => 'reset-form m-form',
        ]
    ]);
    ?>

    <div class="m-form__line">
        <?= $form->field($model, 'email'); ?>
    </div>

    <div class="m-form__line">
        <div class="m-form__col">
            <?= Html::submitButton(
                Yii::t('users', 'Reset Password'),
                [
                    'class' => 'btn-blue g--btn-full profile-form__save-button',
                ]
            ); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
