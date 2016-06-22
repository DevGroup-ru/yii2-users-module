<?php
/**
 * @var $dataProvider ActiveDataProvider
 */
use DevGroup\AdminUtils\columns\ActionColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$this->title = Yii::t('users', 'Edit users');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n<div class='row'>{summary}\n{pager}</div>",
    'summaryOptions' => ['class' => 'summary col-md-12 dataTables_info'],
    'columns' => [
        'id',
        'username',
        'email',
        'phone',
        'created_at:datetime',
        'updated_at:datetime',
        'activated_at:datetime',
        'last_login_at:datetime',
        [
            'class' => ActionColumn::class,
            'options' => [
                'width' => '95px',
            ],

        ],
    ],
    'tableOptions' => [
        'class' => 'table table-bordered table-hover dataTable',
    ]
]); ?>