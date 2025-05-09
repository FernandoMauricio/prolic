<?php

use yii\helpers\Html;
use yii\web\View;
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
$this->registerCssFile('@web/css/processolicitatorio.css');
$this->registerJsFile('@web/js/valores-cards.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('@web/js/saldo-validacao.js', ['depends' => [JqueryAsset::class]]);
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
                <?= $form->field($model, 'artigo_id')->widget(Select2::class, [
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

        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Valor Limite</h6>
                        <h2 id="card-valor-limite" data-valor="<?= $model->valor_limite ?>" class="display-4 mb-0">
                            <?= Yii::$app->formatter->asCurrency($model->valor_limite) ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Limite Apurado</h6>
                        <h2 id="card-limite-apurado" data-valor="<?= $model->valor_limite_apurado ?>" class="display-4 mb-0">
                            <?= Yii::$app->formatter->asCurrency($model->valor_limite_apurado) ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="card-saldo" class="card shadow-sm text-center">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Saldo</h6>
                        <h2 id="card-saldo-valor" class="display-4 mb-0"></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-light d-flex justify-content-end">
        <?= Html::a('<i class="bi bi-x-circle me-1"></i> Cancelar', ['index'], ['class' => 'btn btn-outline-secondary me-2']) ?>
        <?= Html::submitButton('<i class="bi bi-check-circle me-1"></i> Salvar e Continuar', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
    function exibirAlertaSaldoNegativo(mensagem) {
        const alertaHtml = `
        <div class="alert alert-danger d-flex align-items-center shadow-sm fade show shake-erro"
             role="alert" id="alerta-saldo-negativo">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>${mensagem}</div>
        </div>
    `;
        $('#saldo-alerta-container').html(alertaHtml);
    }
</script>