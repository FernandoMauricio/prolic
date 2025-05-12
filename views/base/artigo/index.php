<?php

use app\models\base\Artigo;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\ArtigoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Artigos';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = $this->title;

// Detecta status da aba ativa
$status = Yii::$app->request->get('status', 1);
?>

<div class="artigo-index">

    <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-journal-text fs-2"></i> <?= Html::encode($this->title) ?>
    </h1>

    <p class="mb-4">
        <?= Html::a('<i class="bi bi-plus-circle me-1"></i> Novo Artigo', ['create'], ['class' => 'btn btn-success shadow-sm']) ?>
    </p>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link <?= $status == 1 ? 'active' : '' ?>" href="<?= Url::to(['index', 'status' => 1]) ?>">
                <i class="bi bi-check-circle-fill me-1"></i> Ativos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status == 0 ? 'active' : '' ?>" href="<?= Url::to(['index', 'status' => 0]) ?>">
                <i class="bi bi-x-circle-fill me-1"></i> Inativos
            </a>
        </li>
    </ul>

    <?php
    // Aplica o filtro automaticamente ao dataProvider
    $searchModel->art_status = $status;
    $panelType = $status == 1 ? GridView::TYPE_SUCCESS : GridView::TYPE_DANGER;
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'hover' => true,
        'toggleData' => false,
        'pjax' => true,
        'rowOptions' => ['class' => 'align-middle'],
        'headerRowOptions' => ['class' => 'table-light text-center align-middle'],
        'filterRowOptions' => ['class' => 'table-light'],
        'containerOptions' => ['class' => 'table-responsive'],
        'tableOptions' => ['class' => 'table table-striped table-hover align-middle'],
        'containerOptions' => ['class' => 'table-responsive shadow-sm rounded'],
        'summary' => 'Mostrando <strong>{begin}-{end}</strong> de <strong>{totalCount}</strong> itens',
        'panel' => [
            'type' => $panelType,
            'heading' => '<i class="bi bi-table me-2"></i>Listagem de Artigos',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'art_descricao',
                'format' => 'raw',
                'value' => fn($model) => $model->art_descricao,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(
                    Artigo::find()
                        ->where(['art_status' => 1])
                        ->orderBy('id')
                        ->asArray()
                        ->all(),
                    'art_descricao',
                    'art_descricao'
                ),
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Artigos...'],
            ],
            [
                'attribute' => 'art_tipo',
                'format' => 'raw',
                'width' => '150px',
                'value' => function ($model) {
                    $tipo = $model->art_tipo;
                    switch (strtolower($tipo)) {
                        case 'valor':
                            $badgeClass = 'badge bg-info';
                            break;
                        case 'situação':
                            $badgeClass = 'badge bg-secondary';
                            break;
                        default:
                            $badgeClass = 'badge bg-warning text-dark';
                            break;
                    }
                    return "<span class='$badgeClass'>{$tipo}</span>";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(
                    Artigo::find()
                        ->select(['art_tipo'])
                        ->distinct()
                        ->where(['art_status' => 1])
                        ->orderBy('id')
                        ->asArray()
                        ->all(),
                    'art_tipo',
                    'art_tipo'
                ),
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Tipo...'],
            ],

            [
                'attribute' => 'art_homologacaousuario',
                'format' => 'raw',
                'width' => '150px',
                'value' => fn($model) => $model->art_homologacaousuario,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(
                    Artigo::find()
                        ->select(['art_homologacaousuario'])
                        ->distinct()
                        ->where(['art_status' => 1])
                        ->orderBy('id')
                        ->asArray()
                        ->all(),
                    'art_homologacaousuario',
                    'art_homologacaousuario'
                ),
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Colaborador...'],
            ],
            [
                'attribute' => 'art_homologacaodata',
                'format' => ['date', 'php:d/m/Y'],
                'hAlign' => 'center',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'art_homologacaodata',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ])
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'art_status',
                'vAlign' => 'middle',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {homologar}',
                'header' => 'Ações',
                'contentOptions' => [
                    'class' => 'text-center',
                    'width' => '90px',
                ],
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<i class="bi bi-pencil-square"></i>', $url, [
                            'class' => 'btn btn-outline-secondary btn-sm',
                            'title' => 'Editar',
                        ]);
                    },
                    'homologar' => function ($url) {
                        return Html::a('<i class="bi bi-patch-check-fill"></i>', $url, [
                            'class' => 'btn btn-outline-success btn-sm',
                            'title' => 'Homologar Artigo',
                            'data' => [
                                'confirm' => 'Tem certeza que deseja <b>HOMOLOGAR</b> esse item?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>