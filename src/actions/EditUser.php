<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\User;
use DevGroup\Users\UsersModule;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rbac\Item;

class EditUser extends BaseAdminAction
{
    public $viewFile = 'edit-user';


    public function run($id = null, $returnUrl = null)
    {

        /***
         * @var $model User
         */
        $model = Yii::createObject(ModelMapHelper::User());
        $module = UsersModule::module();

        $assignmentModel = new DynamicModel(['assignments', 'allAssignments']);
        $assignmentModel->allAssignments = ArrayHelper::map(
            \Yii::$app->getAuthManager()->getRoles(),
            'name',
            function ($item) {
                return $item->name . (strlen($item->description) > 0
                    ? ' [' . $item->description . ']'
                    : '');
            }
        );

        $assignmentModel->addRule(
            'assignments',
            'each',
            [
                'rule' =>
                    [
                        'in',
                        'range' => array_keys($assignmentModel->allAssignments)
                    ]
            ]
        );

        $assignmentModel->assignments = Yii::$app->authManager->getAssignments($id);


        $assignments = [];

        $controllerUrl = '/' . $this->controller->getUniqueId();


        if ($id !== null) {
            /***
             * @var $model User
             */
            $model = $model::findOne($id);
        }


        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if ($assignmentModel->load(Yii::$app->request->post()) && $assignmentModel->validate()) {

                }

                $errors = [];
                foreach ($assignments as $assignment) {
                    $key = array_search($assignment->roleName, $postAssignments);
                    if ($key === false) {
                        Yii::$app->authManager->revoke(new Item(['name' => $assignment->roleName]), $model->id);
                    } else {
                        unset($postAssignments[$key]);
                    }
                }
                foreach ($postAssignments as $assignment) {
                    try {
                        Yii::$app->authManager->assign(new Item(['name' => $assignment]), $model->id);
                    } catch (\Exception $e) {
                        $errors[] = 'Cannot assign "' . $assignment . '" to user';
                    }
                }
                if ($model->isNewRecord === true) {
                    Yii::$app->getSession()->setFlash('error', $model->getErrorMessage());
                    return $this->controller->redirect([
                        $controllerUrl . '/edit',
                        'id' => $model->id,
                    ]);
                } else {
                    switch (Yii::$app->request->post('action', 'save')) {
                        case 'next':
                            return $this->controller->redirect(
                                [
                                    $controllerUrl . '/create',
                                    'returnUrl' => $returnUrl,
                                ]
                            );
                        case 'back':
                            return $this->controller->redirect($returnUrl);
                        default:
                            return $this->controller->redirect(
                                Url::toRoute(
                                    [
                                        $controllerUrl . '/edit',
                                        'id' => $model->id,
                                        'returnUrl' => $returnUrl,
                                    ]
                                )
                            );
                    }
                }
            }
        }

        return $this->render([
            'model' => $model,
            'module' => $module,
            'assignmentModel' => $assignmentModel,
        ]);
    }


}