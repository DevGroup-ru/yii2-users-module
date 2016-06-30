<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use yii\base\InvalidParamException;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * Class DeleteAction
 * Using this action you can delete any of model records, that extends yii\db\ActiveRecord
 * Usage example:
 *  public function actions()
 *  {
 *      return [
 *       ...
 *          'delete' => [
 *              'class' => DeleteAction::class,
 *              'modelClass' => 'some\Class::class',
 *          ]
 *      ];
 *  }
 *
 * @package DevGroup\Users\actions
 */
class DeleteAction extends BaseAdminAction
{
    /** @var  ActiveRecord */
    public $modelClass;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (false === class_exists($this->modelClass)
            || false === is_subclass_of($this->modelClass, ActiveRecord::class)
        ) {
            throw new InvalidParamException(
                Yii::t('users', "'modelClass' must be a correct class name and must extend 'yii\\db\\ActiveRecord'!")
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function run($id, $returnUrl = '')
    {
        $class = $this->modelClass;
        /** @var ActiveRecord $model */
        if (null === $model = $class::findOne($id)) {
            throw new NotFoundHttpException(
                Yii::t('users', "{model} with id :'{id}' not found!", [
                    'model' => Yii::t('users', 'User'),
                    'id' => $id
                ])
            );
        }
        if (false !== $model->delete()) {
            Yii::$app->session->setFlash('info',
                Yii::t('users', '{model} successfully removed.', ['model' => Yii::t('users', 'User')])
            );
        } else {
            Yii::$app->session->setFlash('error',
                Yii::t('users', 'There was an errors: {errors}, while deleting {model}.', [
                    'errors' => implode(', ', $model->errors),
                    'model' => Yii::t('users', 'User')
                ])
            );
        }
        return $this->controller->redirect($returnUrl);
    }
}