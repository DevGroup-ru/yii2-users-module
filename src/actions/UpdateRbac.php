<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use DevGroup\Users\models\AuthItemForm;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii\rbac\Item;

/**
 * Class UpdateRbac
 * @package DevGroup\Users\actions
 */
class UpdateRbac extends BaseAdminAction
{

    /**
     * @var string
     */
    public $viewFile = 'update';

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @param $type
     * @param array $returnUrl
     * @return string|Yii\web\Response
     * @throws \InvalidArgumentException
     */
    public function run($id, $type, $returnUrl = ['/users/rbac/index'])
    {
        $rules = ArrayHelper::map(Yii::$app->getAuthManager()->getRules(), 'name', 'name');
        $model = new AuthItemForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $item = $model->updateItem();
            if (strlen($model->getErrorMessage()) > 0) {
                Yii::$app->getSession()->setFlash('error', $model->getErrorMessage());
                return $this->controller->redirect(['/users/rbac/update', 'id' => $item->name, 'type' => $item->type]);
            } else {
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'next':
                        return $this->controller->redirect(
                            [
                                '/users/rbac/create',
                                'type' => $type,
                                'returnUrl' => $returnUrl,
                            ]
                        );
                    case 'back':
                        return $this->controller->redirect($returnUrl);
                    default:
                        return $this->controller->redirect(
                            Url::toRoute(
                                [
                                    '/users/rbac/update',
                                    'id' => $item->name,
                                    'type' => $type,
                                    'returnUrl' => $returnUrl,
                                ]
                            )
                        );
                }
            }
        } else {
            switch ($type) {
                case Item::TYPE_PERMISSION:
                    $item = Yii::$app->getAuthManager()->getPermission($id);
                    $items = ArrayHelper::map(
                        Yii::$app->getAuthManager()->getPermissions(),
                        'name',
                        function ($item) {
                            return $item->name .
                            (strlen($item->description) > 0 ? ' [' . $item->description . ']' : '');
                        }
                    );
                    break;
                case Item::TYPE_ROLE:
                    $item = Yii::$app->getAuthManager()->getRole($id);
                    $items = ArrayHelper::map(
                        ArrayHelper::merge(
                            Yii::$app->getAuthManager()->getPermissions(),
                            Yii::$app->getAuthManager()->getRoles()
                        ),
                        'name',
                        function ($item) {
                            return $item->name .
                            (strlen($item->description) > 0 ? ' [' . $item->description . ']' : '');
                        },
                        function ($item) {
                            return Item::TYPE_ROLE;
                        }
                    );
                    break;
                default:
                    throw new \InvalidArgumentException('Unexpected item type');
            }
            $children = Yii::$app->getAuthManager()->getChildren($id);
            $selected = [];
            foreach ($children as $child) {
                $selected[] = $child->name;
            }
            $model->name = $item->name;
            $model->oldname = $item->name;
            $model->type = $item->type;
            $model->description = $item->description;
            $model->ruleName = $item->ruleName;
            return $this->render(
                [
                    'model' => $model,
                    'rules' => $rules,
                    'children' => $selected,
                    'items' => $items,
                ]
            );
        }
    }
}