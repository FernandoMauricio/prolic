<?php

use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */
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
                'onchange' => "
                    var select = this;
                    var limiteId = $(this).val(); // Obtém o ID do limite selecionado
                    console.log('Valor do limite selecionado:', limiteId); // Log para verificar o limite selecionado
                    if (limiteId) {
$.getJSON('" . Url::toRoute("/processolicitatorio/processo-licitatorio/get-sum-limite") . "', { limiteId: $(this).val(), processo: " . $model->id . " })
    .done(function(data) {
        console.log('Dados retornados do servidor:', data); // Verificando os dados retornados

        if (data) {
            // Garantir que os valores sejam números antes de passar para os campos
            var valorLimite = parseFloat(data.valor_limite);
            var valorLimiteApurado = parseFloat(data.valor_limite_apurado);
            var valorSaldo = parseFloat(data.valor_saldo);

            console.log('Valores formatados:', valorLimite, valorLimiteApurado, valorSaldo); // Verificando os valores formatados

            // Atualizando os campos com os valores corretamente
            $('#processolicitatorio-valor_limite').val(valorLimite).trigger('input');
            $('#processolicitatorio-valor_limite_apurado').val(valorLimiteApurado).trigger('input');
            $('#processolicitatorio-valor_saldo').val(valorSaldo).trigger('input');
        }
    })
    .fail(function() {
        console.error('Falha na requisição AJAX');
    });

                    }
                "
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

<?php
// Registrar jQuery e Inputmask
$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/inputmask.min.js', ['position' => \yii\web\View::POS_END]);
?>

<script>
    $(document).ready(function() {
        // Aplica a máscara de moeda aos campos
        Inputmask("currency", {
            prefix: 'R$ ',
            groupSeparator: '.',
            radixPoint: ',',
            placeholder: '0,00'
        }).mask("#processolicitatorio-valor_limite, #processolicitatorio-valor_limite_apurado, #processolicitatorio-valor_saldo");
    });
</script>