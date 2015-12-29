<?php
/** @var \yii\web\View $this */
/** @var array $formOptions */
/** @var \DevGroup\Users\models\User $user */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="user-profile-form">
<?php
$form = ActiveForm::begin($formOptions);

$returnUrl = \DevGroup\Frontend\RedirectHelper::getReturnUrl();
echo Html::hiddenInput('returnUrl', $returnUrl);


$usernameOptions = [
    'class' => 'form-group user-profile-form__username'
];
if ($user->username_is_temporary) {
    echo $form->field($user, 'username', ['options' => $usernameOptions]);
} else {
    echo Html::tag(
        'div',
        $user->username,
        $usernameOptions
    );
}

$module = \DevGroup\Users\UsersModule::module();
$attributes = [
    'email',
    'phone',
];
foreach ($attributes as $attribute) {
    if (in_array('email', $module->disabledUserAttributes) === false) {
        $options = [
            'class' => "form-group user-profile-form__$attribute",
        ];
        if (empty($user->$attribute)) {
            echo $form->field($user, $attribute, ['options' => $options]);
        } else {
            echo Html::tag(
                'div',
                $user->$attribute,
                $options
            );
        }
    }
}
?>
<div class="form-group">
    <div class="col-sm-6 col-sm-offset-3">
        <?= Html::submitButton(
            (
            Yii::t('users', 'Save')
            ),
            [
                'class' => 'btn registration-form__save-button',
            ]
        ); ?>

    </div>
</div>
<?php
ActiveForm::end()
?>
</div>
