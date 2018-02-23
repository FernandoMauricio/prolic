<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
// use kartik\nav\NavX;

?>

<?php
    NavBar::begin([
        'brandLabel' => '<img src="css/img/logo_senac_topo.png"/>',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            [
            'label' => 'Parâmetros',
            'items' => [
                        '<li class="dropdown-header">Área Administrador</li>',
                         ['label' => 'Ano', 'url' => ['/base/ano/index']],
                         ['label' => 'Artigo', 'url' => ['/base/artigo/index']],
                         ['label' => 'Empresa', 'url' => ['/base/empresa/index']],
                         ['label' => 'Comprador', 'url' => ['/base/comprador/index']],
                         ['label' => 'Modalidade', 'url' => ['/base/modalidade/index']],
                         ['label' => 'Modalidade - Valor Limite', 'url' => ['/base/modalidade-valorlimite/index']],
                         ['label' => 'Ramo', 'url' => ['/base/ramo/index']],
                         ['label' => 'Recursos', 'url' => ['/base/recursos/index']],
                       ],
            ],
            ['label' => 'Processo Licitatório', 'url' => ['/processolicitatorio/processo-licitatorio/index']],
        ],
    ]);
    NavBar::end();
?>