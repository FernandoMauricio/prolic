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

    <!-- Detalhes da Requisição -->
    <div class="card border-start border-4 border-primary mb-4 shadow-sm">
        <div class="card-header bg-light fw-semibold text-primary">
            <i class="bi bi-info-circle-fill me-2"></i> Detalhes da Requisição
        </div>
        <div class="card-body row row-cols-1 row-cols-md-2 g-3">
            <?php foreach (
                [
                    'Número' => $model->getNumero(),
                    'Data' => $model->getDataFormatada(),
                    'Tipo' =>  $model->get('RCO_TIPO'),
                    'Setor' => $model->get('RCO_SETOR'),
                    'Requisitante' => $model->getRequisitante(),
                    'Observação' => $model->get('RCO_OBS'),
                    'Justificativa' => $model->get('RCO_JUSTIFICATIVA'),
                ] as $label => $value
            ): ?>
                <div class="col">
                    <div class="border-bottom pb-2">
                        <div class="fw-semibold small text-muted"><?= $label ?></div>
                        <div class="text-dark">
                            <?= is_string($value) ? Html::encode($value) : $value ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Aprovadores -->
    <?php if (!empty($aprovacoes)): ?>
        <div class="card border-start border-4 border-success mt-4 shadow-sm">
            <div class="card-header bg-light fw-semibold text-success">
                <i class="bi bi-people-fill me-2"></i> Aprovadores da Requisição
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-bordered align-middle mb-0">
                    <thead class="table-success text-dark">
                        <tr class="text-center">
                            <th>Ordem</th>
                            <th>Aprovador</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Justificativa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aprovacoes as $aprov): ?>
                            <?php
                            $status = $aprov['status'] ?? 'Desconhecido';
                            $nome = $aprov['aprovador'] ?? '(não atribuído)';
                            $data = $aprov['data_aprovacao'] ?? null;
                            $justificativa = $aprov['justificativa'] ?? '-';
                            $ordem = $aprov['nivel'] ?? $aprov['ordem'] ?? '-';

                            $badgeClass = 'bg-secondary';
                            if ($status === 'Aprovado') $badgeClass = 'bg-success';
                            elseif ($status === 'Reprovado') $badgeClass = 'bg-danger';
                            elseif ($status === 'Cancelado') $badgeClass = 'bg-dark';
                            ?>
                            <tr>
                                <td class="text-center fw-bold"><?= Html::encode($ordem) ?>º</td>
                                <td class="fw-semibold"><?= Html::encode($nome) ?></td>
                                <td class="text-center">
                                    <?= $data ? Yii::$app->formatter->asDate($data, 'php:d/m/Y') : '-' ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $badgeClass ?> px-3 py-2 fs-6">
                                        <?= Html::encode($status) ?>
                                    </span>
                                </td>
                                <td><?= Html::encode($justificativa) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Itens da Requisição -->
    <div class="card shadow-sm mt-4">
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
                    ['attribute' => 'IPC_ITEM', 'label' => 'Item'],
                    ['attribute' => 'IPC_DESCRICAO', 'label' => 'Descrição'],
                    ['attribute' => 'IPC_TXESPTECNICAMAPA', 'label' => 'Especificação Técnica'],
                    ['attribute' => 'IPC_UNIDADE', 'label' => 'UN'],
                    [
                        'attribute' => 'IPC_QTD',
                        'label' => 'QTD<br>Pedida',
                        'encodeLabel' => false,
                        'format' => ['decimal', 2],
                        'contentOptions' => ['class' => 'text-end']
                    ],
                    [
                        'attribute' => 'IPC_QTDATEND',
                        'label' => 'QTD<br>Atendida',
                        'encodeLabel' => false,
                        'format' => ['decimal', 2],
                        'contentOptions' => ['class' => 'text-end']
                    ],
                    ['attribute' => 'IPC_PRECO', 'label' => 'Preço', 'format' => ['currency'], 'contentOptions' => ['class' => 'text-end']],
                    ['attribute' => 'IPC_VLDESCONTO', 'label' => 'Desconto', 'format' => ['currency'], 'contentOptions' => ['class' => 'text-end']],
                    ['attribute' => 'IPC_PERCDESC', 'label' => '% Desconto', 'value' => fn($item) => $item['IPC_PERCDESC'] . '%', 'contentOptions' => ['class' => 'text-end']],
                    ['attribute' => 'IPC_PRECOSEMIMP', 'label' => 'Preço <br>s/ Impostos', 'encodeLabel' => false, 'format' => ['currency'], 'contentOptions' => ['class' => 'text-end']],
                    ['attribute' => 'IPC_DTPARAENT', 'label' => 'Entrega <br> Prevista', 'encodeLabel' => false, 'format' => ['date', 'php:d/m/Y']],
                ],
            ]) ?>
        </div>
    </div>
</div>