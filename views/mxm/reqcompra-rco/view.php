<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\helpers\UtfHelper;


/* @var $this yii\web\View */
/* @var $model app\models\mxm\ReqcompraRco */
/* @var $itens array */

$this->title = 'Requisição nº ' . $model->RCO_NUMERO;
$this->params['breadcrumbs'][] = ['label' => 'Requisições', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$provider = new ArrayDataProvider([
    'allModels' => $itens,
    'pagination' => false,
]);
?>

<div class="reqcompra-rco-view">

    <h1 class="fw-bold"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-info-circle-fill me-1"></i> Detalhes da Requisição
        </div>
        <div class="card-body p-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'RCO_NUMERO',
                    'RCO_DATA:date',
                    'RCO_EMPRESA',
                    'RCO_TIPO',
                    'RCO_SETOR',
                    'RCO_REQUISITANTE',
                    'RCO_OBS',
                    'RCO_JUSTIFICATIVA',
                    'RCO_DTLIMITERECEBIMENTOITENS:date',
                ],
            ]) ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-box-seam me-1"></i> Itens da Requisição
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $provider,
                'summary' => false,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'IPC_NUMITEM',
                    'IPC_ITEM',
                    'IPC_DESCRICAO',
                    'IPC_UNIDADE',
                    'IPC_QTD',
                    'IPC_PRECO',
                    'IPC_VLDESCONTO',
                    'IPC_PERCDESC',
                    'IPC_PRECOSEMIMP',
                    [
                        'attribute' => 'IPC_DTPARAENT',
                        'format' => ['date', 'php:d/m/Y'],
                        'label' => 'Entrega Prevista'
                    ],
                    'IPC_OBS:ntext',
                ],
            ]); ?>
        </div>
    </div>

</div>