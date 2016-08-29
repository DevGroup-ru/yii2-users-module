<?php

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $provider
 * @var integer $type
 * @var string $gridId
 * @var bool $isRules
 * @var string $editPermissionName
 * @var string $deletePermissionName
 */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use yii\rbac\Item;

$content = $this->render('_rbacGrid', [
    'data' => $provider,
    'isRules' => $isRules,
    'id' => $gridId,
    'type' => $type
]);
$rolesTab = $permissionsTab = [];
$button = $title = '';
switch ($type) {
    case Item::TYPE_ROLE :
        $rolesTab = [
            'label' => Yii::t('users', 'Roles'),
            'content' => $content,
            'active' => true,
        ];
        $permissionsTab = [
            'label' => Yii::t('users', 'Permissions'),
            'url' => Url::to(['/users/rbac-manage/index', 'type' => Item::TYPE_PERMISSION]),
        ];
        $button = Html::a(
            Yii::t('users', 'Create Role'),
            ['edit', 'type' => \yii\rbac\Item::TYPE_ROLE],
            ['class' => 'btn btn-success']
        );
        $title = Yii::t('users', 'Manage Roles');
        break;
    case Item::TYPE_PERMISSION :
        $rolesTab = [
            'label' => Yii::t('users', 'Roles'),
            'url' => Url::to(['/users/rbac-manage/index', 'type' => Item::TYPE_ROLE]),
        ];
        $permissionsTab = [
            'label' => Yii::t('users', 'Permissions'),
            'content' => $content,
            'active' => true
        ];
        $button = Html::a(
            Yii::t('users', 'Create Permission'),
            ['edit', 'type' => Item::TYPE_PERMISSION],
            ['class' => 'btn btn-success']
        );
        $title = Yii::t('users', 'Manage Permissions');
        break;
}
$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
    <section id="rbac-list">
        <div class="user-index nav-tabs-custom">
            <?= Tabs::widget(['items' => [$rolesTab, $permissionsTab]]) ?>
            <div class="box-footer" role="toolbar">
                <div class="btn-group pull-right" role="group" aria-label="Edit buttons">
                    <?php if (true === Yii::$app->user->can($editPermissionName)) : ?>
                        <?= $button ?>
                    <?php endif; ?>
                    <?php if (true === Yii::$app->user->can($deletePermissionName)) : ?>
                        <?= Html::button(
                            Yii::t('users', 'Delete selected'),
                            ['class' => 'btn btn-danger', 'id' => 'deleteItems']
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php
$url = Url::to(['/users/rbac-manage/remove-items']);
$js = <<<JS
    "use strict";

    $('#deleteItems').on('click', function() {
        $.ajax({
            'url' : '$url',
            'type': 'post',
            'data': {
                'items' : $('.grid-view').yiiGridView('getSelectedRows'),
                'item-type' : '$type'
            },
            success: function(data) {
                location.reload();
            }
        });
    });
JS;
$this->registerJs($js);
