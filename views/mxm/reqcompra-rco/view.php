<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\cache\RequisicaoCache;

$this->title = 'Requisição';
$this->params['breadcrumbs'][] = ['label' => 'Consulta das Requisições', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$itensProvider = new ArrayDataProvider([
    'allModels' => $model->itens,
    'pagination' => false,
]);

$aprovacoesUnicas = [];
$chaves = [];
foreach ($aprovacoes as $aprov) {
    $chave = ($aprov['ordem'] ?? '-') . '|' . ($aprov['aprovador'] ?? '');
    if (!in_array($chave, $chaves)) {
        $chaves[] = $chave;
        $aprovacoesUnicas[] = $aprov;
    }
}

$aprovacoesVisiveis = array_filter($aprovacoesUnicas, function ($aprov) {
    return !(
        ($aprov['aprovador'] ?? '') === '(não atribuído)' &&
        empty($aprov['data_aprovacao']) &&
        ($aprov['status'] ?? '') === 'Pendente'
    );
});

// Calcular total geral
$total = 0;
foreach ($model->itens as $item) {
    $qtd = floatval($item['IRC_QTDPEDIDA'] ?? 0);
    $preco = floatval($item['IRC_VALOR'] ?? 0);
    $total += $qtd * $preco;
}
function getStatusIcon($status)
{
    if ($status === 'Aprovado') return '<i class="bi bi-check-circle-fill me-1"></i>';
    if ($status === 'Reprovado') return '<i class="bi bi-x-circle-fill me-1"></i>';
    if ($status === 'Cancelado') return '<i class="bi bi-slash-circle-fill me-1"></i>';
    return '<i class="bi bi-hourglass-split me-1"></i>';
}
?>

<div class="reqcompra-rco-view container-xxl">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary d-flex align-items-center gap-2">
            <i class="bi bi-clipboard-check-fill"></i> Requisição
            <span class="badge bg-primary-subtle border border-primary text-primary px-3"><?= Html::encode($model->getNumero()) ?></span>
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
                    'Status (via API)' => $model->getStatusBadge(),
                    'Observação' => $model->get('RCO_OBS'),
                    'Justificativa' => $model->get('RCO_JUSTIFICATIVA'),
                ] as $label => $value
            ): ?>
                <div class="col">
                    <div class="border-bottom pb-2">
                        <div class="fw-semibold small text-muted"><?= $label ?></div>
                        <div class="text-dark">
                            <?php if (strip_tags($value) !== $value): ?>
                                <?= $value ?>
                            <?php else: ?>
                                <?= Html::encode($value) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Aprovadores -->
    <?php if (!empty($aprovacoesVisiveis)): ?>
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
                        <?php foreach ($aprovacoesVisiveis as $aprov): ?>
                            <?php
                            $status = $aprov['status'] ?? 'Desconhecido';
                            $nome = $aprov['aprovador'] ?? '(não atribuído)';
                            $data = $aprov['data_aprovacao'] ?? null;
                            $justificativa = $aprov['justificativa'] ?? '-';
                            $ordem = $aprov['ordem'] ?? '-';

                            $badgeClass = 'bg-secondary';
                            if ($status === 'Aprovado') $badgeClass = 'bg-success';
                            elseif ($status === 'Reprovado') $badgeClass = 'bg-danger';
                            elseif ($status === 'Cancelado') $badgeClass = 'bg-dark';

                            $icon = getStatusIcon($status);
                            ?>
                            <tr>
                                <td class="text-center fw-bold"><?= Html::encode($ordem) ?>º</td>
                                <td class="fw-semibold"><?= Html::encode($nome) ?></td>
                                <td class="text-center">
                                    <?= $data ? Yii::$app->formatter->asDate($data, 'php:d/m/Y') : '-' ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $badgeClass ?> px-3 py-2 fs-6">
                                        <?= $icon . Html::encode($status) ?>
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
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-box-seam me-2"></i> Itens da Requisição</span>
            <?= Html::a(
                '<i class="bi bi-file-earmark-excel"></i> Exportar Itens',
                ['exportar-itens', 'id' => $model->getNumero()],
                ['class' => 'btn btn-outline-light btn-sm']
            ) ?>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $itensProvider,
                'summary' => false,
                'tableOptions' => ['class' => 'table table-sm table-hover table-striped table-bordered align-middle mb-0'],
                'columns' => [
                    ['class' => 'yii\\grid\\SerialColumn'],
                    ['attribute' => 'IRC_ITEM', 'label' => 'Item'],
                    ['attribute' => 'IRC_DESCRICAO', 'label' => 'Descrição'],
                    ['attribute' => 'IRC_UNIDADE', 'label' => 'UN'],
                    [
                        'attribute' => 'IRC_QTDPEDIDA',
                        'label' => 'QTD<br>Pedida',
                        'encodeLabel' => false,
                        'format' => ['decimal', 2],
                        'contentOptions' => ['class' => 'text-end']
                    ],
                    [
                        'attribute' => 'IRC_VALOR',
                        'label' => 'Preço',
                        'format' => ['currency'],
                        'contentOptions' => ['class' => 'text-end']
                    ],
                    [
                        'label' => 'Total',
                        'format' => ['currency'],
                        'value' => fn($item) => floatval($item['IRC_QTDPEDIDA']) * floatval($item['IRC_VALOR']),
                        'contentOptions' => ['class' => 'text-end']
                    ]
                ]
            ]) ?>
        </div>
        <div class="card-footer bg-light text-end fw-semibold">
            Total Geral: <?= Yii::$app->formatter->asCurrency($total) ?>
        </div>
    </div>
</div>