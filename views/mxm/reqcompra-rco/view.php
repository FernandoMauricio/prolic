<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\cache\RequisicaoCache;

$this->title = 'Requisição nº ' . $model->getNumero();
$this->params['breadcrumbs'][] = ['label' => 'Consulta das Requisições', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$itensProvider = new ArrayDataProvider([
    'allModels' => $model->itens,
    'pagination' => false,
]);
?>

<div class="reqcompra-rco-view container-xxl">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-clipboard-check-fill me-2"></i> <?= Html::encode($this->title) ?>
        </h1>
        <?= Html::a('<i class="bi bi-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <div class="card border-start border-4 border-primary mb-4 shadow-sm">
        <div class="card-header bg-light fw-semibold text-primary">
            <i class="bi bi-info-circle-fill me-2"></i> Detalhes da Requisição
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-bordered table-striped table-sm'],
                'attributes' => [
                    ['label' => 'Número', 'value' => $model->getNumero()],
                    ['label' => 'Data', 'value' => $model->getDataFormatada()],
                    ['label' => 'Empresa', 'value' => $model->get('RCO_EMPRESA')],
                    ['label' => 'Tipo', 'value' => Html::tag('span', $model->get('RCO_TIPO'), [
                        'class' => 'badge bg-info text-dark',
                    ]), 'format' => 'raw'],
                    ['label' => 'Setor', 'value' => $model->get('RCO_SETOR')],
                    ['label' => 'Requisitante', 'value' => $model->getRequisitante()],
                    ['label' => 'Observação', 'value' => $model->get('RCO_OBS')],
                    ['label' => 'Justificativa', 'value' => $model->get('RCO_JUSTIFICATIVA')],
                    [
                        'label' => 'Data Limite Recebimento',
                        'value' => $model->get('RCO_DTLIMITERECEBIMENTOITENS')
                            ? Yii::$app->formatter->asDate($model->get('RCO_DTLIMITERECEBIMENTOITENS'), 'php:d/m/Y')
                            : '(não definida)',
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-box-seam me-2"></i> Itens da Requisição
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $itensProvider,
                'summary' => false,
                'tableOptions' => ['class' => 'table table-hover table-striped table-bordered align-middle mb-0'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'IPC_ITEM',
                        'label' => 'Item',
                    ],
                    [
                        'attribute' => 'IPC_DESCRICAO',
                        'label' => 'Descrição',
                    ],
                    [
                        'attribute' => 'IPC_TXESPTECNICAMAPA',
                        'label' => 'Especificação Técnica',
                    ],
                    [
                        'attribute' => 'IPC_UNIDADE',
                        'label' => 'UN',
                    ],
                    [
                        'attribute' => 'IPC_QTD',
                        'label' => 'QTD<br>Pedida',
                        'encodeLabel' => false,
                        'format' => ['decimal', 2],
                        'contentOptions' => ['class' => 'text-end'],
                    ],
                    [
                        'attribute' => 'IPC_QTDATEND',
                        'label' => 'QTD<br>Atendida',
                        'encodeLabel' => false,
                        'format' => ['decimal', 2],
                        'contentOptions' => ['class' => 'text-end'],
                    ],
                    [
                        'attribute' => 'IPC_PRECO',
                        'label' => 'Preço',
                        'format' => ['currency'],
                        'contentOptions' => ['class' => 'text-end'],
                    ],
                    [
                        'attribute' => 'IPC_VLDESCONTO',
                        'label' => 'Desconto',
                        'format' => ['currency'],
                        'contentOptions' => ['class' => 'text-end'],
                    ],
                    [
                        'attribute' => 'IPC_PERCDESC',
                        'label' => '% Desconto',
                        'value' => fn($item) => $item['IPC_PERCDESC'] . '%',
                        'contentOptions' => ['class' => 'text-end'],
                    ],
                    [
                        'attribute' => 'IPC_PRECOSEMIMP',
                        'label' => 'Preço <br>s/ Impostos',
                        'encodeLabel' => false,
                        'format' => ['currency'],
                        'contentOptions' => ['class' => 'text-end'],
                    ],
                    [
                        'attribute' => 'IPC_DTPARAENT',
                        'label' => 'Entrega <br> Prevista',
                        'encodeLabel' => false,
                        'format' => ['date', 'php:d/m/Y'],
                    ],
                    [
                        'attribute' => 'IPC_MAPA',
                        'label' => 'Mapa',
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>