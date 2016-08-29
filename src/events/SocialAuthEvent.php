<?php

namespace DevGroup\Users\events;

use DevGroup\Users\models\UserService;
use yii\authclient\ClientInterface;
use yii\base\Event;

/**
 * Class SocialAuthEvent
 *
 * @package DevGroup\Users\events
 */
class SocialAuthEvent extends Event
{
    /** @var ClientInterface */
    public $client = null;

    /** @var UserService */
    public $userService = null;
}
