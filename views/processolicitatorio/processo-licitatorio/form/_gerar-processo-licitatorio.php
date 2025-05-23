<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use yii\web\JqueryAsset;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */
/* @var array $valorlimite */
/* @var array $artigo */
?>

<script>
    var requisicoesSalvas = <?= json_encode($model->prolic_codmxm ? explode(';', $model->prolic_codmxm) : []) ?>;
</script>

<?php
$form = ActiveForm::begin([
    'id' => 'form-gerar-processo',
    'options' => ['class' => 'h-100', 'enctype' => 'multipart/form-data'],
]);
$this->registerJsFile('@web/js/processolicitatorio.js', ['depends' => [JqueryAsset::class]]);
$this->registerCssFile('@web/css/valores-cards.css');
$this->registerJsFile('@web/js/valores-cards.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('@web/js/saldo-validacao.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('@web/js/alertas.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$sumUrl = Url::to(['/processolicitatorio/processo-licitatorio/get-sum-limite']);
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-journal-plus me-2"></i> Dados do Processo Licitatório</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info shadow-sm" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Próxima etapa:</strong> Após salvar esta solicitação, você será direcionado para adicionar mais detalhes sobre o processo.
        </div>

        <div class="row g-3">
            <!-- Modalidade -->
            <div class="col-md-4">
                <?= $form->field($model, 'modalidade')->widget(Select2::class, [
                    'data' => ArrayHelper::map($valorlimite, 'modalidade.id', 'modalidade.mod_descricao'),
                    'options' => ['id' => 'modalidade-id', 'placeholder' => 'Selecione a Modalidade…'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumResultsForSearch' => 0,
                        'dropdownParent' => new JsExpression('$("#modal")'),
                    ],
                ]) ?>
            </div>

            <!-- Segmento -->
            <div class="col-md-8">
                <?= $form->field($model, 'modalidade_valorlimite_id')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => [
                        'options' => ['id' => 'valorlimite-id'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumResultsForSearch' => 0,
                            'dropdownParent' => new JsExpression('$("#modal")'),
                        ],
                    ],
                    'pluginOptions' => [
                        'depends' => ['modalidade-id'],
                        'placeholder' => 'Selecione o Segmento…',
                        'initialize' => true,
                        'url' => Url::to(['processolicitatorio/processo-licitatorio/limite']),
                    ],
                    'pluginEvents' => [
                        "change" => new JsExpression("
                            function() {
                                var limiteId = $(this).val();
                                if (!limiteId) return;
                                $.getJSON('{$sumUrl}', {
                                limiteId: limiteId,
                                processo: 0
                                })
                                .done(function(data) {
                                var vl = parseFloat(data.valor_limite) || 0;
                                var va = parseFloat(data.valor_limite_apurado) || 0;
                                $('#card-valor-limite').data('valor', vl).text(formatarMoeda(vl));
                                $('#card-limite-apurado').data('valor', va).text(formatarMoeda(va));
                                calcularValores();
                                });
                            }
                            ")
                    ]
                ])->label('Segmento <span class="text-danger">*</span>') ?>
            </div>

            <!-- Recurso -->
            <div class="col-md-6">
                <?= $form->field($model, 'recursos_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($recurso, 'id', 'rec_descricao'),
                    'options' => ['placeholder' => 'Selecione o Recurso...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumResultsForSearch' => 0,
                        'dropdownParent' => new JsExpression('$("#modal")'),
                    ],
                ]) ?>
            </div>

            <!-- Comprador -->
            <div class="col-md-6">
                <?= $form->field($model, 'comprador_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($comprador, 'id', 'comp_descricao'),
                    'options' => ['placeholder' => 'Selecione o Comprador...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumResultsForSearch' => 0,
                        'dropdownParent' => new JsExpression('$("#modal")'),
                    ],
                ]) ?>
            </div>

            <!-- Artigo -->
            <div class="col-md-12">
                <label class="form-label" for="processolicitatorio-artigo_id">
                    Artigo
                    <span id="badge-artigo-tipo" class="badge bg-secondary ms-2 align-middle d-none"></span>
                </label>
                <?= $form->field($model, 'artigo_id', ['template' => '{input}{error}'])->widget(Select2::class, [
                    'data' => ArrayHelper::map($artigo, 'id', 'art_descricao'),
                    'options' => ['placeholder' => 'Informe o Artigo…'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumResultsForSearch' => 0,
                        'dropdownParent' => new JsExpression('$("#modal")'),
                    ],
                ]) ?>
            </div>
        </div>

        <?= Html::activeHiddenInput($model, 'valor_limite', ['id' => 'processolicitatorio-valor_limite']) ?>
        <?= Html::activeHiddenInput($model, 'valor_limite_apurado', ['id' => 'processolicitatorio-valor_limite_apurado']) ?>
        <?= Html::activeHiddenInput($model, 'valor_saldo', ['id' => 'processolicitatorio-valor_saldo']) ?>

        <div id="saldo-alerta-container"></div>

        <div id="cards-financeiros">
            <?= $this->render('/processolicitatorio/processo-licitatorio/_cards-financeiros', [
                'valorLimite' => $model->valor_limite,
                'valorLimiteApurado' => $model->valor_limite_apurado,
                'valorSaldo' => $model->valor_saldo,
            ]) ?>
        </div>
        <div id="info-artigo-situacao" class="alert alert-info d-none mt-3" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            Este processo será cadastrado com base em <strong>situação específica</strong>. Valores financeiros não se aplicam.
        </div>

    </div>
    <div class="card-footer bg-light d-flex justify-content-end">
        <?= Html::a('<i class="bi bi-x-circle me-1"></i> Cancelar', ['index'], ['class' => 'btn btn-outline-secondary me-2']) ?>
        <?= Html::submitButton(
            '<span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true" id="spinner-botao"></span><i class="bi bi-check-circle me-1"></i> Salvar e Continuar',
            [
                'class' => 'btn btn-primary',
                'id' => 'botao-submit',
                'type' => 'submit',
                'name' => 'submit-button',
            ]
        ) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$urlBuscarArtigo = Url::to(['processolicitatorio/processo-licitatorio/buscar-artigo-tipo']);
$this->registerJs(<<<JS
$('#processolicitatorio-artigo_id').on('change', function () {
    var artigoId = $(this).val();
    var badge = $('#badge-artigo-tipo');
    var cards = $('#cards-financeiros');
    var infoSituacao = $('#info-artigo-situacao');
    var alerta = $('#saldo-alerta');

    if (!artigoId) {
        badge.addClass('d-none').text('');
        cards.show();
        infoSituacao.addClass('d-none');
        alerta.show();
        return;
    }

    $.getJSON('{$urlBuscarArtigo}', { id: artigoId }, function (data) {
        if (data.success && data.tipo) {
            badge.removeClass('d-none').text(data.tipo);
            $('#processolicitatorio-artigo_id').data('tipo-artigo', data.tipo);
            if (data.tipo.toLowerCase().includes('situação')) {
                cards.hide();
                infoSituacao.removeClass('d-none');
                alerta.hide();
                $('#saldo-alerta-container').html('');
            } else {
                cards.show();
                infoSituacao.addClass('d-none');
                alerta.show();
            }
        } else {
            badge.addClass('d-none').text('');
            cards.show();
            infoSituacao.addClass('d-none');
            alerta.show();
        }
    });
});

JS);

?>