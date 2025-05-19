<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\cache\RequisicaoCache;

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
            'value' => fn($model) => $model->getNumero(),
        ],
        [
            'label' => 'Data',
            'value' => fn($model) => $model->getDataFormatada(),
        ],
        [
            'label' => 'Empresa',
            'value' => fn($model) => $model->get('RCO_EMPRESA'),
        ],
        [
            'label' => 'Tipo',
            'value' => fn($model) => $model->get('RCO_TIPO'),
        ],
        [
            'label' => 'Requisitante',
            'value' => fn($model) => $model->getRequisitante(),
        ],
        [
            'label' => 'Moeda',
            'value' => fn($model) => $model->get('RCO_MOEDA'),
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'urlCreator' => fn($action, $model, $key, $index) => ['view', 'id' => $model->getNumero()],
        ],
    ],
]) ?>