<?php

namespace DevGroup\Users\actions;

use Yii;
use yii\base\Action;

abstract class BaseAction extends Action
{
    /**
     * @return array
     */
    abstract public function breadcrumbs();

    /**
     * @return string
     */
    abstract public function title();

    /** @inheritdoc */
    public function beforeRun()
    {
        $this->controller->getView()->title = $this->title();
        $this->controller->getView()->params['breadcrumbs'] = $this->breadcrumbs();
        return parent::beforeRun();
    }
}
