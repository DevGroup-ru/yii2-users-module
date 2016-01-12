<?php

namespace DevGroup\Users\social;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use \yii\authclient\clients\GoogleOAuth as BaseGoogle;

class Google extends BaseGoogle implements SocialServiceInterface
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
     */
    public function retrieveAdditionalData()
    {

    }

}