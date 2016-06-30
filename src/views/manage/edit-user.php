<?php
/**
 * @var User $model
 * @var UsersModule $module
 * @var array $roles
 * @var array $currentRoles
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
    'url' => ['/users/manage/index'],
    'label' => Yii::t('users', 'Edit users')
];

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <section id="edit-user">
        <div class="col-xs-12">
            <div class="box">
                <?php $form = ActiveForm::begin(); ?>
                <div class="box-body">
                    <?= $form->field($model, 'username'); ?>
                    <?= $form->field($model, 'email'); ?>
                    <?= $form->field($model, 'phone'); ?>
                    <?= $form->field($model, 'password'); ?>
                    <?= $form->field($model, 'is_active')->checkbox(); ?>
                    <?= $form->field($model, 'username_is_temporary')->checkbox(); ?>
                    <?= Select2::widget([
                        'name' => 'assignments',
                        'options' => [
                            'placeholder' => Yii::t('users', 'Select items'),
                            'multiple' => true
                        ],
                        'data' => $roles,
                        'value' => $currentRoles,
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]) ?>
                </div>
                <div class="box-footer">
                    <div class="btn-group pull-right" role="group" aria-label="Edit buttons">
                        <?= Html::a(
                            Yii::t('users', 'Back'),
                            Yii::$app->request->get('returnUrl', ['index']),
                            ['class' => 'btn btn-danger']
                        ) ?>
                        <?= Html::submitButton(Yii::t('users', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php $form->end(); ?>
            </div>
        </div>
        <?php if ($module->logLastLoginTime === true): ?>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'last_login_at:datetime',
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($module->logLastLoginData === true && empty($model->login_data) === false): ?>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?= DetailView::widget([
                            'model' => $model->login_data,
                            'attributes' => array_keys($model->login_data)
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

