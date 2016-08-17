<?php

namespace DevGroup\Users\social;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use \yii\authclient\clients\Google as BaseGoogle;
use yii\helpers\Url;

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

    public function setReturnUrl($returnUrl)
    {
        // google oauth can't redirect with redirectUrl param
        return parent::setReturnUrl(preg_replace('/\\?returnUrl=[^&]*&/s', '?', $returnUrl));
    }

    protected function defaultReturnUrl()
    {
        // google oauth can't redirect with redirectUrl param
        return preg_replace('/\\?returnUrl=[^&]*&/s', '?', parent::defaultReturnUrl());
    }
    
    /** @inheritdoc */
    protected function normalizeUserAttributes($attributes)
    {
        $attributes = parent::normalizeUserAttributes($attributes);
        if (isset($attributes['emails'][0]['value']) === true) {
            $attributes['email'] = $attributes['emails'][0]['value'];
        }
        $attributes['name'] = $attributes['displayName'];
        return $attributes;
    }

}
