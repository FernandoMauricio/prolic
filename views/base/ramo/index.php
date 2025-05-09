<?php

use app\models\base\Ramo;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\RamoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Segmentos';
$this->params['breadcrumbs'][] = $this->title;

// Detecta status da aba ativa
$status = Yii::$app->request->get('status', 1);
$searchModel->ram_status = $status;
$panelType = $status == 1 ? GridView::TYPE_SUCCESS : GridView::TYPE_DANGER;
?>

<div class="ramo-index">

    <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-diagram-3 fs-2"></i> <?= Html::encode($this->title) ?>
    </h1>

    <p class="mb-4">
        <?= Html::a('<i class="bi bi-plus-circle me-1"></i> Novo Segmento', ['create'], ['class' => 'btn btn-success shadow-sm']) ?>
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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'hover' => true,
        'striped' => true,
        'condensed' => true,
        'bordered' => false,
        'responsiveWrap' => false,
        'summary' => 'Mostrando <strong>{begin}-{end}</strong> de <strong>{totalCount}</strong> itens',
        'panel' => [
            'type' => $panelType,
            'heading' => '<i class="bi bi-table me-2"></i>Listagem de Segmentos',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'ram_descricao',
                'format' => 'raw',
                'value' => fn($model) => Html::encode($model->ram_descricao),
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'ram_status',
                'vAlign' => 'middle',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'header' => 'Ações',
                'contentOptions' => ['class' => 'text-center', 'width' => '90px'],
                'buttons' => [
                    'update' => fn($url) => Html::a(
                        '<i class="bi bi-pencil-square"></i>',
                        $url,
                        [
                            'class' => 'btn btn-outline-secondary btn-sm',
                            'title' => 'Editar Segmento',
                        ]
                    ),
                ],
            ],
        ],
    ]); ?>
</div>