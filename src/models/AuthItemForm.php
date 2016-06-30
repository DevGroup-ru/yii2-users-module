<?php

namespace DevGroup\Users\models;

use Yii;
use yii\base\Model;
use yii\rbac\Item;

/**
 * LoginForm is the model behind the login form.
 */
class AuthItemForm extends Model
{
    public $name;
    public $oldname;
    public $type;
    public $description = '';
    public $ruleName = null;
    public $isNewRecord = false;

    public $children = [];

    private $errorMessage = '';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            ['name', 'check'],
            ['isNewRecord', 'boolean'],
        ];
    }

    public function scenarios()
    {
        return [
            'default' => ['name', 'oldname', 'type', 'description', 'ruleName', 'children'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function check($attribute, $params)
    {
        $authManager = Yii::$app->getAuthManager();
        if ((
                (strlen($this->oldname) == 0)
                || ($this->oldname != $this->name)
            ) && (
                ($authManager->getRole($this->$attribute) !== null)
                || $authManager->getPermission($this->$attribute) !== null
            )
        ) {
            $this->addError($attribute, Yii::t('users', "Duplicate Item '{name}'", ['name' => $this->$attribute]));
        }
    }

    /**
     * @return Item
     */
    public function createItem()
    {
        $authManager = Yii::$app->getAuthManager();
        $ruleName = trim($this->ruleName);
        $item = new Item(
            [
                'name' => $this->name,
                'type' => $this->type,
                'description' => $this->description,
                'ruleName' => empty($ruleName) ? null : $ruleName,
            ]
        );
        $authManager->add($item);
        foreach ($this->getChildren() as $value) {
            try {
                $authManager->addChild($item, new Item(['name' => $value]));
            } catch (\Exception $ex) {
                $this->errorMessage .= Yii::t('users', "Item <strong>{value}</strong> is not assigned:", [
                        'value' => $value,
                    ])
                    . " " . $ex->getMessage() . "<br />";
            }
        }
        return $item;
    }

    public function updateItem()
    {
        $item = new Item();
        $item->name = $this->name;
        $item->type = $this->type;
        $item->description = $this->description;
        $authManager = Yii::$app->getAuthManager();
        $ruleName = trim($this->ruleName);
        $item->ruleName = empty($ruleName) ? null : $ruleName;
        $authManager->update($this->oldname, $item);
        $children = $authManager->getChildren($item->name);
        $newChildren = $this->getChildren();
        foreach ($children as $value) {
            $key = array_search($value->name, $newChildren);
            if ($key === false) {
                $authManager->removeChild($item, $value);
            } else {
                unset($this->children[$key]);
            }
        }
        foreach ($this->getChildren() as $value) {
            try {
                $authManager->addChild($item, new Item(['name' => $value]));
            } catch (\Exception $ex) {
                $this->errorMessage .= Yii::t('users', "Item <strong>{value}</strong> is not assigned:", [
                        'value' => $value,
                    ])
                    . " " . $ex->getMessage() . "<br />";
            }
        }
        return $item;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return empty($this->children) === false ? (array)$this->children : [];
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('users', 'Name'),
            'oldname' => Yii::t('users', 'Old Name'),
            'type' => Yii::t('users', 'Type'),
            'description' => Yii::t('users', 'Description'),
            'ruleName' => Yii::t('users', 'Biz Rule'),
            'children' => Yii::t('users', 'Children'),
        ];
    }
}
