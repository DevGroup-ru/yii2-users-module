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

$model->children = $children;
$isNewRecord = isset($isNewRecord) && $isNewRecord;
$this->title = $model->isNewRecord ? Yii::t('users', 'Create') : Yii::t('users', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('users', 'Rbac'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 box">
    <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'oldname', ['template' => '{input}'])->input('hidden'); ?>
            <?= $form->field($model, 'type', ['template' => '{input}'])->input('hidden'); ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>
            <?= (!empty($rules)) ? $form->field($model, 'ruleName')->dropDownList($rules, ['prompt' => 'Choose rule']) : '' ?>
            <?= $form->field($model, 'children')->widget(Select2::class, [
                'data' => $items,
                'options' => [
                    'placeholder' => 'Select provinces ...',
                    'multiple' => true
                ],
            ]) ?>
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

    <?php ActiveForm::end(); ?>
</div>
