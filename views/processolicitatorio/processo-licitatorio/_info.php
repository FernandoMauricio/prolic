<?php

use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

?>
<div class="row g-3">
    <div class="col-lg-2">
        <?= $form->field($model, 'ano_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($ano, 'id', 'an_ano'),
            'options' => ['placeholder' => 'Ano...'],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>
    <div class="col-lg-3">
        <?= $form->field($model, 'prolic_dataprocesso')->widget(DateControl::class, [
            'type' => DateControl::FORMAT_DATE,
            'ajaxConversion' => false,
            'widgetOptions' => [
                'pluginOptions' => ['autoclose' => true]
            ]
        ]) ?>
    </div>
    <div class="col-lg-7">
        <?= $form->field($model, 'prolic_destino')->widget(Select2::class, [
            'data' => ArrayHelper::map($destinos, 'uni_codunidade', 'uni_nomeabreviado'),
            'options' => ['placeholder' => 'Informe os Destinos...', 'multiple' => true],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>
    <div class="col-lg-12">
        <?= $form->field($model, 'prolic_codmxm')->widget(Select2::class, [
            'options' => [
                'id' => 'processolicitatorio-prolic_codmxm',
                'multiple' => true,
                'placeholder' => 'Digite o número da requisição...',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 5,
                'ajax' => [
                    'url' => Url::to(['processolicitatorio/processo-licitatorio/buscar-requisicao-opcao']),
                    'dataType' => 'json',
                    'delay' => 300,
                    'data' => new JsExpression('function(params) { return { term: params.term }; }'),
                    'processResults' => new JsExpression('function(data) { return data; }'),
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                // Configurando para separar os itens com ponto e vírgula
                'templateResult' => new JsExpression('function (data) { return data.text; }'),
                'templateSelection' => new JsExpression('function (data) { return data.text; }'),
            ],
        ]) ?>
    </div>
</div>
<div class="col-lg-12">
    <?= $form->field($model, 'prolic_objeto')->textarea(['rows' => 3]) ?>
</div>

<script>
    // Transformar a seleção do Select2 em texto separado por ponto e vírgula
    $('#processolicitatorio-prolic_codmxm').on('change', function() {
        var selectedValues = $(this).val(); // Pega as opções selecionadas
        if (selectedValues) {
            // Junta os valores com ponto e vírgula
            $('#processolicitatorio-prolic_codmxm').val(selectedValues.join('; '));
        }
    });
</script>