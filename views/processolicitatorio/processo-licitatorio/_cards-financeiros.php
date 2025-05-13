<?php

use yii\web\View;
use yii\helpers\Html;

$this->registerCssFile('@web/css/valores-cards.css');
?>

<div class="row g-3 mb-4">
    <!-- Valor Limite -->
    <div class="col-lg-4" id="card-wrapper-valor-limite">
        <div class="card shadow-sm text-center" id="card-valor-limite-wrapper">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Valor Limite</h6>
                <h2
                    id="card-valor-limite"
                    data-valor="<?= $valorLimite ?>"
                    class="display-4 mb-0 <?= strlen((string)$valorLimite) > 15 ? 'texto-ajustado' : '' ?>">
                    <?= $valorLimite >= 999999999.99
                        ? '<span class="text-muted fst-italic">(não aplicável)</span>'
                        : Yii::$app->formatter->asCurrency($valorLimite) ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Limite Apurado -->
    <div class="col-lg-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Limite Apurado</h6>
                <h2
                    id="card-limite-apurado"
                    data-valor="<?= $valorLimiteApurado ?>"
                    class="display-4 mb-0 <?= strlen((string)$valorLimiteApurado) > 15 ? 'texto-ajustado' : '' ?>">
                    <?= Yii::$app->formatter->asCurrency($valorLimiteApurado) ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Saldo -->
    <div class="col-lg-4">
        <div
            id="card-saldo"
            class="card shadow-sm text-center <?= $valorLimite >= 999999999.99 ? 'text-bg-warning' : '' ?>">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Saldo</h6>
                <h2 id="card-saldo-valor" class="display-4 mb-0 <?= strlen((string)$valorSaldo) > 15 ? 'texto-ajustado' : '' ?>">
                    <?= $valorLimite >= 999999999.99
                        ? '<span class="text-muted fst-italic">(não aplicável)</span>'
                        : Yii::$app->formatter->asCurrency($valorSaldo) ?>
                </h2>
            </div>
        </div>
    </div>
</div>