<?php

/** @var array $dados */
?>
<style>
    .requisicao-card {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 16px;
        background-color: #f9f9f9;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .requisicao-card h4,
    .requisicao-card h5 {
        margin-top: 0;
        font-weight: 600;
    }

    .requisicao-card table {
        margin-top: 10px;
        background-color: white;
    }

    .requisicao-feedback {
        padding: 8px 12px;
        margin-bottom: 15px;
        border-left: 4px solid #4caf50;
        background-color: #e8f5e9;
        color: #2e7d32;
        border-radius: 4px;
        font-size: 14px;
    }
</style>

<div class="requisicao-card">
    <h4>Resumo da Requisição</h4>
    <p><strong>Empresa:</strong> <?= $dados['empresa'] ?></p>
    <p><strong>Número do Pedido:</strong> <?= $dados['numero'] ?></p>
    <p><strong>Data do Pedido:</strong> <?= $dados['data'] ?></p>
    <p><strong>Requisitante:</strong> <?= $dados['requisitante'] ?></p>
    <p><strong>Condição de Pagamento:</strong> <?= $dados['condicaoPagamento'] ?></p>
    <p><strong>Status:</strong> <?= $dados['status'] ?></p>
    <p><strong>Valor Total:</strong> R$ <?= number_format((float)$dados['valorTotal'], 2, ',', '.') ?></p>

    <?php if (!empty($dados['itens'])): ?>
        <h5>Itens da Requisição</h5>
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados['itens'] as $item): ?>
                    <tr>
                        <td><?= $item['Descricao'] ?? '-' ?></td>
                        <td><?= $item['QuantidadePedida'] ?? '-' ?></td>
                        <td>R$ <?= number_format((float)($item['ValorUnitario'] ?? 0), 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><em>Nenhum item encontrado.</em></p>
    <?php endif; ?>
</div>