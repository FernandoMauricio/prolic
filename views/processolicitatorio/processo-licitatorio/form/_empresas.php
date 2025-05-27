<?php

use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;

?>
<div class="card shadow-sm mb-4 position-relative">
    <div class="card-body">
        <?= $form->field($model, 'prolic_empresa', [
            'template' => '
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label mb-0" for="processolicitatorio-prolic_empresa">Empresas</label>
            <span id="badge-cotacoes" class="badge bg-primary">Cotações: 0</span>
        </div>
        {input}{error}{hint}
    ',
        ])->widget(Select2::class, [
            'options' => [
                'multiple' => true,
                'value' => is_array($model->prolic_empresa) ? $model->prolic_empresa : explode(';', $model->prolic_empresa),
            ],
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
        <?= $form->field($model, 'prolic_cotacoes')->hiddenInput(['id' => 'processolicitatorio-prolic_cotacoes'])->label(false) ?>
    </div>

    <div class="card-body">
        <!-- Select2 para busca da empresa ganhadora -->
        <div class="mb-3">
            <label class="form-label">Empresa Ganhadora</label>
            <?= Select2::widget([
                'name' => 'empresa_ganhadora_temp',
                'id' => 'empresa-ganhadora-temp',
                'value' => $model->prolic_empresa_ganhadora, // apenas visual
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

        <!-- Campo hidden do modelo -->
        <?= $form->field($model, 'prolic_empresa_ganhadora')->hiddenInput(['id' => 'prolic_empresa_ganhadora'])->label(false) ?>

    </div>

</div>

<script>
    function atualizarQuantidadeCotacoes() {
        const empresasSelecionadas = $('#processolicitatorio-prolic_empresa').val();
        const total = empresasSelecionadas ? empresasSelecionadas.length : 0;
        $('#processolicitatorio-prolic_cotacoes').val(total);
        $('#badge-cotacoes').text('Cotações: ' + total);
    }

    $(document).ready(function() {
        atualizarQuantidadeCotacoes();

        $('#processolicitatorio-prolic_empresa').on('change', function() {
            atualizarQuantidadeCotacoes();
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#empresa-ganhadora-temp').on('select2:select', function(e) {
            const text = e.params.data.text;
            $('#prolic_empresa_ganhadora').val(text); // salva no campo real do modelo
        });
    });
</script>