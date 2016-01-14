<?php
use DevGroup\Frontend\RedirectHelper;
use DevGroup\Users\widgets\ManageSocialNetworksWidget;

/**
 * @var array $services;
 */
?>

<h1><?= Yii::t('users', 'Manage Social'); ?></h1>


<?= ManageSocialNetworksWidget::widget([
    'baseAuthUrl' => ['@add-social', 'returnUrl' => RedirectHelper::getReturnUrl()],
    'services' => $services
]); ?>