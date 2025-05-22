<?php

use yii\helpers\Html;

$temRequisicoes = !empty($requisicoes);
$temFaltando = !empty($faltando);

?>

<div id="requisicoes-resumo" class="<?= $temRequisicoes || $temFaltando ? '' : 'd-none' ?>">

    <?php if ($temFaltando): ?>
        <div class="alert alert-danger d-flex align-items-start gap-2 small my-4 border-start border-4 border-danger-subtle shadow-sm">
            <i class="bi bi-x-octagon-fill fs-5 text-danger mt-1"></i>
            <div>
                <div class="fw-semibold mb-1">
                    Requisiç<?= count($faltando) > 1 ? 'ões' : 'ão' ?> não localizada<?= count($faltando) > 1 ? 's' : '' ?> no sistema:
                </div>
                <?= implode(', ', array_map(fn($n) =>
                Html::tag('span', Html::encode($n), [
                    'class' => 'badge bg-danger-subtle text-danger border border-danger fw-semibold me-1'
                ]), $faltando)) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($temRequisicoes): ?>
        <div class="list-group list-group-flush">
            <?php foreach ($requisicoes as $req): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong><?= Html::encode($req->getNumero()) ?></strong><br>
                            <small><?= Html::encode($req->getRequisitante()) ?> — <?= Html::encode($req->getDataFormatada()) ?></small>
                        </div>
                        <div>
                            <?= $req->getStatusBadgeHtml() ?>
                        </div>
                    </div>
                    <div class="mt-2 small text-muted"><?= Html::encode($req->get('RCO_JUSTIFICATIVA')) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (!$temFaltando): ?>
        <div class="text-muted text-center py-4">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            Nenhuma requisição vinculada.
        </div>
    <?php endif; ?>

</div>