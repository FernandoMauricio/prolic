<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use miloschuman\highcharts\Highcharts;
use yii\widgets\ActiveForm;
use kartik\export\ExportMenu;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\bootstrap5\Modal;
use yii\web\JsExpression;

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

Modal::begin([
    'id' => 'detalheModal',
    'title' => '<h4>Detalhes do Processo</h4>',
    'size' => Modal::SIZE_LARGE,
]);

echo '<div id="detalheModalContent">Carregando...</div>';

Modal::end();

$this->registerJs(<<<JS
function abrirModalDetalhes(url) {
  $('#detalheModal').modal('show')
    .find('#detalheModalContent')
    .load(url);
}
JS);
?>

<h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

<!-- Filtros e Exportação -->
<div class="row mb-4">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['index'],
            'options' => ['class' => 'row g-2 align-items-end']
        ]); ?>

        <?= $form->field($filtroModel, 'ano', ['options' => ['class' => 'col-auto']])->dropDownList(
            array_combine($anosDisponiveis, $anosDisponiveis),
            ['prompt' => 'Ano', 'class' => 'form-select']
        )->label(false) ?>

        <?= $form->field($filtroModel, 'mes', ['options' => ['class' => 'col-auto']])->dropDownList([
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
        ], ['prompt' => 'Mês', 'class' => 'form-select'])->label(false) ?>

        <div class="col-auto">
            <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-md-6 text-end">
        <?= ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['attribute' => 'prolic_codprocesso', 'label' => 'Código'],
                ['attribute' => 'prolic_objeto', 'label' => 'Objeto'],
                ['attribute' => 'prolic_datacertame', 'label' => 'Data Certame'],
                ['attribute' => 'prolic_datahomologacao', 'label' => 'Data Homologação'],
            ],
            'dropdownOptions' => ['label' => 'Exportar', 'class' => 'btn btn-secondary']
        ]); ?>
    </div>
</div>

<!-- KPIs -->
<div class="row text-center mb-4">
    <?php foreach (
        [
            ['label' => 'Total de Processos', 'value' => $kpi['total'], 'class' => 'primary'],
            ['label' => 'Valor Estimado', 'value' => 'R$ ' . number_format($kpi['valor_estimado'], 2, ',', '.'), 'class' => 'info'],
            ['label' => 'Valor Efetivo', 'value' => 'R$ ' . number_format($kpi['valor_efetivo'], 2, ',', '.'), 'class' => 'success'],
            ['label' => 'Ciclo Médio', 'value' => $kpi['ciclo_medio'] . ' dias', 'class' => 'warning'],
        ] as $kpiCard
    ): ?>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card border-<?= $kpiCard['class'] ?> h-100" style="min-height: 120px;">
                <div class="card-header bg-<?= $kpiCard['class'] ?> text-white fw-bold">
                    <?= $kpiCard['label'] ?>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <h4 class="mb-0"><?= $kpiCard['value'] ?></h4>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Bloco Top 5 Unidades, Requisições, Compradores por Situação -->
