<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;

$this->registerJsFile('@web/js/valores-cards.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('@web/js/alertas.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<?= $form->field($model, 'valor_limite')
    ->hiddenInput(['id' => 'processolicitatorio-valor_limite'])
    ->label(false) ?>

<?= $form->field($model, 'valor_limite_apurado')
    ->hiddenInput(['id' => 'processolicitatorio-valor_limite_apurado'])
    ->label(false) ?>

<?= $form->field($model, 'valor_saldo')
    ->hiddenInput(['id' => 'processolicitatorio-valor_saldo'])
    ->label(false) ?>

<div id="cards-financeiros">
    <?= $this->render('/processolicitatorio/processo-licitatorio/_cards-financeiros', [
        'valorLimite' => $model->valor_limite,
        'valorLimiteApurado' => $model->valor_limite_apurado,
        'valorSaldo' => $model->valor_saldo,
    ]) ?>
</div>

<hr>

<!-- Campos que serão salvos no banco (inalterados) -->
<div class="row g-3">
    <div class="col-lg-6">
        <?= $form->field($model, 'prolic_valorestimado')->textInput(['id' => 'processolicitatorio-valorestimado']) ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'prolic_valorefetivo')->textInput(['id' => 'processolicitatorio-prolic_valorefetivo']) ?>
        <div id="economia-info" class="badge bg-light text-success border border-success fw-semibold d-inline-block mt-2" style="display: none;"></div>
    </div>
</div>