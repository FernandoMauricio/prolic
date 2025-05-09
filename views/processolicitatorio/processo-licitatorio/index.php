<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Accordion;

/* @var $this yii\web\View */
/* @var $searchModel app\models\processolicitatorio\ProcessoLicitatorioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acompanhamento de Processos Licitatórios';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="processo-licitatorio-index">
    <?php $gridColumns = require(__DIR__ . '/_gridColumns.php'); ?>

    <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-journal-text text-primary fs-2"></i>
        Acompanhamento de <span class="text-dark">Processos Licitatórios</span>
    </h1>

    <?php
    echo Accordion::widget([
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
    ]);
    ?>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <?= Html::button(
            '<i class="bi bi-plus-circle me-1"></i> Processo Licitatório',
            [
                'value' => Url::to(['processolicitatorio/processo-licitatorio/create']),
                'class' => 'btn btn-success shadow-sm',
                'id' => 'modalButton'
            ]
        ) ?>
        <?php
        Modal::begin([
            'id' => 'modal',
            'size'         => Modal::SIZE_LARGE,
            'options'      => ['tabindex' => false],
            'clientOptions' => ['backdrop' => 'static'],
            'title'        => '<h5>Gerar Processo Licitatório</h5>',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
        ?>
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

    <?php Pjax::begin(['id' => 'w0-pjax']); ?>

    <style>
        .kv-panel-before {
            display: none !important;
        }
    </style>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'rowOptions' => ['class' => 'align-middle'],
        'headerRowOptions' => ['class' => 'table-primary text-center align-middle'],
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
            'heading' => '<h5 class="mb-0"><i class="bi bi-clipboard-data-fill me-2"></i>Listagem - Processos Licitatórios</h5>',
        ],
    ]);

    ?>
    <?php Pjax::end(); ?>

</div>