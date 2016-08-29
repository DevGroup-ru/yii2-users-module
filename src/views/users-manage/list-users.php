<?php
/**
 * @var $dataProvider ActiveDataProvider
 */
use DevGroup\AdminUtils\columns\ActionColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\bootstrap\Html;
use kartik\icons\Icon;

$this->title = Yii::t('users', 'Users list');
$this->params['breadcrumbs'][] = $this->title;
$buttons = Html::a(
    Icon::show('plus') . '&nbsp'
    . Yii::t('users', 'New user'),
    ['/users/users-manage/edit', 'returnUrl' => \DevGroup\AdminUtils\Helper::returnUrl()],
    ['class' => 'btn btn-success']
);
$gridTpl = <<<TPL
<div class="box-body">
    {summary}
    {items}
</div>
<div class="box-footer">
    <div class="row list-bottom">
        <div class="col-sm-5">
            {pager}
        </div>
        <div class="col-sm-7">
            <div class="btn-group pull-right" style="margin: 20px 0;">
                $buttons
            </div>
        </div>
    </div>
</div>
TPL;
?>
<section id="list-users">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => $gridTpl,
                    'summaryOptions' => ['class' => 'summary col-md-12 dataTables_info'],
                    'tableOptions' => [
                        'class' => 'table table-bordered table-hover table-responsive dataTable',
                    ],
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
                ]) ?>
            </div>
        </div>
    </div>
</section>
