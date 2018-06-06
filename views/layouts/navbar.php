<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use kartik\nav\NavX;

?>

<?php
$session = Yii::$app->session;
    NavBar::begin([
        'brandLabel' => '<img src="css/img/logo_senac_topo.png"/>',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

if($session['sess_codunidade'] == 6){ //ÁREA DA EQUIPE DO GMA

echo NavX::widget([

'options' => ['class' => 'navbar-nav navbar-right'],
                
    'items' => [
        ['label' => 'Administração', 'items' => [

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

            '<li class="divider"></li>',
            '<li class="dropdown-header">Área do Administrador</li>',
            ['label' => 'Processos Licitatórios', 'url' => ['/processolicitatorio/processo-licitatorio/index']],
        ]
    ],

    ]
]);

} else{

echo NavX::widget([

'options' => ['class' => 'navbar-nav navbar-right'],
                
    'items' => [
        ['label' => 'Processos Licitatórios', 'url' => ['/processolicitatorio/processo-licitatorio/consulta-processos-licitatorios']],
    ]
]);

}

    NavBar::end();
?>