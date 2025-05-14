<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Accordion;
use yii\bootstrap5\Tabs;
use app\models\base\Ano;

/* @var $this yii\web\View */
/* @var $searchModel app\models\processolicitatorio\ProcessoLicitatorioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Processos Licitatórios';
$this->params['breadcrumbs'][] = $this->title;

// Obter o ID do ano atual com base no campo an_ano
$anoAtualId = Ano::find()->select('id')->where(['an_ano' => date('Y')])->scalar();

// Clonar os dataProviders com filtros por ano
$dataProviderAnoAtual = clone $dataProvider;
$dataProviderAnoAtual->query->andWhere(['processo_licitatorio.ano_id' => $anoAtualId]);

$dataProviderAnteriores = clone $dataProvider;
$dataProviderAnteriores->query->andWhere(['<', 'processo_licitatorio.ano_id', $anoAtualId]);

$gridColumns = require(__DIR__ . '/_gridColumns.php');
?>

<div class="processo-licitatorio-index">

    <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-journal-text text-primary fs-2"></i>
        Acompanhamento de <span class="text-dark">Processos Licitatórios</span>
    </h1>

    <?= Accordion::widget([
        'options' => ['class' => 'accordion w-50 mx-auto shadow-sm mb-4'],
        'items' => [
            [
                'label' => '<i class="bi bi-funnel-fill me-2"></i> Pesquisa Avançada',
                'content' => $this->render('_search', ['model' => $searchModel]),
                'contentOptions' => ['class' => 'bg-light p-3'],
                'options' => ['class' => 'mb-2'],
                'encode' => false,
                'expand' => false,
            ],
        ],
    ]) ?>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <?= Html::button(
            '<i class="bi bi-plus-circle me-1"></i> Processo Licitatório',
            [
                'value' => Url::to(['processolicitatorio/processo-licitatorio/create']),
                'class' => 'btn btn-success shadow-sm',
                'id' => 'modalButton'
            ]
        ) ?>

        <?php Modal::begin([
            'id' => 'modal',
            'size' => Modal::SIZE_LARGE,
            'options' => ['tabindex' => false],
            'clientOptions' => ['backdrop' => 'static'],
            'title' => '<h5>Gerar Processo Licitatório</h5>',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end(); ?>

        <div class="d-flex gap-2">
            <?= Html::a('<i class="bi bi-arrows-angle-expand me-1"></i> Todos', [''], [
                'class' => 'btn btn-outline-primary',
                'data-pjax' => 1,
                'data-toggle' => 'gridview-toggle',
            ]) ?>

            <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'dropdownOptions' => [
                    'label' => 'Exportar',
                    'class' => 'btn btn-outline-secondary',
                    'encodeLabel' => false,
                ],
                'showColumnSelector' => false,
            ]) ?>
        </div>
    </div>

    <style>
        .kv-panel-before {
            display: none !important;
        }
    </style>

    <?= Tabs::widget([
        'items' => [
            [
                'label' => '<i class="bi bi-calendar3"></i> Ano Atual (' . date('Y') . ')',
                'encode' => false,
                'active' => true,
                'content' => (function () use ($dataProviderAnoAtual, $searchModel, $gridColumns) {
                    ob_start();
                    Pjax::begin(['id' => 'pjax-ano-atual']);
                    echo GridView::widget([
                        'dataProvider' => $dataProviderAnoAtual,
                        'filterModel' => $searchModel,
                        'columns' => $gridColumns,
                        'rowOptions' => ['class' => 'align-middle'],
                        'headerRowOptions' => ['class' => 'table-light text-center align-middle'],
                        'filterRowOptions' => ['class' => 'table-light'],
                        'containerOptions' => ['class' => 'table-responsive'],
                        'hover' => true,
                        'export' => false,
                        'toggleData' => false,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'striped' => true,
                        'pjax' => true,
                        'panel' => [
                            'type' => GridView::TYPE_PRIMARY,
                            'heading' => '<h5 class="mb-0"><i class="bi bi-clipboard-data-fill me-2"></i>Processos Licitatórios - Ano Atual</h5>',
                        ],
                    ]);
                    Pjax::end();
                    return ob_get_clean();
                })(),
            ],
            [
                'label' => '<i class="bi bi-clock-history"></i> Anos Anteriores',
                'encode' => false,
                'content' => (function () use ($dataProviderAnteriores, $searchModel, $gridColumns) {
                    ob_start();
                    Pjax::begin(['id' => 'pjax-anos-anteriores']);
                    echo GridView::widget([
                        'dataProvider' => $dataProviderAnteriores,
                        'filterModel' => $searchModel,
                        'columns' => $gridColumns,
                        'rowOptions' => ['class' => 'align-middle'],
                        'headerRowOptions' => ['class' => 'table-light text-center align-middle'],
                        'filterRowOptions' => ['class' => 'table-light'],
                        'containerOptions' => ['class' => 'table-responsive'],
                        'hover' => true,
                        'export' => false,
                        'toggleData' => false,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'striped' => true,
                        'pjax' => true,
                        'panel' => [
                            'type' => GridView::TYPE_SECONDARY,
                            'heading' => '<h5 class="mb-0"><i class="bi bi-archive me-2"></i>Processos de Anos Anteriores</h5>',
                        ],
                    ]);
                    Pjax::end();
                    return ob_get_clean();
                })(),
            ],
        ],
        'options' => ['class' => 'mb-4'],
    ]) ?>

</div>