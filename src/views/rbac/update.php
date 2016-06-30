<?php

/**
 * @var yii\web\View $this
 * @var AuthItemForm $model
 * @var array $items
 * @var array $children
 */

use DevGroup\Users\models\AuthItemForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$isNewRecord = isset($isNewRecord) && $isNewRecord;
$modelName = ($model->type == 1) ? Yii::t('users', 'Role') : Yii::t('users', 'Permission');
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
                            Yii::$app->request->get('returnUrl', ['index']),
                            ['class' => 'btn btn-danger']
                        ) ?>
                        <?= Html::submitButton(Yii::t('users', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </section>
</div>