<div class="row mb-4">
    <!-- Top 5 Unidades Atendidas -->
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card h-100" role="region" aria-label="Gráfico Top 5 Unidades Atendidas">
            <div class="card-header bg-light text-dark fw-bold">
                Top 5 Unidades Atendidas
            </div>
            <div class="card-body" style="overflow-x: auto;">
                <div style="min-width: 300px">
                    <?= Highcharts::widget([
                        'options' => [
                            'chart' => ['type' => 'column'],
                            'title' => false,
                            'xAxis' => ['categories' => array_column($topUnidadesAtendidas, 'unidade')],
                            'yAxis' => ['title' => ['text' => 'Processos']],
                            'series' => [[
                                'name' => 'Processos',
                                'data' => array_map(function ($item) use ($filtroModel) {
                                    return [
                                        'name' => $item['unidade'],
                                        'y' => $item['count'],
                                        'url' => Url::to([
                                            'detalhes-unidade',
                                            'codigo' => $item['codigo'],
                                            'ano' => $filtroModel->ano,
                                            'mes' => $filtroModel->mes
                                        ])
                                    ];
                                }, $topUnidadesAtendidas),
                            ]],
                            'plotOptions' => [
                                'column' => [
                                    'cursor' => 'pointer',
                                    'point' => [
                                        'events' => [
                                            'click' => new JsExpression("function () { abrirModalDetalhes(this.options.url); }")
                                        ]
                                    ]
                                ]
                            ],
                            'credits' => ['enabled' => false],
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Maiores Requisições -->
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card h-100" role="region" aria-label="Gráfico Top 10 Maiores Requisições">
            <div class="card-header bg-light text-dark fw-bold">
                Top 10 Maiores Requisições
            </div>
            <div class="card-body" style="overflow-x: auto;">
                <div style="min-width: 300px">
                    <?= Highcharts::widget([
                        'options' => [
                            'chart' => ['type' => 'bar'],
                            'title' => false,
                            'xAxis' => ['categories' => array_column($maioresRequisicoes, 'numero_processo')],
                            'yAxis' => ['title' => ['text' => 'R$']],
                            'series' => [[
                                'name' => 'Valor Estimado',
                                'data' => array_map(function ($item) {
                                    return [
                                        'name' => $item['numero_processo'],
                                        'y' => (float)$item['valor_estimado'],
                                        'url' => Url::to(['detalhes-requisicao', 'codigo' => $item['codigo']])
                                    ];
                                }, $maioresRequisicoes),
                            ]],
                            'plotOptions' => [
                                'bar' => [
                                    'cursor' => 'pointer',
                                    'point' => [
                                        'events' => [
                                            'click' => new JsExpression("function () { abrirModalDetalhes(this.options.url); }")
                                        ]
                                    ]
                                ]
                            ],
                            'credits' => ['enabled' => false],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 5 Compradores por Situação -->
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card h-100" role="region" aria-label="Gráfico Top 5 Compradores por Situação">
            <div class="card-header bg-light text-dark fw-bold">
                Top 5 Compradores por Situação
            </div>
            <div class="card-body" style="overflow-x: auto;">
                <div style="min-width: 300px">
                    <?php
                    $series = [];
                    if (!empty($compradoresSituacao)) {
                        $keys = array_keys($compradoresSituacao[0]);
                        foreach ($keys as $key) {
                            if ($key === 'comprador' || $key === 'comprador_id') continue;
                            $series[] = [
                                'name' => $key,
                                'data' => array_map(function ($item) use ($key, $filtroModel) {
                                    return [
                                        'name' => $item['comprador'],
                                        'y' => (int)($item[$key] ?? 0),
                                        'url' => Url::to([
                                            'detalhes-comprador',
                                            'id' => $item['comprador_id'],
                                            'situacao' => $key,
                                            'ano' => $filtroModel->ano,
                                            'mes' => $filtroModel->mes,
                                        ])
                                    ];
                                }, $compradoresSituacao),
                            ];
                        }
                    }
                    ?>
                    <?= Highcharts::widget([
                        'options' => [
                            'chart' => ['type' => 'column'],
                            'title' => false,
                            'xAxis' => [
                                'categories' => array_column($compradoresSituacao, 'comprador'),
                                'labels' => ['style' => ['fontSize' => '13px']]
                            ],
                            'yAxis' => [
                                'title' => ['text' => 'Processos', 'style' => ['fontSize' => '13px']]
                            ],
                            'plotOptions' => [
                                'column' => [
                                    'stacking' => 'normal',
                                    'cursor' => 'pointer',
                                    'point' => [
                                        'events' => [
                                            'click' => new JsExpression("function () { abrirModalDetalhes(this.options.url); }")
                                        ]
                                    ]
                                ]
                            ],
                            'series' => $series,
                            'credits' => ['enabled' => false],
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Distribuição Mensal de Processos e Alertas -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header bg-light text-dark fw-bold">
                Distribuição Mensal de Processos e Alertas
            </div>
            <div class="card-body">
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
                            'title' => ['text' => 'Mês', 'style' => ['fontSize' => '13px']]
                        ],
                        'yAxis' => [
                            'title' => ['text' => 'Quantidade de Processos', 'style' => ['fontSize' => '13px']],
                            'labels' => ['style' => ['fontSize' => '13px']],
                        ],
                        'tooltip' => [
                            'pointFormat' => '<span style="color:{series.color}"> {series.name}: <b>{point.y}</b></span><br>',
                            'style' => ['fontSize' => '13px', 'fontFamily' => 'Arial'],
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
                            ['name' => 'Processos', 'data' => array_values($dadosProcessos), 'color' => '#007bff'],
                            ['name' => 'Alertas', 'data' => array_values($dadosAlertas), 'color' => '#dc3545']
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
    <div class="card border-danger mb-4">
        <div class="card-header bg-danger text-white fw-bold">
            ⚠️ Processos com Possível Atraso
        </div>
        <div class="card-body">
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
                            $class = 'border-secondary';
                    }
                    ?>
                    <li class="list-group-item <?= $class ?>">
                        <strong><?= Html::encode($processo->prolic_codprocesso) ?></strong>
                        (<?= Html::encode($processo->situacao->sit_descricao) ?>) —
                        <?= Html::encode(StringHelper::truncate($processo->prolic_objeto, 80)) ?> —
                        Certame: <?= Yii::$app->formatter->asDate($processo->prolic_datacertame) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>


<!-- Gráfico Modalidades e Situações -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-light text-dark fw-bold">
                Distribuição por Modalidade
            </div>
            <div class="card-body">
                <?= Highcharts::widget(['options' => [
                    'chart' => ['type' => 'column'],
                    'title' => false,
                    'xAxis' => ['categories' => array_column($modalidades, 'name')],
                    'yAxis' => ['title' => ['text' => 'Quantidade']],
                    'series' => [[
                        'name' => 'Processos',
                        'data' => array_map('intval', array_column($modalidades, 'y')),
                    ]],
                    'credits' => ['enabled' => false],
                ]]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-light text-dark fw-bold">
                Distribuição por Situação
            </div>
            <div class="card-body">
                <?= Highcharts::widget(['options' => [
                    'chart' => ['type' => 'pie'],
                    'title' => false,
                    'series' => [[
                        'name' => 'Total',
                        'colorByPoint' => true,
                        'data' => array_map(function ($item) {
                            return ['name' => $item['name'], 'y' => (int) $item['y']];
                        }, array_filter($situacoes, fn($s) => $s['y'] > 0))
                    ]],
                    'credits' => ['enabled' => false],
                ]]) ?>
            </div>
        </div>
    </div>
</div>

<!-- Top 5 Compradores -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white fw-bold">
                Top 5 Compradores
            </div>
            <div class="card-body">
                <?= Highcharts::widget(['options' => [
                    'chart' => ['type' => 'bar'],
                    'title' => false,
                    'xAxis' => ['categories' => array_column($topCompradores, 'name')],
                    'yAxis' => ['title' => ['text' => 'Total de Processos']],
                    'series' => [[
                        'name' => 'Processos',
                        'data' => array_map('intval', array_column($topCompradores, 'y'))
                    ]],
                    'credits' => ['enabled' => false],
                ]]) ?>
            </div>
        </div>
    </div>
</div>