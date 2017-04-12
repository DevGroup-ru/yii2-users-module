<?php

namespace DevGroup\Users\models;

use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\UsersModule;
use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class BackendCreateForm validation rules for backend user creation
 *
 * @package DevGroup\Users\models
 */
class BackendCreateForm extends Model
{
    const SCENARIO_BACKEND_USER_CREATE = 'backend-user-create';

    public $id = null;

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var string */
    public $confirmPassword;

    /** @var string */
    public $email;

    /** @var boolean */
    public $password_is_temporary;

    /** @var boolean */
    public $username_is_temporary;

    /** @var string */
    public $phone;

    /** @var boolean */
    public $is_active;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['password', 'confirmPassword'], 'required', 'on' => self::SCENARIO_BACKEND_USER_CREATE],
            ['username', 'filter', 'filter' => 'trim'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['password', 'string', 'min' => UsersModule::module()->authorizationScenario()->minPasswordLength],
            [['phone', 'id'], 'safe'],
            [
                [
                    'password_is_temporary',
                    'username_is_temporary',
                    'is_active',
                ],
                'filter',
                'filter' => 'boolval',
            ],
            [
                'username',
                'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'username',
                'message' => Yii::t('users', 'This username has already been taken'),
                'filter' => function ($query) {
                    /** @var Query $query */
                    if (null !== $this->id) {
                        $query->andWhere(['not', ['id' => $this->id]]);
                    }
                },
            ],
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'message' => Yii::t('users', 'This email address has already been taken'),
                'filter' => function ($query) {
                    /** @var Query $query */
                    if (null !== $this->id) {
                        $query->andWhere(['not', ['id' => $this->id]]);
                    }
                },
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = ArrayHelper::merge(
            UsersModule::module()->authorizationScenario()->attributeLabels(),
            Yii::createObject(ModelMapHelper::User())->attributeLabels()
        );
        return $labels;
    }
}
