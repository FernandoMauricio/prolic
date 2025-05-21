<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\cache\RequisicaoCache;
use yii\bootstrap5\Modal;

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

    <div class="card border-start border-4 border-primary mb-4 shadow-sm">
        <div class="card-header bg-light text-uppercase small fw-bold text-primary">
            <i class="bi bi-info-circle-fill me-2"></i> Detalhes da Requisição
        </div>
        <div class="card-body row row-cols-1 row-cols-md-2 g-4 align-items-start">
            <?php foreach (
                [
                    'Número' => $model->getNumero(),
                    'Data' => $model->getDataFormatada(),
                    'Tipo' =>  $model->get('RCO_TIPO'),
                    'Setor' => $model->get('RCO_SETOR'),
                    'Requisitante' => $model->getRequisitante(),
                    'Status (via API)' => Html::tag('span', '<span class="dot-bounce">Carregando<span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span></span>', [
                        'class' => 'badge bg-secondary px-3 py-2 requisicao-status',
                        'data-numero' => $model->getNumero(),
                        'style' => 'min-width: 130px; display: inline-block; text-align: center;',
                    ])
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

            <div class="col">
                <div class="border-bottom pb-2">
                    <div class="fw-semibold small text-muted d-flex justify-content-between">
                        Observação
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalObservacao" class="small text-decoration-none"><i class="bi bi-eye"></i> Visualizar completa</a>
                    </div>
                    <div class="text-dark text-truncate" style="max-width: 100%;">
                        <?= Html::encode(substr($model->get('RCO_OBS'), 0, 180)) ?>...
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="border-bottom pb-2">
                    <div class="fw-semibold small text-muted d-flex justify-content-between">
                        Justificativa
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalJustificativa" class="small text-decoration-none"><i class="bi bi-eye"></i> Visualizar completa</a>
                    </div>
                    <div class="text-dark text-truncate" style="max-width: 100%;">
                        <?= Html::encode(substr($model->get('RCO_JUSTIFICATIVA'), 0, 180)) ?>...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php Modal::begin([
        'id' => 'modalJustificativa',
        'title' => '<i class="bi bi-align-start"></i> Justificativa Completa',
        'size' => Modal::SIZE_LARGE,
        'options' => ['tabindex' => false],
    ]); ?>
    <p><?= nl2br(Html::encode($model->get('RCO_JUSTIFICATIVA'))) ?></p>
    <?php Modal::end(); ?>

    <?php Modal::begin([
        'id' => 'modalObservacao',
        'title' => '<i class="bi bi-align-start"></i> Observação Completa',
        'size' => Modal::SIZE_LARGE,
        'options' => ['tabindex' => false],
    ]); ?>
    <p><?= nl2br(Html::encode($model->get('RCO_OBS'))) ?></p>
    <?php Modal::end(); ?>

    <?php if (!empty($aprovacoesVisiveis)): ?>
        <div class="card border-start border-4 border-success mt-4 shadow-sm">
            <div class="card-header bg-light text-uppercase small fw-bold text-success">
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

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <span class="text-uppercase small fw-bold">
                <i class="bi bi-box-seam me-2"></i> Itens da Requisição (<?= count($model->itens) ?>)
            </span>
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
                        'contentOptions' => ['class' => 'text-end fw-bold text-success']
                    ],
                    [
                        'label' => 'Total',
                        'format' => ['currency'],
                        'value' => fn($item) => floatval($item['IRC_QTDPEDIDA']) * floatval($item['IRC_VALOR']),
                        'contentOptions' => ['class' => 'text-end fw-semibold']
                    ]
                ]
            ]) ?>
        </div>
        <div class="card-footer bg-light text-end fw-bold fs-6">
            Total Geral: <?= Yii::$app->formatter->asCurrency($total) ?>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
window.addEventListener('load', function() {
    const span = document.querySelector('.requisicao-status');
    if (!span) return;

    const numero = span.dataset.numero;
    const key = 'status-' + numero;

    // Se já tem em cache na sessão, usa direto
    if (sessionStorage.getItem(key)) {
        span.outerHTML = sessionStorage.getItem(key);
        return;
    }

    fetch('index.php?r=mxm/reqcompra-rco/status-requisicao-ajax&numero=' + numero)
        .then(response => response.json())
        .then(data => {
            if (data?.statusHtml) {
                sessionStorage.setItem(key, data.statusHtml);
                span.style.transition = 'opacity 0.3s ease';
                span.style.opacity = 0;
                setTimeout(() => {
                    span.outerHTML = data.statusHtml;
                }, 300);
            }
        })
        .catch(() => {
            span.outerHTML = '<span class="badge bg-danger px-3 py-2">Erro</span>';
        });
});
JS);

?>