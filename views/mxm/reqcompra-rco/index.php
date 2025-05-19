<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Requisições de Compra';
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


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'RCO_NUMERO',
            'label' => 'Número',
            'value' => fn($model) => $model['requisicao']['RCO_NUMERO'] ?? '(n/d)',
        ],
        [
            'attribute' => 'RCO_DATA',
            'label' => 'Data',
            'value' => fn($model) => $model['requisicao']['RCO_DATA'] ?? '(n/d)',
        ],
        [
            'attribute' => 'RCO_EMPRESA',
            'label' => 'Empresa',
            'value' => fn($model) => $model['requisicao']['RCO_EMPRESA'] ?? '(n/d)',
        ],
        [
            'attribute' => 'RCO_TIPO',
            'label' => 'Tipo',
            'value' => fn($model) => $model['requisicao']['RCO_TIPO'] ?? '(n/d)',
        ],
        [
            'attribute' => 'RCO_REQUISITANTE',
            'label' => 'Requisitante',
            'value' => fn($model) => $model['requisicao']['RCO_REQUISITANTE'] ?? '(n/d)',
        ],
        [
            'attribute' => 'RCO_MOEDA',
            'label' => 'Moeda',
            'value' => fn($model) => $model['requisicao']['RCO_MOEDA'] ?? '(n/d)',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'urlCreator' => function ($action, $model, $key, $index) {
                return ['view', 'id' => $model['requisicao']['RCO_NUMERO']];
            },
        ],
    ],
]); ?>