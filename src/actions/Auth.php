<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\events\SocialAuthEvent;
use DevGroup\Users\models\SocialService;
use DevGroup\Users\models\User;
use DevGroup\Users\models\UserService;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\web\ServerErrorHttpException;

class Auth extends AuthAction
{
    protected $socialServiceId = null;
    protected $serviceId = null;

    public function init()
    {
        $this->successCallback = Yii::$app->user->getIsGuest()
            ? [$this, 'authenticate']
            : [$this, 'bindSocialNetwork'];
        parent::init();
    }

    protected function authSuccess($client)
    {
        /** @var \yii\authclient\BaseClient $client */
        $this->socialServiceId = SocialService::classNameToId($client->className());

        if ($this->socialServiceId === null) {
            throw new ServerErrorHttpException("SocialService unknown");
        }

        // first find user service on this id
        $this->serviceId = $client->getId();

        //! @todo Add caching here based on commonTag
        $existingService = UserService::find()
            ->where(
                [
                    'service_id' => $this->serviceId,
                    'social_service_id' => $this->socialServiceId,
                ]
            )
            ->one();
        if ($existingService !== null) {
            //! @todo add better error screen here
            throw new \RuntimeException("User with such service already exists");
        }

        return parent::authSuccess($client);
    }

    public function authenticate(ClientInterface $client)
    {
        // this is the most hard part
        // create user
        // fill user with attributes
        // check if we need to run post-registration

    }

    public function bindSocialNetwork(ClientInterface $client)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $userService = new UserService();
        $userService->service_id = $this->serviceId;
        $userService->social_service_id = $this->socialServiceId;
        $user->link('services', $userService);

        $event = new SocialAuthEvent();
        $event->client = &$client;
        $event->userService = &$userService;

        $user->trigger(User::EVENT_SOCIAL_BIND, $event);

    }
}
