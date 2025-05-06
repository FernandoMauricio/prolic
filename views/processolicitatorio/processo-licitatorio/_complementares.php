<?php

use kartik\select2\Select2;
use faryshta\widgets\JqueryTagsInput;
use yii\helpers\ArrayHelper;
?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-2">
                <?= $form->field($model, 'prolic_cotacoes')->textInput(['type' => 'number']) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'prolic_centrocusto')->widget(Select2::class, [
                    'data' => ArrayHelper::map($centrocusto, 'cen_centrocustoreduzido', 'cen_centrocustoreduzido'),
                    'options' => [
                        'placeholder' => 'Informe os Centros de Custos...',
                        'multiple' => true
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'prolic_elementodespesa')->widget(JqueryTagsInput::class, [
                    'clientOptions' => [
                        'defaultText' => '',
                        'width' => '100%',
                        'height' => 'auto',
                        'delimiter' => ' / ',
                        'interactive' => true,
                    ],
                ]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'comprador_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($comprador, 'id', 'comp_descricao'),
                    'options' => ['placeholder' => 'Informe o Comprador...'],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?>
            </div>
        </div>
    </div>
</div>