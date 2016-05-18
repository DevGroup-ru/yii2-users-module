<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use DevGroup\Users\models\AuthItemForm;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\helpers\Url;
use Yii;

/**
 * Class CreateRbac
 * @package DevGroup\Users\actions
 */
class CreateRbac extends BaseAdminAction
{

    /**
     * @var string
     */
    public $viewFile = 'update';

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $type
     * @return string|\yii\web\Response
     * @throws \InvalidArgumentException
     */
    public function run($type)
    {
        $rules = ArrayHelper::map(\Yii::$app->getAuthManager()->getRules(), 'name', 'name');
        $model = new AuthItemForm(['isNewRecord' => true]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $item = $model->createItem();
            if (strlen($model->getErrorMessage()) > 0) {
                \Yii::$app->getSession()->setFlash('error', $model->getErrorMessage());
                return $this->controller->redirect(['update', 'id' => $item->name, 'type' => $item->type]);
            } else {
                Yii::$app->session->setFlash('success', Yii::t('users', 'Record has been saved'));
                $returnUrl = Yii::$app->request->get('returnUrl', ['/users/rbac/index']);
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
                    $model->type = Item::TYPE_PERMISSION;
                    $items = ArrayHelper::map(
                        \Yii::$app->getAuthManager()->getPermissions(),
                        'name',
                        function ($item) {
                            return $item->name .
                            (strlen($item->description) > 0 ? ' [' . $item->description . ']' : '');
                        }
                    );
                    break;
                case Item::TYPE_ROLE:
                    $model->type = Item::TYPE_ROLE;
                    $items = ArrayHelper::map(
                        ArrayHelper::merge(
                            \Yii::$app->getAuthManager()->getPermissions(),
                            \Yii::$app->getAuthManager()->getRoles()
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
            return $this->render(
                [
                    'model' => $model,
                    'rules' => $rules,
                    'items' => $items,
                    'children' => [],
                    'isNewRecord' => true,
                ]
            );
        }
    }

}