<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\RecursosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recursos';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = $this->title;

// Detecta status da aba ativa
$status = Yii::$app->request->get('status', 1);
$searchModel->rec_status = $status;
$panelType = $status == 1 ? GridView::TYPE_SUCCESS : GridView::TYPE_DANGER;
?>

<div class="recursos-index">

    <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-cash-coin fs-2"></i> <?= Html::encode($this->title) ?>
    </h1>

    <p class="mb-4">
        <?= Html::a('<i class="bi bi-plus-circle me-1"></i> Novo Recurso', ['create'], ['class' => 'btn btn-success shadow-sm']) ?>
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

    <style>
        .kv-panel-before {
            display: none !important;
        }
    </style>
    <?php Pjax::begin(['id' => 'pjax-grid-recurso']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'hover'        => true,
        'striped'      => true,
        'condensed'    => true,
        'bordered'     => false,
        'responsiveWrap' => false,
        'summary'      => 'Mostrando <strong>{begin}-{end}</strong> de <strong>{totalCount}</strong> itens',
        'panel'        => [
            'type'    => $panelType,
            'heading' => '<i class="bi bi-table me-2"></i>Recursos',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'rec_descricao',
                'format'    => 'raw',
                'value'     => fn($model) => Html::encode($model->rec_descricao),
            ],

            [
                'attribute' => 'rec_status',
                'format' => 'raw',
                'label' => 'Ativo',
                'filter' => [
                    1 => 'Ativo',
                    0 => 'Inativo',
                ],
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model) {
                    $id = $model->id;
                    $switchId = "switch-status-recurso-$id";
                    return Html::tag(
                        'div',
                        Html::checkbox('status', $model->rec_status, [
                            'class' => 'form-check-input status-switch switch-lg',
                            'id' => $switchId,
                            'role' => 'switch',
                            'data-id' => $id,
                            'data-url' => Url::to(['base/recursos/toggle-status']),
                            'data-container' => '#pjax-grid-recurso',
                        ]) .
                            Html::label('', $switchId, ['class' => 'form-check-label']),
                        ['class' => 'form-check form-switch d-inline-block']
                    );
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'header' => 'Ações',
                'contentOptions' => ['class' => 'text-center', 'width' => '90px'],
                'buttons' => [
                    'update' => fn($url) => Html::a('<i class="bi bi-pencil-square"></i>', $url, [
                        'class' => 'btn btn-outline-secondary btn-sm',
                        'title' => 'Editar Recurso',
                    ]),
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>