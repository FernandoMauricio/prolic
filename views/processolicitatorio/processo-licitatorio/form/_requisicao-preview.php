<?php

/** @var array $dados */

use yii\bootstrap5\Html;

// Total geral (soma de quantidade × valor unitário de cada item)
$totalGeral = array_sum(array_map(function ($item) {
    return (float)($item['QuantidadePedida'] ?? 0) * (float)($item['ValorUnitario'] ?? 0);
}, $dados['itens'] ?? []));
?>

<div class="requisicao-card p-3 border rounded bg-light-subtle">
    <h5 class="mb-3 text-primary">
        <i class="bi bi-box-seam me-1"></i> Resumo da Requisição
    </h5>

    <div class="row mb-3">
        <div class="col-md-6"><strong>Número do Pedido:</strong> <?= Html::encode($dados['numero']) ?></div>
        <div class="col-md-6"><strong>Status:</strong> <span class="badge bg-secondary"><?= Html::encode($dados['status']) ?></span></div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Data do Pedido:</strong>
            <?php
            try {
                $dataObj = DateTime::createFromFormat('d/m/Y H:i:s', $dados['data']);
                echo $dataObj ? $dataObj->format('d/m/Y') : '<span class="text-danger">Data inválida</span>';
            } catch (Exception $e) {
                echo '<span class="text-danger">Erro ao formatar data</span>';
            }
            ?>
        </div>
    </div>
    <div class="col-md-6"><strong>Requisitante:</strong> <?= Html::encode($dados['requisitante']) ?></div>
</div>
<div class="row mb-3">
    <div class="col-md-12"><strong>Condição de Pagamento:</strong> <?= Html::encode($dados['condicaoPagamento']) ?></div>
</div>
<div class="row mb-4">
    <div class="col-md-12"><strong>Valor Total:</strong> <span class="text-success fw-semibold">R$ <?= number_format((float)$dados['valorTotal'], 2, ',', '.') ?></span></div>
</div>

<?php if (!empty($dados['itens'])): ?>
    <h6 class="text-dark fw-bold mb-2"><i class="bi bi-list-ul me-1"></i> Itens da Requisição</h6>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th class="text-nowrap text-center" style="width: 30px;">#</th>
                    <th class="text-start">Item</th>
                    <th class="text-nowrap text-end" style="width: 60px;">Qnt.</th>
                    <th class="text-nowrap text-end" style="width: 110px;">Valor Unitário</th>
                    <th class="text-nowrap text-end" style="width: 110px;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados['itens'] as $index => $item):
                    $quantidade = (float)($item['QuantidadePedida'] ?? 0);
                    $valorUnit = (float)($item['ValorUnitario'] ?? 0);
                    $totalItem = $quantidade * $valorUnit;
                ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td class="text-start"><?= Html::encode($item['Descricao'] ?? '-') ?></td>
                        <td class="text-end"><?= $quantidade ?></td>
                        <td class="text-end">R$ <?= number_format($valorUnit, 2, ',', '.') ?></td>
                        <td class="text-end fw-semibold">R$ <?= number_format($totalItem, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="table-warning fw-bold">
                    <td colspan="4" class="text-end">Total Geral:</td>
                    <td class="text-end">R$ <?= number_format($totalGeral, 2, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-warning mt-3">
        <i class="bi bi-exclamation-triangle me-1"></i> Nenhum item encontrado para esta requisição.
    </div>
<?php endif; ?>
</div>