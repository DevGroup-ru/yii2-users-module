<?php
/** @var \yii\web\View $this */
use DevGroup\Users\UsersModule;

/** @var \DevGroup\Users\models\RegistrationForm $model */
/** @var \yii\bootstrap\ActiveForm $form */
?>

<?php foreach (UsersModule::module()->requiredUserAttributes as $attribute): ?>
    <div class="m-form__line">
        <?= $form->field($model, $attribute); ?>
    </div>
<?php endforeach; ?>
<div class="m-form__line">
    <?= $form->field($model, 'password')->passwordInput() ?>
</div>
<div class="m-form__line">
    <?= $form->field($model, 'confirmPassword')->passwordInput() ?>
</div>