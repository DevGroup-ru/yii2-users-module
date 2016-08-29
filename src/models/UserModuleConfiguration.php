<?php

namespace DevGroup\Users\models;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use DevGroup\Users\UsersModule;

/**
 * Class UserModuleConfiguration
 * @package DevGroup\Users\models
 */
class UserModuleConfiguration extends BaseConfigurationModel
{


    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $attributes = [

            'emailConfirmationNeeded',
            'allowLoginInactiveAccounts',
            'enableSocialNetworks',
            'passwordResetTokenExpire',
            'logLastLoginTime',
            'logLastLoginData',
            'loginDuration',
            'generatedPasswordLength',
        ];

        parent::__construct($attributes, $config);
        $module = UsersModule::module();
        foreach ($attributes as $attribute) {
            $this->{$attribute} = $module->{$attribute};
        }
    }


    /**
     * Returns array of module configuration that should be stored in application config.
     * Array should be ready to merge in app config.
     * Used both for web only.
     *
     * @return array
     */
    public function webApplicationAttributes()
    {
        return [];
    }

    /**
     * Returns array of module configuration that should be stored in application config.
     * Array should be ready to merge in app config.
     * Used both for console only.
     *
     * @return array
     */
    public function consoleApplicationAttributes()
    {
        return [];
    }


    /**
     * Returns array of module configuration that should be stored in application config.
     * Array should be ready to merge in app config.
     * Used both for web and console.
     *
     * @return array
     */
    public function commonApplicationAttributes()
    {
        return [
            'modules' => [
                'users' => [
                    'emailConfirmationNeeded' => (bool)$this->emailConfirmationNeeded,
                    'allowLoginInactiveAccounts' => (bool)$this->allowLoginInactiveAccounts,
                    'enableSocialNetworks' => (bool)$this->enableSocialNetworks,
                    'logLastLoginTime' => (bool)$this->logLastLoginTime,
                    'logLastLoginData' => (bool)$this->logLastLoginData,
                    'passwordResetTokenExpire' => (int)$this->passwordResetTokenExpire,
                    'loginDuration' => (int)$this->loginDuration,
                    'generatedPasswordLength' => (int)$this->generatedPasswordLength,
                ]
            ],
        ];
    }

    /**
     * Returns array of key=>values for configuration.
     *
     * @return mixed
     */
    public function appParams()
    {
        return [];
    }

    /**
     * Returns array of aliases that should be set in common config
     * @return array
     */
    public function aliases()
    {
        return [];
    }


    /**
     * Returns the validation rules for attributes.
     * @return array validation rules
     * @see scenarios()
     */
    public function rules()
    {
        return [
            [
                [
                    'loginDuration',
                    'passwordResetTokenExpire',
                    'passwordResetTokenExpire',
                    'generatedPasswordLength'
                ],
                'integer'
            ],
            [
                [
                    'emailConfirmationNeeded',
                    'allowLoginInactiveAccounts',
                    'enableSocialNetworks',
                    'logLastLoginTime',
                    'logLastLoginData'
                ],
                'boolean',
                'trueValue' => true,
                'falseValue' => false,
            ],
        ];
    }
}
