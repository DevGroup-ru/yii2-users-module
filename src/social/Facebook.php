<?php

namespace DevGroup\Users\social;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use yii\authclient\ClientInterface;
use \yii\authclient\clients\Facebook as BaseFacebook;

class Facebook extends BaseFacebook implements SocialServiceInterface
{

    /**
     * @return string Path to configuration view for this social service(used in backend)
     */
    public static function configurationView()
    {
        return 'TBD';
    }

    /**
     * @return BaseConfigurationModel Instance of configuration model
     */
    public static function configurationModel()
    {
        return null;
    }

    /**
     * Retrieves additional data from social network and puts it to client.
     *
     * @param \yii\authclient\BaseClient $client
     *
     * @return \yii\authclient\BaseClient
     */
    public static function retrieveAdditionalData(ClientInterface &$client)
    {
        return $client;
    }

}