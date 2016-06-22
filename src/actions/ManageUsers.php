<?php

namespace DevGroup\Users\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\User;
use yii\data\ActiveDataProvider;
use Yii;

class ManageUsers extends BaseAdminAction
{

    public $viewFile = 'manage-users';

    /**
     * @return array
     */
    public function breadcrumbs()
    {
        return [
            [
                'label' => Yii::t('users', 'Manage users'),
            ]
        ];
    }

    /**
     * @return string
     */
    public function title()
    {
        return Yii::t('users', 'Manage users');
    }


    public function run()
    {
        /***
         * @var $model User
         */
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