<?php
/** @var \yii\web\View $this */
/** @var array $formOptions */
/** @var \DevGroup\Users\models\User $user */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<div class="user-profile-form">
<?php
$form = ActiveForm::begin($formOptions);

$returnUrl = \DevGroup\Frontend\RedirectHelper::getReturnUrl();
echo Html::hiddenInput('returnUrl', $returnUrl);


$usernameOptions = [
    'class' => 'user-profile-form__username'
];
if ($user->username_is_temporary) {
    $usernameOptions['class'] .= ' input-edit';
    echo $form->field($user, 'username', ['options' => $usernameOptions]);
} else {
    $usernameOptions['class'] .= ' input-stat';
    echo Html::tag('label', $user->getAttributeLabel('username'));
    echo Html::tag(
        'div',
        $user->username,
        $usernameOptions
    );
}

$module = \DevGroup\Users\UsersModule::module();
$attributes = [
    'email' => true,
    'phone' => false,
];
foreach ($attributes as $attribute => $changeOnlyOnce) {
    if (in_array($attributes, $module->disabledUserAttributes) === false) {
        echo '<div class="m-form__line"><div class="m-form__col">';
        $options = [
            'class' => "user-profile-form__$attribute",
        ];
        if (empty($user->$attribute) || $changeOnlyOnce === false) {
            $options['class'] .= ' input-edit';
            echo $form->field($user, $attribute, ['options' => $options]);
        } else {
            $options['class'] .= ' input-stat';
            echo Html::tag('label', $user->getAttributeLabel($attribute));
            echo Html::tag(
                'div',
                $user->$attribute,
                $options
            );
        }
        echo "</div></div>";
    }
}
?>
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

<?php
ActiveForm::end()
?>
</div>
