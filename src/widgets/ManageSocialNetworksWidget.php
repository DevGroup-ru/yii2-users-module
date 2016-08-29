<?php

namespace DevGroup\Users\widgets;

use DevGroup\Users\models\SocialService;
use yii\helpers\Html;
use Yii;

class ManageSocialNetworksWidget extends SocialNetworksWidget
{

    public $services;


    /**
     * Renders the main content, which includes all external services links.
     */
    protected function renderMainContent()
    {
        echo Html::beginTag('div', ['class' => 'social-login']);

        echo Html::tag('h2', Yii::t('users', 'Add social service'));

        foreach ($this->getClients() as $externalService) {
            if (!in_array(get_class($externalService), array_keys($this->services))) {
                $socialServiceId = SocialService::classNameToId(get_class($externalService));
                $i18n = SocialService::i18nNameById($socialServiceId);
                $this->clientLink(
                    $externalService,
                    '<i class="demo-icon icon-basic-' . $externalService->getName() . '"></i>',
                    [
                        'class' => "social-login__social-network social-login__social-network--"
                            . $externalService->getName(),
                        'title' => $i18n,
                    ]
                );
            }
        }
        echo Html::endTag('div');

        echo Html::tag('div', '', ['class' => 'clearfix']);
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        if ($this->autoRender) {
            $this->renderMainContent();
        }
        echo Html::endTag('div');

        echo Html::beginTag('div', ['class' => 'social-delete']);
        echo Html::tag('h2', Yii::t('users', 'Delete social service'));
        foreach ($this->getClients() as $externalService) {
            if (in_array(get_class($externalService), array_keys($this->services))) {
                $socialServiceId = SocialService::classNameToId(get_class($externalService));
                $i18n = SocialService::i18nNameById($socialServiceId);

                echo Html::a(
                    '<i class="demo-icon icon-basic-' . $externalService->getName() . '"></i>',
                    ['@delete-social', 'service_id' => $socialServiceId],
                    [
                        'class' => "social-login__social-network social-login__social-network--"
                            . $externalService->getName(),
                        'title' => $i18n,
                    ]
                );
            }
        }
        echo Html::endTag('ul');
        echo Html::endTag('div');
    }
}
