<?php

namespace DevGroup\Users\helpers;

use Yii;

/**
 * Class PasswordHelper is a helper class for Passwords.
 * Copy-paste from https://github.com/dektrium/yii2-user/blob/master/helpers/Password.php
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 *
 * @package DevGroup\Users\helpers
 */
class PasswordHelper
{
    /**
     * Wrapper for yii security helper method.
     *
     * @param $password
     *
     * @return string
     */
    public static function hash($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * Wrapper for yii security helper method.
     *
     * @param $password
     * @param $hash
     *
     * @return bool
     */
    public static function validate($password, $hash)
    {
        return Yii::$app->security->validatePassword($password, $hash);
    }

    /**
     * Generates user-friendly random password containing at least one lower case letter, one uppercase letter and one
     * digit. The remaining characters in the password are chosen at random from those three sets.
     *
     * @see https://gist.github.com/tylerhall/521810
     *
     * @param $length
     *
     * @return string
     */
    public static function generate($length)
    {
        $sets = [
            'abcdefghjkmnpqrstuvwxyz',
            'ABCDEFGHJKMNPQRSTUVWXYZ',
            '23456789',
        ];
        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }
        $password = str_shuffle($password);
        return $password;
    }
}
