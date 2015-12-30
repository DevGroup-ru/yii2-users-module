<?php
/** @var \yii\web\View $this */
/** @var \DevGroup\Users\models\User $user */
/** @var array $profileWidgetOptions */

?>
<?= \dmstr\widgets\Alert::widget() ?>
<div class="profile-update">
    <?=
    \DevGroup\Users\widgets\UserProfileFormWidget::widget($profileWidgetOptions)
    ?>
</div>