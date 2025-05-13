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

<?= $this->render('/processolicitatorio/processo-licitatorio/_cards-financeiros', [
    'valorLimite' => $model->valor_limite,
    'valorLimiteApurado' => $model->valor_limite_apurado,
    'valorSaldo' => $model->valor_saldo,
]) ?>

<hr>

<!-- Campos que serão salvos no banco (inalterados) -->
<div class="row g-3">
    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_valorestimado')->textInput(['id' => 'processolicitatorio-valorestimado']) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_valoraditivo')->textInput(['id' => 'processolicitatorio-valoraditivo']) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_valorefetivo')->textInput() ?>
    </div>
</div>