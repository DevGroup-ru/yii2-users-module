<?php

namespace DevGroup\Users\social;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;

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
     */
    public function retrieveAdditionalData();

}
