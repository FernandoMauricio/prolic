<?php

use app\models\base\Ano;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\AnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cadastro de Anos';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = $this->title;

// Detecta status da aba ativa
$status = Yii::$app->request->get('status', 1);
$searchModel->an_status = $status;
$panelType = $status == 1 ? GridView::TYPE_SUCCESS : GridView::TYPE_DANGER;
?>

<div class="ano-index">

    <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-calendar-range fs-2"></i> <?= Html::encode($this->title) ?>
    </h1>

    <p class="mb-4">
        <?= Html::a('<i class="bi bi-plus-circle me-1"></i> Novo Ano', ['create'], ['class' => 'btn btn-success shadow-sm']) ?>
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
    <?php Pjax::begin(['id' => 'pjax-grid-ano']); ?>
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
            'heading' => '<i class="bi bi-table me-2"></i>Anos',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'an_ano',
                'format' => 'raw',
                'value' => fn($model) => Html::encode($model->an_ano),
            ],
            [
                'attribute' => 'an_status',
                'format' => 'raw',
                'label' => 'Ativo',
                'filter' => [
                    1 => 'Ativo',
                    0 => 'Inativo',
                ],
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model) {
                    $id = $model->id;
                    $switchId = "switch-status-ano-$id";
                    return Html::tag(
                        'div',
                        Html::checkbox('status', $model->an_status, [
                            'class' => 'form-check-input status-switch',
                            'id' => $switchId,
                            'role' => 'switch',
                            'data-id' => $id,
                            'data-url' => Url::to(['base/ano/toggle-status']),
                            'data-container' => '#pjax-grid-ano',
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
                        'title' => 'Editar',
                    ]),
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>