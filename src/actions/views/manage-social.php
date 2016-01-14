<?php
use DevGroup\Frontend\RedirectHelper;
use DevGroup\Users\widgets\ManageSocialNetworksWidget;
use yii\helpers\Url;

/**
 * @var array $services;
 */
?>

<h1><?= Yii::t('users', 'Manage Social'); ?></h1>


<?= ManageSocialNetworksWidget::widget([
    'baseAuthUrl' => ['@add-social', 'returnUrl' => Url::to(['@manage-social'])],
    'services' => $services
]); ?>