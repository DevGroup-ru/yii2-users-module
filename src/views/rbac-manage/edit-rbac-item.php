<?php

/**
 * @var yii\web\View $this
 * @var AuthItemForm $model
 * @var array $items
 * @var array $children
 * @var string $permName
 */

use DevGroup\Users\models\AuthItemForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\rbac\Item;

$isNewRecord = isset($isNewRecord) && $isNewRecord;
$modelName = ($model->type == Item::TYPE_ROLE) ? Yii::t('users', 'Role') : Yii::t('users', 'Permission');
$this->title = $model->isNewRecord ? Yii::t('users', 'Create') : Yii::t('users', 'Update');
$this->title .= ' ' . $modelName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('users', 'Rbac'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <section id="edit-rbac-item">
        <div class="col-xs-12">
            <div class="box">

                <?php $form = ActiveForm::begin(); ?>
                <div class="box-body">

                    <?= $form->field($model, 'oldname', ['template' => '{input}'])->input('hidden'); ?>
                    <?= $form->field($model, 'type', ['template' => '{input}'])->input('hidden'); ?>
                    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($model, 'children')->widget(Select2::class, [
                        'data' => $items,
                        'options' => [
                            'placeholder' => Yii::t('users', 'Select items'),
                            'multiple' => true
                        ],
                    ]) ?>
                </div>
                <div class="box-footer">
                    <div class="btn-group pull-right" role="group" aria-label="Edit buttons">
                        <?= Html::a(
                            Yii::t('users', 'Back'),
                            ['/users/rbac-manage/index', 'type' => $model->type],
                            ['class' => 'btn btn-danger']
                        ) ?>
                        <?php if (true === Yii::$app->user->can($permName)) : ?>
                            <?= Html::submitButton(Yii::t('users', 'Save'), ['class' => 'btn btn-primary']) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </section>
</div>