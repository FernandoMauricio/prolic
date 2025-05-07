<?php

use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

$sumUrl = Url::toRoute(['/processolicitatorio/processo-licitatorio/get-sum-limite']);
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
            'pluginOptions' => [
                'depends' => ['modalidade-id'],
                'placeholder' => 'Selecione o Ramo...',
                'initialize' => true,
                'url' => Url::to(['/processolicitatorio/processo-licitatorio/limite']),
                'data' => [$model->modalidade_valorlimite_id => $model->modalidadeValorlimite->ramo->ram_descricao],
            ],
            'options' => [
                'id' => 'valorlimite-id',
                'onchange' => new JsExpression("
            var limiteId = this.value;
            if (!limiteId) return;
            $.getJSON('{$sumUrl}', {
                limiteId: limiteId,
                processo: {$model->id}
            })
            .done(function(data) {
                var vl = parseFloat(data.valor_limite) || 0;
                var va = parseFloat(data.valor_limite_apurado) || 0;
                // atualiza os mini-cards
                $('#card-valor-limite').data('valor', vl).text(formatarMoeda(vl));
                $('#card-limite-apurado').data('valor', va).text(formatarMoeda(va));
                calcularValores();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Falha ao buscar valores do limite:', textStatus, errorThrown);
            });
        ")
            ],
        ]); ?>
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