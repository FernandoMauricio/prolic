<?php

use kartik\number\NumberControl;
?>

<div class="row g-3">
    <div class="col-lg-2">
        <?= $form->field($model, 'valor_limite')->widget(NumberControl::class, [
            'disabled' => true,
            'maskedInputOptions' => [
                'prefix' => 'R$ ',
                'digits' => 2,
                'groupSeparator' => '.',
                'radixPoint' => ',',
                'autoGroup' => true,
                'unmaskAsNumber' => true,
            ],
        ]) ?>
        <?= $form->field($model, 'valor_limite_hidden')->hiddenInput()->label(false); ?>
    </div>

    <div class="col-lg-2">
        <?= $form->field($model, 'valor_limite_apurado')->widget(NumberControl::class, [
            'disabled' => true,
            'maskedInputOptions' => [
                'prefix' => 'R$ ',
                'digits' => 2,
                'groupSeparator' => '.',
                'radixPoint' => ',',
                'autoGroup' => true,
                'unmaskAsNumber' => true,
            ],
        ]) ?>
        <?= $form->field($model, 'valor_limite_apurado_hidden')->hiddenInput()->label(false); ?>
    </div>

    <div class="col-lg-2">
        <?= $form->field($model, 'valor_saldo')->widget(NumberControl::class, [
            'disabled' => true,
            'maskedInputOptions' => [
                'prefix' => 'R$ ',
                'digits' => 2,
                'groupSeparator' => '.',
                'radixPoint' => ',',
                'autoGroup' => true,
                'unmaskAsNumber' => true,
            ],
        ]) ?>
        <?= $form->field($model, 'valor_saldo_hidden')->hiddenInput()->label(false); ?>
    </div>

    <div class="col-lg-2">
        <?= $form->field($model, 'prolic_valorestimado')->widget(NumberControl::class, [
            'maskedInputOptions' => [
                'prefix' => 'R$ ',
                'digits' => 2,
                'groupSeparator' => '.',
                'radixPoint' => ',',
                'autoUnmask' => true,
                'unmaskAsNumber' => true,
            ],
        ]) ?>
    </div>

    <div class="col-lg-2">
        <?= $form->field($model, 'prolic_valoraditivo')->widget(NumberControl::class, [
            'maskedInputOptions' => [
                'prefix' => 'R$ ',
                'digits' => 2,
                'groupSeparator' => '.',
                'radixPoint' => ',',
                'autoUnmask' => true,
                'unmaskAsNumber' => true,
            ],
        ]) ?>
    </div>

    <div class="col-lg-2">
        <?= $form->field($model, 'prolic_valorefetivo')->widget(NumberControl::class, [
            'maskedInputOptions' => [
                'prefix' => 'R$ ',
                'digits' => 2,
                'groupSeparator' => '.',
                'radixPoint' => ',',
                'autoUnmask' => true,
                'unmaskAsNumber' => true,
            ],
        ]) ?>
        <?= $form->field($model, 'prolic_valorefetivo_hidden')->hiddenInput()->label(false); ?>
    </div>
</div>