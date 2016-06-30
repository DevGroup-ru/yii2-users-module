<?php

use DevGroup\AdminUtils\columns\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;

/**
 * @var $id string
 * @var $data \yii\data\ArrayDataProvider
 * @var $isRules bool
 * @var $type int
 */
?>


<?= GridView::widget([
    'id' => $id,
    'dataProvider' => $data,
    'layout' => "{items}\n<div class='row'>{summary}\n{pager}</div>",
    'summaryOptions' => ['class' => 'summary col-md-12 dataTables_info'],
    'columns' => [
        [
            'class' => CheckboxColumn::className(),
            'options' => [
                'width' => '10px',
            ],
        ],
        [
            'attribute' => 'name',
            'options' => [
                'width' => '30%',
            ],
        ],
        'description',
        [
            'attribute' => 'ruleName',
            'visible' => $isRules,
        ],
        [
            'attribute' => 'createdAt',
            'value' => function ($data) {
                return date("Y-m-d H:i:s", $data->createdAt);
            },
            'options' => [
                'width' => '200px',
            ],
        ],
        [
            'attribute' => 'updatedAt',
            'value' => function ($data) {
                return date("Y-m-d H:i:s", $data->updatedAt);
            },
            'options' => [
                'width' => '200px',
            ],
        ],
        [
            'class' => ActionColumn::class,
            'options' => [
                'width' => '95px',
            ],
            'buttons' => [
                [
                    'url' => 'update',
                    'icon' => 'pencil',
                    'class' => 'btn-primary',
                    'label' => Yii::t('users', 'Edit'),
                ],
                [
                    'url' => 'delete',
                    'icon' => 'trash-o',
                    'class' => 'btn-danger',
                    'label' => Yii::t('users', 'Delete'),
                ],
            ],
            'appendUrlParams' => [
                'type' => $type
            ],
        ],
    ],
    'tableOptions' => [
        'class' => 'table table-bordered table-hover dataTable',
    ]
]); ?>

