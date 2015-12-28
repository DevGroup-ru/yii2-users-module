<?php

namespace DevGroup\Users\social;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use yii\authclient\ClientInterface;

interface SocialServiceInterface
{
    /**
     * @return string Path to configuration view for this social service(used in backend)
     */
    public static function configurationView();

    /**
     * @return BaseConfigurationModel Instance of configuration model
     */
    public static function configurationModel();

    /**
     * Retrieves additional data from social network and puts it to client.
     *
     * @param \yii\authclient\ClientInterface $client
     *
     * @return \yii\authclient\ClientInterface
     */
    public static function retrieveAdditionalData(ClientInterface &$client);

}
