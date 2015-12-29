<?php

namespace DevGroup\Users\helpers;

use DevGroup\Users\UsersModule;
use Yii;

class ModelMapHelper
{
    /**
     * @return array Returns initiated model map
     */
    public static function modelMap()
    {
        return UsersModule::module()->modelMap;
    }

    /**
     * @return \DevGroup\Users\models\User
     */
    public static function User()
    {
        $map = self::modelMap();
        return $map['User'];
    }

    /**
     * @return \DevGroup\Users\models\RegistrationForm
     */
    public static function RegistrationForm()
    {
        $map = self::modelMap();
        return $map['RegistrationForm'];
    }

    /**
     * @return \DevGroup\Users\models\LoginForm
     */
    public static function LoginForm()
    {
        $map = self::modelMap();
        return $map['LoginForm'];
    }
}
