<?php

namespace DevGroup\Users\events;

use yii\base\Event;

/**
 * Class RegistrationEvent
 * @package DevGroup\Users\events
 */
class RegistrationEvent extends Event
{
    public $isValid = true;
}
