<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use miloschuman\highcharts\Highcharts;
use yii\widgets\ActiveForm;
use kartik\export\ExportMenu;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $filtroModel \app\models\FiltroDashboardForm */
/* @var $kpi array */
/* @var $modalidades array */
/* @var $situacoes array */
/* @var $topCompradores array */
/* @var $alertas array */
/* @var $anosDisponiveis array */

$this->title = 'Painel de Acompanhamento';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ArrayDataProvider([
    'allModels' => $alertas,
    'pagination' => false,
]);
?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['index'],
            'options' => ['class' => 'form-inline']
        ]); ?>

        <?= $form->field($filtroModel, 'ano')->dropDownList(
            array_combine($anosDisponiveis, $anosDisponiveis),
            ['prompt' => 'Ano']
        )->label(false) ?>

        <?= $form->field($filtroModel, 'mes')->dropDownList([
            '1' => 'Janeiro',
            '2' => 'Fevereiro',
            '3' => 'Março',
            '4' => 'Abril',
            '5' => 'Maio',
            '6' => 'Junho',
            '7' => 'Julho',
            '8' => 'Agosto',
            '9' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        ], ['prompt' => 'Mês'])->label(false) ?>

        <div class="form-group" style="margin-top: -10px;">
            <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-md-6 text-right">
        <?= ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['attribute' => 'prolic_codprocesso', 'label' => 'Código'],
                ['attribute' => 'prolic_objeto', 'label' => 'Objeto'],
                ['attribute' => 'prolic_datacertame', 'label' => 'Data Certame'],
                ['attribute' => 'prolic_datahomologacao', 'label' => 'Data Homologação'],
            ],
            'dropdownOptions' => ['label' => 'Exportar', 'class' => 'btn btn-default']
        ]); ?>
    </div>
</div>

