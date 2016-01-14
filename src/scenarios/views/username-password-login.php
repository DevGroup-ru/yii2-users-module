<?php
/** @var \yii\web\View $this */
/** @var \DevGroup\Users\models\RegistrationForm $model */
/** @var \yii\widgets\ActiveForm $form */
?>

<div class="m-form__line">
    <?= $form->field($model, 'username') ?>
</div>
<div class="m-form__line">
    <?= $form->field($model, 'password')->passwordInput() ?>
</div>
<div class="m-form__line">
    <?= $form->field($model, 'rememberMe')->checkbox() ?>
</div>

