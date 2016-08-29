<?php
/**
 * @var \DevGroup\Users\models\BackendCreateForm $model
 * @var array $roles
 * @var array $currentRoles
 * @var \DevGroup\Users\models\User $user
 * @var bool $canSave
 */


use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\DetailView;
use \DevGroup\Users\UsersModule;

$this->title = $user->isNewRecord ?
    Yii::t('users', 'Create new user') :
    Yii::t('users', 'Edit user "{username}"', ['username' => $user->username]);

$this->params['breadcrumbs'][] = [
    'url' => ['/users/users-manage/index'],
    'label' => Yii::t('users', 'Edit users')
];

$this->params['breadcrumbs'][] = $this->title;
$module = UsersModule::module();
$webUser = Yii::$app->user;
?>
<div class="row">
    <section id="edit-user">
        <div class="col-xs-12">
            <div class="box">
                <?php $form = ActiveForm::begin(); ?>
                <div class="box-body">
                    <?= $form->field($model, 'username') ?>
                    <?= $form->field($model, 'email') ?>
                    <?= $form->field($model, 'phone') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'confirmPassword')->passwordInput() ?>
                    <?= $form->field($model, 'password_is_temporary')->checkbox() ?>
                    <?= $form->field($model, 'username_is_temporary')->checkbox() ?>
                    <?php if (true === $webUser->can('users-user-activate')) : ?>
                        <?= $form->field($model, 'is_active')->checkbox() ?>
                    <?php endif; ?>
                    <?php if (true === $webUser->can('users-role-assign')) : ?>
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
                    <?php endif; ?>
                </div>
                <div class="box-footer">
                    <div class="btn-group pull-right" role="group" aria-label="Edit buttons">
                        <?= Html::a(
                            Yii::t('users', 'Back'),
                            Yii::$app->request->get('returnUrl', ['index']),
                            ['class' => 'btn btn-danger']
                        ) ?>
                        <?php if (true === $canSave) : ?>
                            <?= Html::submitButton(Yii::t('users', 'Save'), ['class' => 'btn btn-primary']) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $form->end(); ?>
            </div>
        </div>
        <?php if ($module->logLastLoginTime === true) : ?>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?= DetailView::widget([
                            'model' => $user,
                            'attributes' => [
                                'last_login_at:datetime',
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($module->logLastLoginData === true && empty($user->login_data) === false) : ?>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?= DetailView::widget([
                            'model' => $user->login_data,
                            'attributes' => array_keys($user->login_data)
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>
