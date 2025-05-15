<?php

use yii\bootstrap5\Html;

/** @var array $dados */

// Total geral com base na quantidade atendida
$totalGeral = array_sum(array_map(function ($item) {
    $valor = (float) str_replace(',', '.', $item['ValorUnitario'] ?? 0);
    $quantidade = (float)($item['QuantidadeAtendida'] ?? 0);
    return $quantidade * $valor;
}, $dados['itens'] ?? []));
?>

<div class="requisicao-card p-3 border rounded bg-light-subtle">
    <h5 class="mb-3 text-primary">
        <i class="bi bi-box-seam me-1"></i> Resumo da Requisição
    </h5>

    <div class="row mb-3">
        <div class="col-md-6"><strong>Número do Pedido:</strong> <?= Html::encode($dados['numero']) ?></div>
        <div class="col-md-6">
            <strong>Status:</strong>
            <span class="badge bg-secondary"><?= Html::encode($dados['status']) ?></span>
        </div>
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
        <div class="col-md-6"><strong>Requisitante:</strong> <?= Html::encode($dados['requisitante']) ?></div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12"><strong>Condição de Pagamento:</strong> <?= Html::encode($dados['condicaoPagamento']) ?></div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <strong>Valor Total (MXM):</strong>
            <span class="text-success fw-semibold">R$ <?= number_format((float)$dados['valorTotal'], 2, ',', '.') ?></span>
        </div>
    </div>

    <?php if (!empty($dados['itens'])): ?>
        <h6 class="text-dark fw-bold mb-2"><i class="bi bi-list-ul me-1"></i> Itens da Requisição</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th style="width: 30px;">#</th>
                        <th style="width: 40px;">Cód.</th>
                        <th style="width: 30px;"><i class="bi bi-info-circle"></i></th>
                        <th class="text-start">Item</th>
                        <th class="text-end" style="width: 60px;">Qnt. Pedida</th>
                        <th class="text-end" style="width: 70px;">Qnt. Atendida</th>
                        <th class="text-end" style="width: 90px;">Valor Unitário</th>
                        <th class="text-end" style="width: 110px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dados['itens'] as $index => $item):
                        $quantidadePedida = (float)($item['QuantidadePedida'] ?? 0);
                        $quantidadeAtendida = (float)($item['QuantidadeAtendida'] ?? 0);
                        $valorUnit = (float) str_replace(',', '.', $item['ValorUnitario'] ?? 0);
                        $totalItem = $quantidadeAtendida * $valorUnit;

                        // Definição de ícone e cor de status
                        if ($quantidadeAtendida == 0) {
                            $icone = 'bi-x-circle-fill';
                            $cor = 'text-danger';
                            $title = 'Não atendido';
                            $rowClass = 'table-danger';
                        } elseif ($quantidadeAtendida < $quantidadePedida) {
                            $icone = 'bi-exclamation-triangle-fill';
                            $cor = 'text-warning';
                            $title = 'Parcialmente atendido';
                            $rowClass = 'table-warning';
                        } else {
                            $icone = 'bi-check-circle-fill';
                            $cor = 'text-success';
                            $title = 'Totalmente atendido';
                            $rowClass = '';
                        }
                    ?>
                        <tr class="<?= $rowClass ?>">
                            <td><?= $index + 1 ?></td>
                            <td><?= Html::encode($item['CodigoItem'] ?? '-') ?></td>
                            <td class="text-center">
                                <i class="bi <?= $icone ?> <?= $cor ?>" title="<?= $title ?>"></i>
                            </td>
                            <td class="text-start"><?= Html::encode($item['Descricao'] ?? '-') ?></td>
                            <td class="text-end"><?= number_format($quantidadePedida, 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format($quantidadeAtendida, 0, ',', '.') ?></td>
                            <td class="text-end">R$ <?= number_format($valorUnit, 2, ',', '.') ?></td>
                            <td class="text-end fw-semibold">R$ <?= number_format($totalItem, 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-warning fw-bold">
                        <td colspan="7" class="text-end">Total Geral (Qtde Atendida × Valor Unit.):</td>
                        <td class="text-end">R$ <?= number_format($totalGeral, 2, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3">
                <h6 class="text-muted fw-bold">Legenda</h6>
                <div class="d-flex align-items-center gap-4 small text-muted">
                    <div><i class="bi bi-check-circle-fill text-success me-1"></i> Totalmente atendido</div>
                    <div><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Parcialmente atendido</div>
                    <div><i class="bi bi-x-circle-fill text-danger me-1"></i> Não atendido</div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-3">
            <i class="bi bi-exclamation-triangle me-1"></i> Nenhum item encontrado para esta requisição.
        </div>
    <?php endif; ?>
</div>