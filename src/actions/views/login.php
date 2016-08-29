<?php
/** @var \yii\web\View $this */
/** @var \DevGroup\Users\models\LoginForm $model */
/** @var array $formOptions */
?>
    <h1><?= Yii::t('users', 'Login to site') ?></h1>
<?= \DevGroup\Users\widgets\LoginFormWidget::widget([
    'model' => $model,
    'formOptions' => $formOptions,
]) ?>