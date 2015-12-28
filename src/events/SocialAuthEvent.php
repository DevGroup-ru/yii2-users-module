<?php

namespace DevGroup\Users\events;


use DevGroup\Users\models\UserService;
use yii\authclient\ClientInterface;
use yii\base\Event;

class SocialAuthEvent extends Event
{
    /** @var ClientInterface */
    public $client = null;

    /** @var UserService */
    public $userService = null;
}
