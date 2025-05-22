<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $searchModel app\models\processolicitatorio\ProcessoLicitatorioSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consulta Pública de Processos Licitatórios';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="fs-4 fw-bold text-primary mb-4">
    <i class="bi bi-search"></i> <?= Html::encode($this->title) ?>
</h1>

<!-- Botão que abre o Offcanvas -->
<div class="mb-3 text-end">
    <button class="btn btn-outline-primary shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#pesquisaAvancada" aria-controls="pesquisaAvancada">
        <i class="bi bi-funnel-fill me-1"></i> Pesquisa Avançada
    </button>
</div>

<!-- Offcanvas de Pesquisa -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="pesquisaAvancada" aria-labelledby="pesquisaAvancadaLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-semibold text-primary" id="pesquisaAvancadaLabel">
            <i class="bi bi-funnel-fill me-2"></i> Filtros Avançados
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body">
        <?= $this->render('_search', ['model' => $searchModel]) ?>
    </div>
</div>

<?php
// Estilo para ajustar largura do canvas
$this->registerCss(".offcanvas-end { width: 460px; }");
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'prolic_sequenciamodal',
        'ano',
        'prolic_objeto:ntext',
        'prolic_codmxm',
        'prolic_valorestimado:currency',
        'prolic_valorefetivo:currency',
        [
            'attribute' => 'situacao_id',
            'value' => fn($model) => $model->situacao->sit_descricao,
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view}',
            'header' => 'Detalhes',
            'contentOptions' => ['class' => 'text-center'],
            'buttons' => [
                'view' => fn($url) => Html::a('<i class="bi bi-eye"></i>', $url, [
                    'title' => 'Visualizar',
                    'class' => 'btn btn-sm btn-outline-primary',
                    'data-pjax' => '0'
                ]),
            ],
        ],
    ],
]); ?>