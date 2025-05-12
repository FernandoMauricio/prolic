<?php

use yii\bootstrap5\NavBar;
use kartik\nav\NavX;
use yii\bootstrap5\Html;

$session = Yii::$app->session;

NavBar::begin([
    'brandLabel' => Html::img('@web/css/img/logo_senac_topo.png', ['alt' => 'Senac']),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-expand-lg navbar-dark navbar-custom fixed-top',
    ],
]);

echo NavX::widget([
    'options' => ['class' => 'navbar-nav ms-auto'],
    'activateParents' => true,
    'encodeLabels' => false, // permite usar ícones
    'items' => $session['sess_codunidade'] == 6 ? [

        [
            'label' => '<i class="bi bi-gear-fill me-1"></i> Administração',
            'items' => [
                ['label' => '<i class="bi bi-sliders"></i> <strong>Parâmetros</strong>', 'items' => [
                    ['label' => '<i class="bi bi-calendar2-week"></i> Ano', 'url' => ['/base/ano/index']],
                    ['label' => '<i class="bi bi-journal-text"></i> Artigo', 'url' => ['/base/artigo/index']],
                    ['label' => '<i class="bi bi-buildings"></i> Empresa', 'url' => ['/base/empresa/index']],
                    ['label' => '<i class="bi bi-person"></i> Comprador', 'url' => ['/base/comprador/index']],
                    ['label' => '<i class="bi bi-tag-fill"></i> Modalidade', 'url' => ['/base/modalidade/index']],
                    ['label' => '<i class="bi bi-graph-up-arrow"></i> Valor Limite', 'url' => ['/base/modalidade-valorlimite/index']],
                    ['label' => '<i class="bi bi-diagram-2"></i> Segmento', 'url' => ['/base/ramo/index']],
                    ['label' => '<i class="bi bi-lightning-charge-fill"></i> Recursos', 'url' => ['/base/recursos/index']],
                ]],
                '<li class="dropdown-divider"></li>',
                '<li class="dropdown-header text-muted">Área do Administrador</li>',
                ['label' => '<i class="bi bi-clipboard-data"></i> Processos Licitatórios', 'url' => ['/processolicitatorio/processo-licitatorio/index']],
                ['label' => '<i class="bi bi-calendar3"></i> Agenda de Compromissos', 'url' => ['/processolicitatorio/agenda/index']],
                ['label' => '<i class="bi bi-bar-chart-line-fill"></i> Acompanhamento', 'url' => ['/processolicitatorio/dashboard/index']],
            ],
        ],

        [
            'label' => '<i class="bi bi-person-circle"></i> ' . Html::encode(ucwords(strtolower($session['sess_nomeusuario']))),
            'items' => [
                '<li class="dropdown-header text-muted">Área do Usuário</li>',
                ['label' => '<i class="bi bi-clock-history"></i> Versões Anteriores', 'url' => ['/site/versao']],
                ['label' => '<i class="bi bi-box-arrow-right"></i> Sair', 'url' => 'https://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
            ],
        ],

    ] : [

        ['label' => '<i class="bi bi-file-text-fill"></i> Processos Licitatórios', 'url' => ['/processolicitatorio/processo-licitatorio/consulta-processos-licitatorios']]

    ],
]);

NavBar::end();
