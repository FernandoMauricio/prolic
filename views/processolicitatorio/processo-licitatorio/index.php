<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Accordion;

/* @var $this yii\web\View */
/* @var $searchModel app\models\processolicitatorio\ProcessoLicitatorioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// ini_set('memory_limit', '-1');

$this->title = 'Acompanhamento de Processos Licitatórios';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- <style>
    /* Estilo para o container da GridView com rolagem horizontal */
    .kv-grid-container {
        overflow-x: auto;
        width: 100%;
    }

    /* Torna as células da GridView mais compactas */
    .kv-grid-table>thead>tr>th,
    .kv-grid-table>tbody>tr>td {
        white-space: nowrap;
        vertical-align: middle;
        font-size: 13px;
    }

    /* Adaptação da responsividade ao tamanho da tela */
    @media (max-width: 992px) {
        .kv-grid-wrapper {
            padding: 0;
        }

        .kv-panel-before,
        .kv-panel-after {
            display: none;
        }
    }
</style> -->
<div class="processo-licitatorio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    echo Accordion::widget([
        'items' => [
            [
                'label' => '🔍 Pesquisa Avançada',
                'content' => $this->render('_search', ['model' => $searchModel]),
                'contentOptions' => ['class' => 'bg-light p-3'],
                'options' => ['class' => 'mb-3'],
            ],
        ],
    ]);
    ?>

    <p>
        <?= Html::button('Novo Processo Licitatório', ['value' => Url::to(['processolicitatorio/processo-licitatorio/gerar-processo-licitatorio']), 'class' => 'btn btn-success', 'id' => 'modalButton']) ?>
    </p>

    <?php
    Modal::begin([
        'options' => ['tabindex' => false], // important for Select2 to work properly
        'title' => '<h3>Novo Processo Licitatório</h3>',
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => true],
        'id' => 'modal',
        'size' => 'modal-lg',
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
    ?>

    <?php $gridColumns = require(__DIR__ . '/_gridColumns.php'); ?>

    <?php Pjax::begin(['id' => 'w0-pjax']); ?>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'rowOptions' => ['style' => 'font-size: 12px'],
        'headerRowOptions' => ['style' => 'font-size: 12px'],
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjax' => true, // pjax is set to always true for this demo

        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => 'Detalhes dos Processos', 'options' => ['colspan' => 16, 'class' => 'text-center warning']],
                    ['content' => 'Ações', 'options' => ['colspan' => 1, 'class' => 'text-center warning']],
                ],
            ]
        ],
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Listagem - Processos Licitatórios</h3>',
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>