<?php

namespace DevGroup\Users\models;

use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use DevGroup\Users\events\RegistrationEvent;
use DevGroup\Users\helpers\PasswordHelper;
use DevGroup\Users\UsersModule;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * Class User represents base User model
 *
 * @package DevGroup\Users\models
 * @property integer $id
 * @property string  $username Username
 * @property string  $phone Phone
 * @property string  $email Email
 * @property string  $email_activation_token E-Mail activation token
 * @property string  $password_hash Password hash
 * @property string  $password_reset_token Password reset token with expiration time, null|empty
 * @property string  $auth_key Yii2 auth key
 * @property boolean $is_active If user is active/activated his account through email verification link
 * @property string  $created_at
 * @property string  $updated_at
 * @property string  $activated_at
 * @property string  $last_login_at
 * @property boolean $username_is_temporary If username is temporary and was generated
 * @property UserService $services
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    use TagDependencyTrait;

    const SCENARIO_PROFILE_UPDATE = 'scenario-profile-update';

    const EVENT_BEFORE_REGISTER = 'before-register';
    const EVENT_AFTER_REGISTER = 'after-register';
    const EVENT_LOGIN = 'login';
    const EVENT_SOCIAL_LOGIN = 'event-social-login';
    const EVENT_SOCIAL_BIND = 'event-social-bind';

    /** @var string Plain password. Used for model validation. */
    public $password = '';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'CacheableActiveRecord' => [
                'class' => CacheableActiveRecord::className(),
            ],
            'updateTimestamps' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Validation rules for this model.
     */
    public function rules()
    {
        $module = UsersModule::module();
        return [
            [
                'phone',
                'safe',
            ],
            [
                [
                    'password',
                ],
                'safe',
                'on' => self::SCENARIO_DEFAULT,
            ],
            [
                'username',
                'unique',
            ],
            [
                'email',
                'unique',
            ],
            'standardEmailRules' => [
                [
                    'email',
                ],
                'email',
                'checkDNS' => $module->authorizationScenario()->emailCheckDNS,
                'enableIDN' => $module->authorizationScenario()->emailEnableIDN,
                'skipOnEmpty' => true,
                'on' => self::SCENARIO_PROFILE_UPDATE,
            ],
            'trimEmail' =>[
                [
                    'email',
                ],
                'filter',
                'filter' => 'trim',
                'on' => self::SCENARIO_PROFILE_UPDATE,
            ],
            [
                [
                    'username_is_temporary',
                    'is_active',
                ],
                'filter',
                'filter' => 'boolval',
            ],
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PROFILE_UPDATE] = [
            'phone',
        ];

        if ($this->username_is_temporary) {
            $scenarios[self::SCENARIO_PROFILE_UPDATE][] = 'username';
        }
        if (empty($this->email)) {
            $scenarios[self::SCENARIO_PROFILE_UPDATE][] = 'email';
        }

        return $scenarios;
    }

    /**
     * Performs after find action and casts attributes to proper type
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->username_is_temporary = boolval($this->username_is_temporary);
        $this->is_active = boolval($this->is_active);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::loadModel($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAttribute('auth_key') === $authKey;
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('auth_key', Yii::$app->security->generateRandomString());
        }
        if (!empty($this->password)) {
            $this->setAttribute('password_hash', PasswordHelper::hash($this->password));
        }
        return parent::beforeSave($insert);
    }

    /**
     * This method is used to register new user account.
     *
     * @return bool|User
     */
    public function register()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $module = UsersModule::module();

        if (empty($this->password) === true) {
            $this->password = PasswordHelper::generate($module->generatedPasswordLength);
        }

        if ($module->emailConfirmationNeeded === false) {
            $this->is_active = true;
        }

        $event = new RegistrationEvent();
        $this->trigger(self::EVENT_BEFORE_REGISTER, $event);

        if ($event->isValid === false) {
            return false;
        }

        if (!$this->save()) {
            return false;
        }

        $this->trigger(self::EVENT_AFTER_REGISTER, $event);
        return $this;
    }

    /**
     * Login user.
     * @param integer $loginDuration
     * @return bool true if success
     */
    public function login($loginDuration = 0)
    {
        $loginStatus = Yii::$app->getUser()->login($this, $loginDuration);
        if ($loginStatus) {
            $this->trigger(User::EVENT_LOGIN);
        }
        return $loginStatus;
    }

    public function getServices()
    {
        return $this->hasMany(UserService::className(), ['user_id' => 'id']);
    }
}
