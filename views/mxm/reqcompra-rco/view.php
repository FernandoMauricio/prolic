<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\RequisicaoCache;

/** @var yii\web\View $this */
/** @var RequisicaoCache $model */

$this->title = 'Requisição nº ' . $model->getNumero();

$this->params['breadcrumbs'][] = ['label' => 'Requisições', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$itensProvider = new ArrayDataProvider([
    'allModels' => $model->getItens(),
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
                    [
                        'label' => 'Número',
                        'value' => $model->getNumero(),
                    ],
                    [
                        'label' => 'Data',
                        'value' => $model->getDataFormatada(),
                    ],
                    [
                        'label' => 'Empresa',
                        'value' => $model->get('RCO_EMPRESA'),
                    ],
                    [
                        'label' => 'Tipo',
                        'value' => $model->get('RCO_TIPO'),
                    ],
                    [
                        'label' => 'Setor',
                        'value' => $model->get('RCO_SETOR'),
                    ],
                    [
                        'label' => 'Requisitante',
                        'value' => $model->getRequisitante(),
                    ],
                    [
                        'label' => 'Observação',
                        'value' => $model->get('RCO_OBS'),
                    ],
                    [
                        'label' => 'Justificativa',
                        'value' => $model->get('RCO_JUSTIFICATIVA'),
                    ],
                    [
                        'label' => 'Data Limite Recebimento',
                        'value' => function () use ($model) {
                            $data = $model->get('RCO_DTLIMITERECEBIMENTOITENS');
                            return $data ? Yii::$app->formatter->asDate($data, 'php:d/m/Y') : '(não definida)';
                        },
                    ],
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
                'dataProvider' => $itensProvider,
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
            ]) ?>
        </div>
    </div>

</div>