<?php

use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;

?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <?= $form->field($model, 'prolic_empresa')->widget(Select2::class, [
            'data' => $empresasFormatadas,
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'placeholder' => 'Digite o CPF/CNPJ da empresa...',
                'minimumInputLength' => 8,
                'ajax' => [
                    'url' => Url::to(['processolicitatorio/processo-licitatorio/buscar-fornecedor']),
                    'dataType' => 'json',
                    'delay' => 250,
                    'data' => new JsExpression('function(params) { return { q: params.term }; }'),
                    'processResults' => new JsExpression('function(data) {
                        return {
                            results: data.map(function(item) {
                                return { id: item.id, text: item.text };
                            })
                        };
                    }'),
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            ],
        ]) ?>
    </div>
</div>