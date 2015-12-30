<?php

namespace DevGroup\Users\widgets;

use DevGroup\Users\models\SocialService;
use Yii;
use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;

class SocialNetworksWidget extends AuthChoice
{

    /**
     * Renders the main content, which includes all external services links.
     */
    protected function renderMainContent()
    {
        echo Html::beginTag('div', ['class' => 'social-login']);
        foreach ($this->getClients() as $externalService) {

            $socialServiceId = SocialService::classNameToId(get_class($externalService));
            $i18n = SocialService::i18nNameById($socialServiceId);
            $this->clientLink(
                $externalService,
                '<i class="demo-icon icon-basic-'.$externalService->getName().'"></i>',
                [
                    'class' => "social-login__social-network social-login__social-network--"
                        . $externalService->getName(),
                    'title' => $i18n,
                ]
            );

        }
        echo Html::endTag('ul');
    }
}
