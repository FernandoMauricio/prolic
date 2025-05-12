<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
use yii\widgets\Pjax;
use app\models\base\Modalidade;
use app\models\base\Ano;
use app\models\base\Ramo;

$this->title = 'Valor Limite por Modalidade';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = $this->title;

// Tabs - status da aba ativa
$status = Yii::$app->request->get('status', 1);
$searchModel->status = $status;
$panelType = $status == 1 ? GridView::TYPE_SUCCESS : GridView::TYPE_DANGER;
?>

<div class="modalidade-valorlimite-index">

    <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-graph-up-arrow"></i> <?= Html::encode($this->title) ?>
    </h1>

    <p class="mb-4">
        <?= Html::a('<i class="bi bi-plus-circle me-1"></i> Novo Valor Limite', ['create'], ['class' => 'btn btn-success shadow-sm']) ?>
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

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'hover' => true,
        'pjax' => true,
        'summary' => 'Mostrando <strong>{begin}-{end}</strong> de <strong>{totalCount}</strong> itens',
        'panel' => [
            'type' => $panelType,
            'heading' => '<i class="bi bi-table me-2"></i>Valores Limite',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'modalidade_id',
                'value' => fn($model) => $model->modalidade->mod_descricao,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Modalidade::find()->where(['mod_status' => 1])->all(), 'id', 'mod_descricao'),
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Modalidade...'],
            ],
            [
                'attribute' => 'ramo_id',
                'value' => fn($model) => $model->ramo->ram_descricao,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Ramo::find()->where(['ram_status' => 1])->all(), 'id', 'ram_descricao'),
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Segmento...'],
            ],
            [
                'attribute' => 'ano_id',
                'value' => fn($model) => $model->ano->an_ano,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Ano::find()->where(['an_status' => 1])->all(), 'id', 'an_ano'),
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Ano...'],
            ],
            [
                'attribute' => 'valor_limite',
                'format' => 'raw',
                'hAlign' => 'right',
                'value' => fn($model) => Html::tag(
                    'span',
                    \yii\helpers\StringHelper::truncate(Yii::$app->formatter->asCurrency($model->valor_limite), 15),
                    ['title' => Yii::$app->formatter->asCurrency($model->valor_limite)]
                ),
            ],
            'homologacao_usuario',
            [
                'attribute' => 'homologacao_data',
                'format' => ['date', 'php:d/m/Y'],
                'hAlign' => 'center',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'homologacao_data',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
                ]),
            ],
            [
                'attribute' => 'tipo',
                'format' => 'raw',
                'value' => fn($model) => Html::tag(
                    'span',
                    $model->tipo == 0 ? 'Limitado' : 'Ilimitado',
                    ['class' => 'badge ' . ($model->tipo == 0 ? 'bg-warning text-dark' : 'bg-success')]
                ),
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ['0' => 'Limitado', '1' => 'Ilimitado'],
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Tipo...'],
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'status',
                'vAlign' => 'middle',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {homologar}',
                'header' => 'Ações',
                'contentOptions' => ['class' => 'text-center', 'width' => '110px'],
                'buttons' => [
                    'update' => fn($url) =>
                    Html::a('<i class="bi bi-pencil-square"></i>', $url, [
                        'class' => 'btn btn-outline-secondary btn-sm',
                        'title' => 'Editar Valor',
                    ]),
                    'homologar' => fn($url) =>
                    Html::a('<i class="bi bi-patch-check-fill"></i>', $url, [
                        'class' => 'btn btn-outline-success btn-sm',
                        'title' => 'Homologar Valor',
                        'data' => [
                            'confirm' => 'Você deseja <b>homologar</b> este valor limite?',
                            'method' => 'post',
                        ],
                    ]),
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>