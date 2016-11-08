<?php

namespace DevGroup\Users\models;

use DevGroup\DataStructure\behaviors\PackedJsonAttributes;
use DevGroup\DataStructure\behaviors\HasProperties;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use DevGroup\DataStructure\traits\PropertiesTrait;
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
 * @property string $username Username
 * @property string $phone Phone
 * @property string $email Email
 * @property string $email_activation_token E-Mail activation token
 * @property string $password_hash Password hash
 * @property string $password_reset_token Password reset token with expiration time, null|empty
 * @property string $auth_key Yii2 auth key
 * @property boolean $is_active If user is active/activated his account through email verification link
 * @property string $created_at
 * @property string $updated_at
 * @property string $activated_at
 * @property string $last_login_at
 * @property string $login_data
 * @property boolean $username_is_temporary If username is temporary and was generated
 * @property boolean $password_is_temporary Whether password must be changed after first login
 * @property UserService $services
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    use TagDependencyTrait;
    use PropertiesTrait;

    const SCENARIO_PROFILE_UPDATE = 'scenario-profile-update';
    const SCENARIO_PASSWORD_RESET = 'scenario-password-reset';

    const EVENT_BEFORE_REGISTER = 'before-register';
    const EVENT_AFTER_REGISTER = 'after-register';
    const EVENT_LOGIN = 'login';
    const EVENT_SOCIAL_LOGIN = 'event-social-login';
    const EVENT_SOCIAL_BIND = 'event-social-bind';
    const EVENT_PASSWORD_CHANGE = 'event-password-change';
    const EVENT_ROLE_ADDED = 'event-role-added';

    /** @var string Plain password. Used for model validation. */
    public $password = '';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [
            'CacheableActiveRecord' => [
                'class' => CacheableActiveRecord::class,
            ],
            'updateTimestamps' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            'properties' => [
                'class' => HasProperties::class,
                'autoFetchProperties' => true,
            ],
        ];

        if (UsersModule::module()->logLastLoginData === true) {
            $behaviors['json_attributes'] = [
                'class' => PackedJsonAttributes::class,
            ];
        }

        return $behaviors;
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('users', 'Username'),
            'password' => Yii::t('users', 'Password'),
            'email' => Yii::t('users', 'E-Mail'),
            'phone' => Yii::t('users', 'Phone'),
            'email_activation_token' => Yii::t('users', 'Activation Token'),
            'password_hash' => Yii::t('users', 'Password Hash'),
            'password_reset_token' => Yii::t('users', 'Password Reset Token'),
            'auth_key' => Yii::t('users', 'Auth Key'),
            'is_active' => Yii::t('users', 'User Is Active'),
            'activated_at' => Yii::t('users', 'Activated At'),
            'last_login_at' => Yii::t('users', 'Last Login At'),
            'login_data' => Yii::t('users', 'Login Data'),
            'username_is_temporary' => Yii::t('users', 'User Is Temporary'),
            'password_is_temporary' => Yii::t('users', 'Password Is Temporary'),
            'created_at' => Yii::t('users', 'Created at'),
            'updated_at' => Yii::t('users', 'Updated at'),
        ];
    }

    /**
     * Validation rules for this model.
     */
    public function rules()
    {
        $module = UsersModule::module();
        $rules = [
            [
                ['phone'],
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
                [
                    'password',
                ],
                'required',
                'on' => self::SCENARIO_PASSWORD_RESET,
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
            'trimEmail' => [
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
                    'password_is_temporary',
                    'is_active',
                ],
                'filter',
                'filter' => 'boolval',
            ]
        ];
        $property_rules = $this->propertiesRules();
        if (!empty($property_rules)) {
            $rules = array_merge($rules, $property_rules);
        }
        if (count(UsersModule::module()->requiredUserAttributes) > 0) {
            $rules['requiredAttributes'] = [
                UsersModule::module()->requiredUserAttributes,
                'required',
                'on' => self::SCENARIO_PROFILE_UPDATE,
            ];
        }


        return $rules;
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

    /**
     * @return bool
     */
    public function changePassword()
    {
        if ($this->getIsNewRecord() == true) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" non existing user');
        }
        $this->trigger(User::EVENT_PASSWORD_CHANGE);
        $this->password_is_temporary = 0;
        $this->password_hash = PasswordHelper::hash($this->password);
        return $this->save();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function getServices()
    {
        return $this->hasMany(UserService::className(), ['user_id' => 'id']);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = UsersModule::module()->passwordResetTokenExpire;
        return $timestamp + $expire >= time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = '';
    }
}
