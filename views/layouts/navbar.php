<?php

use yii\bootstrap5\NavBar;
use kartik\nav\NavX;
use yii\bootstrap5\Html;

$session = Yii::$app->session;

NavBar::begin([
    'brandLabel' => Html::img('@web/css/img/logo_senac_topo.png', ['alt' => 'Senac', 'height' => 40]),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-expand-lg navbar-dark bg-dark fixed-top',
    ],
]);

echo NavX::widget([
    'options' => ['class' => 'navbar-nav ms-auto'],
    'activateParents' => true,
    'items' => $session['sess_codunidade'] == 6 ? [

        [
            'label' => 'Administração',
            'items' => [
                ['label' => 'Parâmetros', 'items' => [
                    '<li class="dropdown-header">Administração dos Parâmetros</li>',
                    ['label' => 'Ano', 'url' => ['/base/ano/index']],
                    ['label' => 'Artigo', 'url' => ['/base/artigo/index']],
                    ['label' => 'Empresa', 'url' => ['/base/empresa/index']],
                    ['label' => 'Comprador', 'url' => ['/base/comprador/index']],
                    ['label' => 'Modalidade', 'url' => ['/base/modalidade/index']],
                    ['label' => 'Modalidade - Valor Limite', 'url' => ['/base/modalidade-valorlimite/index']],
                    ['label' => 'Ramo', 'url' => ['/base/ramo/index']],
                    ['label' => 'Recursos', 'url' => ['/base/recursos/index']],
                ]],
                '<li class="dropdown-divider"></li>',
                '<li class="dropdown-header">Área do Administrador</li>',
                ['label' => 'Processos Licitatórios', 'url' => ['/processolicitatorio/processo-licitatorio/index']],
                ['label' => 'Agenda de Compromissos', 'url' => ['/processolicitatorio/agenda/index']],
                ['label' => 'Acompanhamento dos Processos', 'url' => ['/processolicitatorio/dashboard/index']],
            ]
        ],

        [
            'label' => 'Usuário (' . Html::encode(ucwords(strtolower($session['sess_nomeusuario']))) . ')',
            'items' => [
                '<li class="dropdown-header">Área Usuário</li>',
                ['label' => 'Versões Anteriores', 'url' => ['/site/versao']],
                ['label' => 'Sair', 'url' => 'https://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
            ],
        ],

    ] : [

        ['label' => 'Processos Licitatórios', 'url' => ['/processolicitatorio/processo-licitatorio/consulta-processos-licitatorios']]

    ],
]);

NavBar::end();
