<?php

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $permissions
 * @var yii\data\ActiveDataProvider $roles
 * @var bool $isRules
 */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\rbac\Item;

$this->title = Yii::t('users', 'Rbac');
$this->params['breadcrumbs'][] = $this->title;

?>
            <div class="user-index nav-tabs-custom">
                <?= Tabs::widget([
                    'items' => [
                        [
                            'label' => Yii::t('users', 'Permissions'),
                            'content' => $this->render('_rbacGrid', [
                                'data' => $permissions,
                                'isRules' => $isRules,
                                'id' => 'operations',
                                'type' => Item::TYPE_PERMISSION
                            ]),
                            'active' => true,
                        ],
                        [
                            'label' => Yii::t('users', 'Roles'),
                            'content' => $this->render('_rbacGrid',
                                ['data' => $roles, 'isRules' => $isRules, 'id' => 'roles', 'type' => Item::TYPE_ROLE]),
                        ],
                    ],
                ]); ?>



                <div class="box-footer" role="toolbar">
                    <div class="btn-group">
                        <?=
                        Html::a(
                            Yii::t('users', 'Create Permission'),
                            ['create', 'type' => \yii\rbac\Item::TYPE_PERMISSION],
                            ['class' => 'btn btn-success']
                        )
                        ?>
                        <?=
                        Html::a(
                            Yii::t('users', 'Create Role'),
                            ['create', 'type' => \yii\rbac\Item::TYPE_ROLE],
                            ['class' => 'btn btn-success']
                        )
                        ?>
                    </div>
                    <?= Html::button(Yii::t('users', 'Delete selected'), ['class' => 'btn btn-danger', 'id' => 'deleteItems']); ?>
                </div>


            </div>





<?php

$js = <<<JS
    "use strict";

    $('#deleteItems').on('click', function() {
        $.ajax({
            'url' : '/users/rbac/remove-items',
            'type': 'post',
            'data': {
                'items' : $('.grid-view').yiiGridView('getSelectedRows')
            },
            success: function(data) {
                location.reload();
            }
        });
    });
JS;

$this->registerJs($js);

?>