<!-- KPIs -->
<div class="row text-center">
    <?php foreach (
        [
            ['label' => 'Total de Processos', 'value' => $kpi['total'], 'class' => 'primary'],
            ['label' => 'Valor Estimado', 'value' => 'R$ ' . number_format($kpi['valor_estimado'], 2, ',', '.'), 'class' => 'info'],
            ['label' => 'Valor Efetivo', 'value' => 'R$ ' . number_format($kpi['valor_efetivo'], 2, ',', '.'), 'class' => 'success'],
            ['label' => 'Ciclo Médio', 'value' => $kpi['ciclo_medio'] . ' dias', 'class' => 'warning'],
        ] as $kpiCard
    ): ?>
        <div class="col-md-3">
            <div class="panel panel-<?= $kpiCard['class'] ?>">
                <div class="panel-heading"><?= $kpiCard['label'] ?></div>
                <div class="panel-body">
                    <h4><?= $kpiCard['value'] ?></h4>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Distribuição Mensal de Processos e Alertas -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Distribuição Mensal de Processos e Alertas</strong></div>
            <div class="panel-body">
                <?php
                $meses = [
                    1 => 'Jan',
                    2 => 'Fev',
                    3 => 'Mar',
                    4 => 'Abr',
                    5 => 'Mai',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Ago',
                    9 => 'Set',
                    10 => 'Out',
                    11 => 'Nov',
                    12 => 'Dez'
                ];
                $dadosProcessos = array_fill(1, 12, 0);
                foreach ($distribuicaoMensal['processos'] as $p) {
                    $dadosProcessos[(int)$p['mes']] = (int)$p['y'];
                }
                $dadosAlertas = array_fill(1, 12, 0);
                foreach ($distribuicaoMensal['alertas'] as $a) {
                    $dadosAlertas[(int)$a['mes']] = (int)$a['y'];
                }

                echo Highcharts::widget([
                    'options' => [
                        'chart' => [
                            'type' => 'column',
                            'style' => [
                                'fontFamily' => 'Arial',
                                'fontSize' => '13px',
                            ],
                        ],
                        'title' => false,
                        'xAxis' => [
                            'categories' => array_values($meses),
                            'labels' => ['style' => ['fontSize' => '13px']],
                            'title' => [
                                'text' => 'Mês',
                                'style' => ['fontSize' => '13px']
                            ]
                        ],
                        'yAxis' => [
                            'title' => [
                                'text' => 'Quantidade de Processos',
                                'style' => ['fontSize' => '13px']
                            ],
                            'labels' => ['style' => ['fontSize' => '13px']],
                        ],
                        'tooltip' => [
                            'pointFormat' => '<span style="color:{series.color}"> {series.name}: <b>{point.y}</b></span><br>',
                            'style' => [
                                'fontSize' => '13px',
                                'fontFamily' => 'Arial',
                            ],
                        ],
                        'plotOptions' => [
                            'column' => [
                                'dataLabels' => [
                                    'enabled' => true,
                                    'style' => [
                                        'fontSize' => '12px',
                                        'fontWeight' => 'bold',
                                        'color' => '#000'
                                    ]
                                ]
                            ]
                        ],
                        'series' => [
                            [
                                'name' => 'Processos',
                                'data' => array_values($dadosProcessos),
                                'color' => '#007bff'
                            ],
                            [
                                'name' => 'Alertas',
                                'data' => array_values($dadosAlertas),
                                'color' => '#dc3545'
                            ]
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>


<!-- Alertas -->
<?php if (!empty($alertas)): ?>
    <div class="panel panel-danger">
        <div class="panel-heading"><strong>⚠️ Processos com Possível Atraso</strong></div>
        <div class="panel-body">
            <ul class="list-group">
                <?php foreach ($alertas as $processo): ?>
                    <?php
                    switch ($processo->situacao_id) {
                        case 1:
                            $class = 'list-group-item-success';
                            break;
                        case 2:
                            $class = 'list-group-item-warning';
                            break;
                        case 5:
                            $class = 'list-group-item-danger';
                            break;
                        case 6:
                            $class = 'list-group-item-info';
                            break;
                        default:
                            $class = 'list-group-item-default';
                    }
                    ?>
                    <li class="list-group-item <?= $class ?>">
                        <strong><?= Html::encode($processo->prolic_codprocesso) ?></strong> —
                        <?= Html::encode(StringHelper::truncate($processo->prolic_objeto, 80)) ?> —
                        Certame: <?= Yii::$app->formatter->asDate($processo->prolic_datacertame) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<!-- Gráficos Modalidade e Situação -->
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Distribuição por Modalidade</strong></div>
            <div class="panel-body">
                <?= Highcharts::widget([
                    'options' => [
                        'chart' => ['type' => 'column'],
                        'title' => false,
                        'xAxis' => [
                            'categories' => array_column($modalidades, 'name'),
                            'labels' => ['style' => ['fontSize' => '13px']],
                        ],
                        'yAxis' => [
                            'title' => [
                                'text' => 'Quantidade',
                                'style' => [
                                    'fontSize' => '13px',
                                    'fontWeight' => 'bold',
                                    'color' => '#333'
                                ]
                            ],
                            'labels' => [
                                'style' => ['fontSize' => '13px']
                            ]
                        ],
                        'legend' => [
                            'itemStyle' => [
                                'fontSize' => '13px',
                                'fontWeight' => 'bold',
                            ],
                        ],
                        'tooltip' => [
                            'headerFormat' => '<b>{point.key}</b><br>',
                            'pointFormat' => '<span style="color:{series.color}">Total: <b>{point.y}</b></span><br>',
                            'style' => ['fontSize' => '13px'],
                        ],
                        'plotOptions' => [
                            'column' => [
                                'dataLabels' => [
                                    'enabled' => true,
                                    'style' => [
                                        'fontSize' => '13px',
                                        'fontWeight' => 'bold',
                                        'color' => '#000000',
                                    ],
                                ],
                            ]
                        ],
                        'series' => [[
                            'name' => 'Processos',
                            'data' => array_map('intval', array_column($modalidades, 'y')),
                        ]]
                    ]
                ]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Distribuição por Situação</strong></div>
            <div class="panel-body">
                <?= Highcharts::widget([
                    'options' => [
                        'chart' => ['type' => 'pie'],
                        'title' => false,
                        'tooltip' => [
                            'headerFormat' => '<span style="font-size:14px"><b>{point.key}</b></span><br>',
                            'pointFormat' => 'Total: <b>{point.y}</b> ({point.percentage:.1f}%)',
                            'style' => ['fontSize' => '13px'],
                        ],
                        'plotOptions' => [
                            'pie' => [
                                'allowPointSelect' => true,
                                'cursor' => 'pointer',
                                'dataLabels' => [
                                    'enabled' => true,
                                    'format' => '{point.name}: {point.percentage:.1f}%',
                                    'style' => [
                                        'fontSize' => '16px',
                                        'fontWeight' => 'bold',
                                        'color' => '#000000',
                                    ]
                                ]
                            ]
                        ],
                        'series' => [[
                            'name' => 'Total',
                            'colorByPoint' => true,
                            'data' => array_map(function ($item) {
                                return [
                                    'name' => $item['name'],
                                    'y' => (int)$item['y']
                                ];
                            }, array_filter($situacoes, fn($s) => $s['y'] > 0)),

                        ]]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>

<!-- Top 5 Compradores -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Top 5 Compradores</strong></div>
            <div class="panel-body">
                <?= Highcharts::widget([
                    'options' => [
                        'chart' => ['type' => 'bar'],
                        'title' => false,
                        'xAxis' => [
                            'categories' => array_column($topCompradores, 'name'),
                            'labels' => ['style' => ['fontSize' => '13px']]
                        ],
                        'yAxis' => [
                            'title' => [
                                'text' => 'Total de Processos',
                                'style' => ['fontSize' => '16px', 'fontWeight' => 'bold']
                            ],
                            'labels' => [
                                'style' => ['fontSize' => '13px']
                            ]
                        ],
                        'tooltip' => [
                            'pointFormat' => '<span style="color:{series.color}"><b>{point.y} processos</b><br>',
                            'style' => [
                                'fontSize' => '13px',
                                'fontFamily' => 'Arial',
                            ],
                        ],
                        'plotOptions' => [
                            'bar' => [
                                'dataLabels' => [
                                    'enabled' => true,
                                    'style' => [
                                        'fontSize' => '16px',
                                        'fontWeight' => 'bold',
                                        'color' => '#000000',
                                    ]
                                ],
                            ]
                        ],
                        'legend' => ['enabled' => false],
                        'series' => [[
                            'name' => 'Processos',
                            'data' => array_map('intval', array_column($topCompradores, 'y'))
                        ]]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>