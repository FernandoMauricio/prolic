<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\cache\RequisicaoCache;
use yii\widgets\Pjax;

$this->title = 'Consulta das Requisições';

$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="mb-3">
    <?= Html::beginForm(['index'], 'get') ?>
    <div class="input-group">
        <?= Html::textInput('q', $searchTerm ?? '', ['class' => 'form-control', 'placeholder' => 'Buscar por número ou requisitante...']) ?>
        <button class="btn btn-primary" type="submit">Buscar</button>
    </div>
    <?= Html::endForm() ?>
</div>

<?php Pjax::begin(['id' => 'grid-requisicoes', 'timeout' => 5000, 'enablePushState' => false]); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'RCO_NUMERO',
            'label' => 'Número',
            'value' => fn($model) => $model->getNumero(),
        ],
        [
            'label' => 'Data',
            'value' => fn($model) => $model->getDataFormatada(),
        ],
        [
            'label' => 'Tipo',
            'value' => fn($model) => $model->get('RCO_TIPO'),
        ],
        [
            'label' => 'Setor',
            'value' => fn($model) => $model->get('RCO_SETOR'),
        ],
        [
            'label' => 'Requisitante',
            'value' => fn($model) => $model->getRequisitante('RCO_REQUISITANTE'),
        ],
        [
            'label' => 'Dt Movimentação',
            'value' => fn($model) => $model->getDataMovFormatada('RCO_DTMOV'),
        ],
        [
            'label' => 'Justificativa',
            'value' => fn($model) => $model->get('RCO_JUSTIFICATIVA'),
        ],


        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'header' => 'Ações',
            'contentOptions' => ['class' => 'text-center', 'width' => '170px'],
            'urlCreator' => fn($action, $model, $key, $index) => ['view', 'id' => $model->getNumero()],
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<i class="bi bi-eye-fill"></i>', ['view', 'id' => $model->getNumero()], [
                        'class' => 'btn btn-outline-primary btn-sm',
                        'title' => 'Visualizar Requisição',
                        'data-pjax' => '0',
                    ]);
                },
            ],
        ],
    ],
]) ?>
<?php Pjax::end(); ?>