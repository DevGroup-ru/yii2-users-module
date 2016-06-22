<?php
/**
 * @var $model User
 * @var $module UsersModule
 * @var $assignments array
 */

use DevGroup\Users\models\User;
use DevGroup\Users\UsersModule;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

$this->title = $model->isNewRecord ?
    Yii::t('users', 'Create new user') :
    Yii::t('users', 'Edit user "{username}"', ['username' => $model->username]);

$this->params['breadcrumbs'][] = [
    'url' => ['/users/backend/index'],
    'label' => Yii::t('users', 'Edit users')
];

$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'username'); ?>
<?= $form->field($model, 'email'); ?>
<?= $form->field($model, 'phone'); ?>
<?= $form->field($model, 'password'); ?>
<?= $form->field($model, 'is_active')->checkbox(); ?>
<?= $form->field($model, 'username_is_temporary')->checkbox(); ?>
<?= $form->field($assignmentModel, 'assignments')->widget(Select2::class, [
    'data' => $assignmentModel->allAssignments,
    'options' => [
        'multiple' => true
    ],
]); ?>


<div class="form-group no-margin box-footer">
    <?=
    Html::a(
        Yii::t('users', 'Back'),
        Yii::$app->request->get('returnUrl', ['index']),
        ['class' => 'btn btn-danger']
    )
    ?>
    <?php if ($model->isNewRecord): ?>
        <?=
        Html::submitButton(
            Yii::t('users', 'Save & Go next'),
            [
                'class' => 'btn btn-success',
                'name' => 'action',
                'value' => 'next',
            ]
        )
        ?>
    <?php endif; ?>
    <?= Html::submitButton(
        Yii::t('users', 'Save & Go back'),
        [
            'class' => 'btn btn-warning',
            'name' => 'action',
            'value' => 'back',
        ]
    ); ?>
    <?=
    Html::submitButton(
        Yii::t('users', 'Save'),
        [
            'class' => 'btn btn-primary',
            'name' => 'action',
            'value' => 'save',
        ]
    )
    ?>


</div>


<?php $form->end(); ?>

<?php if ($module->logLastLoginTime === true): ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'last_login_at:datetime',
        ]
    ]) ?>
<?php endif; ?>


<?php if ($module->logLastLoginData === true && empty($model->login_data) === false): ?>
    <?= DetailView::widget([
        'model' => $model->login_data,
        'attributes' => array_keys($model->login_data)
    ]) ?>
<?php endif; ?>

