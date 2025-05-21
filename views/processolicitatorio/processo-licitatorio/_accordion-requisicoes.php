<?php
// views/processolicitatorio/_accordion-requisicoes.php

use yii\helpers\Html;

?>

<?php if (!empty($requisicoes)): ?>
    <div id="requisicoes-detalhadas">
        <div class="accordion" id="accordionPreview">
            <?php foreach ($requisicoes as $i => $req): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-<?= $i ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-<?= $i ?>" aria-expanded="false"
                            aria-controls="collapse-<?= $i ?>">
                            Requisição nº <?= Html::encode($req->getNumero()) ?>
                            <span class="ms-3"><?= $req->getStatusBadge() ?></span>
                        </button>
                    </h2>
                    <div id="collapse-<?= $i ?>" class="accordion-collapse collapse"
                        aria-labelledby="heading-<?= $i ?>" data-bs-parent="#accordionPreview">
                        <div class="accordion-body">
                            <strong>Data:</strong> <?= $req->getDataFormatada() ?><br>
                            <strong>Requisitante:</strong> <?= Html::encode($req->getRequisitante()) ?><br>
                            <strong>Justificativa:</strong><br>
                            <div class="text-muted"><?= nl2br(Html::encode($req->get('RCO_JUSTIFICATIVA'))) ?></div>

                            <hr class="my-2">

                            <strong>Itens:</strong>
                            <ul class="list-group list-group-flush mt-2">
                                <?php foreach ($req->getItens() as $item): ?>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div><?= Html::encode($item['IRC_DESCRICAO']) ?></div>
                                        <div class="text-end">
                                            QTD: <?= $item['IRC_QTDPEDIDA'] ?> —
                                            R$ <?= Yii::$app->formatter->asDecimal($item['IRC_VALOR'], 2) ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <div class="text-muted text-center py-4">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        Nenhuma requisição vinculada.
    </div>
<?php endif; ?>

<div id="requisicoes-resumo" class="d-none">
    <?= $this->render('_resumo-requisicao', ['requisicoes' => $requisicoes]) ?>
</div>

<div class="text-end mt-3">
    <button id="toggleRequisicoes" type="button" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-list"></i> Ver resumo
    </button>
</div>

<?php
$this->registerJs(<<<JS
    const toggleBtn = document.getElementById('toggleRequisicoes');
    const detalhadas = document.getElementById('requisicoes-detalhadas');
    const resumo = document.getElementById('requisicoes-resumo');

    toggleBtn.addEventListener('click', () => {
        const mostrandoResumo = !resumo.classList.contains('d-none');

        resumo.classList.toggle('d-none');
        detalhadas.classList.toggle('d-none');

        toggleBtn.innerHTML = mostrandoResumo
            ? '<i class="bi bi-list"></i> Ver resumo'
            : '<i class="bi bi-card-text"></i> Ver detalhes';
    });
JS);
?>