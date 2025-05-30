<?php

use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

?>
<div class="row g-3">
    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_dataprocesso')->widget(DateControl::class, [
            'type' => DateControl::FORMAT_DATE,
            'ajaxConversion' => false,
            'widgetOptions' => [
                'pluginOptions' => ['autoclose' => true]
            ]
        ]) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'situacao_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($situacao, 'id', 'sit_descricao'),
            'options' => ['placeholder' => 'Informe a Situação...'],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_codmxm')->widget(Select2::class, [
            'options' => [
                'id' => 'processolicitatorio-prolic_codmxm',
                'multiple' => true,
                'placeholder' => 'Digite o número da requisição...',
            ],
            'data' => array_combine($model->requisicoesCodmxm, $model->requisicoesCodmxm),
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
            ],
        ]) ?>
    </div>
    <div class="col-lg-12">
        <?= $form->field($model, 'prolic_destino')->widget(Select2::class, [
            'data' => ArrayHelper::map($destinos, 'uni_codunidade', 'uni_nomeabreviado'),
            'options' => ['placeholder' => 'Informe os Demandante(s)...', 'multiple' => true],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>
</div>
<div class="col-lg-12">
    <?= $form->field($model, 'prolic_objeto')->textarea(['rows' => 3]) ?>
</div>

<script>
    // Verifique se o valor de $model->prolic_codmxm é uma string e aplique explode() ou use o valor diretamente
    var requisicoesSalvas = <?php echo json_encode(is_string($model->prolic_codmxm) ? explode(';', $model->prolic_codmxm) : ($model->prolic_codmxm ?: [])); ?>;
</script>