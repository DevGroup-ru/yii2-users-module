<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\User;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * Class ListUsers
 *
 * @package DevGroup\Users\actions
 */
class BackendListUsers extends BaseAdminAction
{
    /**
     * @var string
     */
    public $viewFile = 'list-users';

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        /** @var $model User */
        $model = Yii::createObject(ModelMapHelper::User());
        $dataProvider = new ActiveDataProvider([
            'query' => $model::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render(['dataProvider' => $dataProvider]);
    }
}
