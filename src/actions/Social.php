<?php

namespace DevGroup\Users\actions;

use DevGroup\Users\events\SocialAuthEvent;
use DevGroup\Users\helpers\ModelMapHelper;
use DevGroup\Users\models\RegistrationForm;
use DevGroup\Users\models\SocialMappings;
use DevGroup\Users\models\SocialService;
use DevGroup\Users\models\User;
use DevGroup\Users\models\UserService;
use DevGroup\Users\social\SocialServiceInterface;
use DevGroup\Users\UsersModule;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\BaseClient;
use yii\authclient\ClientInterface;
use yii\base\ErrorException;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

class Social extends AuthAction
{
    protected $socialServiceId = null;
    protected $serviceId = null;

    /** @var UserService */
    protected $userService = null;

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
        $userAttributes = $client->getUserAttributes();
        $this->serviceId = $userAttributes['id'];

        //! @todo Add caching here based on commonTag
        $this->userService = UserService::find()
            ->where(
                [
                    'service_id' => $this->serviceId,
                    'social_service_id' => $this->socialServiceId,
                ]
            )
            ->one();


        return parent::authSuccess($client);
    }

    public function authenticate(ClientInterface $client)
    {
        // find existing user by service
        if ($this->userService !== null) {
            /** @var User $user */
            $user = Yii::createObject(ModelMapHelper::User());
            $user = $user->loadModel($this->userService->user_id);
            $user->login(UsersModule::module()->loginDuration);
        } else {
            // no user for this pair
            // this is the most hard part
            // create user

            /** @var SocialServiceInterface|BaseClient $client */
            $client->retrieveAdditionalData();

            /** @var RegistrationForm $registrationForm */
            $registrationForm = Yii::createObject(ModelMapHelper::RegistrationForm());
            $this->mapServiceAttributes($client, $registrationForm);

            $user = $registrationForm->socialRegister($client);

            if ($user === false) {
                throw new ErrorException("Unable to register user");
            }

            $userService = $this->createService();
            if ($user->save() === false) {
                throw new ErrorException("Unable to save user:" . var_export($user->errors, true));
            }
            $user->link('services', $userService);

            // check if we need to run post-registration
            $user->login(UsersModule::module()->loginDuration);
        }
    }

    public function bindSocialNetwork(ClientInterface $client)
    {
        if ($this->userService !== null) {
            //! @todo add better error screen here
            throw new \RuntimeException("User with such service already exists");
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;

        $userService = $this->createService();
        $user->link('services', $userService);

        $event = new SocialAuthEvent();
        $event->client = &$client;
        $event->userService = &$userService;

        $user->trigger(User::EVENT_SOCIAL_BIND, $event);

    }

    private function createService()
    {
        $userService = new UserService();
        $userService->service_id = $this->serviceId;
        $userService->social_service_id = $this->socialServiceId;
        return $userService;
    }

    protected function mapServiceAttributes(BaseClient &$client, Model &$model)
    {
        $clientUserAttributes = $client->getUserAttributes();

        // go through native client attributes
        foreach ($clientUserAttributes as $attribute => $value) {
            if ($model->canSetProperty($attribute) || in_array($attribute, $model->attributes())) {
                $model->$attribute = $value;
            }
        }
        // now find mapping for this service
        $map = SocialMappings::mapForSocialService($this->socialServiceId);
        foreach ($map as $modelAttribute => $socialAttribute) {
            $value = [];
            foreach ($socialAttribute as $attribute) {
                $attribute = trim($attribute);
                if (isset($clientUserAttributes[$attribute])) {
                    $attributeValue = trim($clientUserAttributes[$attribute]);
                    if (!empty($attributeValue)) {
                        $value[] = $clientUserAttributes[$attribute];
                    }
                }
            }
            $value = implode(' ', $value);
            if (!empty($value)) {
                $model->$modelAttribute = $value;
            }
        }
    }
}
