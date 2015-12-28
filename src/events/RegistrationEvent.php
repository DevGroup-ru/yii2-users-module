<?php

namespace DevGroup\Users\events;

use yii\base\Event;

class RegistrationEvent extends Event
{
    public $isValid = true;
}
