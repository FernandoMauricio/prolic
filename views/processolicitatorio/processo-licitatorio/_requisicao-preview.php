<?php

/** @var array $dados */
?>

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