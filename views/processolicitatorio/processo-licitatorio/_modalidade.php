<?php

use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use kartik\number\NumberControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>
<div class="row g-3">
    <div class="col-lg-4">
        <?php
        $data_modalidade = ArrayHelper::map($valorlimite, 'modalidade.id', 'modalidade.mod_descricao');
        $modalidadeData = $model->isNewRecord ? $data_modalidade : ArrayHelper::map(
            \app\models\base\ModalidadeValorlimite::find()
                ->innerJoinWith('modalidade')
                ->where(['mod_status' => 1])
                ->andWhere(['!=', 'homologacao_usuario', ''])
                ->andWhere(['modalidade_id' => $model->modalidadeValorlimite->modalidade->id])
                ->all(),
            'modalidade.id',
            'modalidade.mod_descricao'
        );
        echo $form->field($model, 'modalidade')->widget(Select2::class, [
            'data' => $modalidadeData,
            'options' => ['id' => 'modalidade-id', 'placeholder' => 'Selecione a Modalidade...', 'value' => $model->modalidadeValorlimite->modalidade->id],
            'pluginOptions' => ['allowClear' => true],
        ]);
        ?>
    </div>

    <div class="col-lg-4">
        <?= $form->field($model, 'modalidade_valorlimite_id')->widget(DepDrop::class, [
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => ['pluginOptions' => ['allowClear' => true]],
            'options' => ['id' => 'valorlimite-id'],
            'pluginOptions' => [
                'depends' => ['modalidade-id'],
                'placeholder' => 'Selecione o Ramo...',
                'initialize' => true,
                'url' => Url::to(['/processolicitatorio/processo-licitatorio/limite']),
                'data' => [$model->modalidade_valorlimite_id => $model->modalidadeValorlimite->ramo->ram_descricao],
            ],
            'options' => [
                'onchange' => '
                    var select = this;
                    var limiteId = $(this).val(); // Obtém o ID do limite selecionado
                    if (limiteId) {
                        $.getJSON("' . Url::toRoute("/processolicitatorio/processo-licitatorio/get-sum-limite") . '", { limiteId: $(this).val(), processo: ' . $model->id . ' })
                            .done(function(data) {
                                console.log(data); // Verifique os dados no console
                                if (data) {
                                    // Verificar se os campos de input existem antes de preencher
                                    if($("#processolicitatorio-valor_limite_hidden").length) {
                                        $("#processolicitatorio-valor_limite_hidden").val(data.valor_limite);
                                        // Garantir a formatação no campo NumberControl
                                        $("#processolicitatorio-valor_limite").val(data.valor_limite).trigger("input");
                                    }
                                    if($("#processolicitatorio-valor_limite_apurado_hidden").length) {
                                        $("#processolicitatorio-valor_limite_apurado_hidden").val(data.valor_limite_apurado);
                                        // Garantir a formatação no campo NumberControl
                                        $("#processolicitatorio-valor_limite_apurado").val(data.valor_limite_apurado).trigger("input");
                                    }
                                    if($("#processolicitatorio-valor_saldo_hidden").length) {
                                        $("#processolicitatorio-valor_saldo_hidden").val(data.valor_saldo);
                                        // Garantir a formatação no campo NumberControl
                                        $("#processolicitatorio-valor_saldo").val(data.valor_saldo).trigger("input");
                                    }
                                }
                            })
                            .fail(function() {
                                console.error("Falha na requisição AJAX");
                            });
                    }
                '
            ]
        ]) ?>
    </div>

    <div class="col-lg-4">
        <?= $form->field($model, 'recursos_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($recurso, 'id', 'rec_descricao'),
            'options' => ['placeholder' => 'Informe o Recurso...'],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-12">
        <?= $form->field($model, 'artigo_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($artigo, 'id', 'art_descricao'),
            'options' => ['placeholder' => 'Informe o Artigo...'],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>
</div>