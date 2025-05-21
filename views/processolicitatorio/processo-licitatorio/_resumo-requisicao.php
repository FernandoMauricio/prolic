<?php

use yii\helpers\Html;

if (empty($requisicoes)): ?>
    <div class="text-muted text-center py-4">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        Nenhuma requisição vinculada.
    </div>
<?php else: ?>
    <div class="list-group list-group-flush">
        <?php foreach ($requisicoes as $req): ?>
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong><?= Html::encode($req->getNumero()) ?></strong><br>
                        <small><?= Html::encode($req->getRequisitante()) ?> — <?= Html::encode($req->getDataFormatada()) ?></small>
                    </div>
                    <div>
                        <?= $req->getStatusBadge() ?>
                    </div>
                </div>
                <div class="mt-2 small text-muted"><?= Html::encode($req->get('RCO_JUSTIFICATIVA')) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>