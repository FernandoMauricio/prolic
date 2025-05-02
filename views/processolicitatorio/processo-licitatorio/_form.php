<?php

use app\models\base\ModalidadeValorlimite;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\money\MaskMoney;
use kartik\depdrop\DepDrop;
use kartik\number\NumberControl;
use kartik\datecontrol\DateControl;
use faryshta\widgets\JqueryTagsInput;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('@web/js/processolicitatorio.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="processo-licitatorio-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <!-- Card Primário -->
    <div class="card shadow-sm mb-5 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-clipboard-check me-2"></i> Atualização do Processo Licitatório
            </h5>
        </div>
        <div class="card-body">

            <!-- Informações Iniciais -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <strong><i class="bi bi-info-circle me-2"></i>Informações Iniciais</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2">
                            <?php
                            $data_ano = ArrayHelper::map($ano, 'id', 'an_ano');
                            echo $form->field($model, 'ano_id')->widget(Select2::class, [
                                'data' => $data_ano,
                                'options' => ['placeholder' => 'Ano...'],
                                'pluginOptions' => ['allowClear' => true],
                            ]);
                            ?>
                        </div>

                        <div class="col-lg-3">
                            <?= $form->field($model, 'prolic_dataprocesso')->widget(DateControl::class, [
                                'type' => DateControl::FORMAT_DATE,
                                'ajaxConversion' => false,
                                'widgetOptions' => [
                                    'pluginOptions' => ['autoclose' => true]
                                ]
                            ]); ?>
                        </div>

                        <div class="col-lg-7">
                            <?= $form->field($model, 'prolic_codmxm')->widget(Select2::class, [
                                'options' => [
                                    'id' => 'processolicitatorio-prolic_codmxm',
                                    'multiple' => true,
                                    'placeholder' => 'Digite o número da requisição...'
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
                                ],
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <?= $form->field($model, 'prolic_objeto')->textarea(['rows' => 3]) ?>
                    </div>

                    <div class="col-lg-12">
                        <?php
                        $options = ArrayHelper::map($destinos, 'uni_codunidade', 'uni_nomeabreviado');
                        echo $form->field($model, 'prolic_destino')->widget(Select2::class, [
                            'data' => $options,
                            'options' => ['placeholder' => 'Informe os Destinos...', 'multiple' => true],
                            'pluginOptions' => ['allowClear' => true],
                        ]);
                        ?>
                    </div>

                    <div id="requisicao-feedback" class="requisicao-feedback mt-3 d-none"></div>
                    <div id="requisicao-preview" class="mt-2"></div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <strong><i class="bi bi-list-ul me-2"></i>Modalidade, Ramo, Artigo e Recurso</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <?php
                            $data_modalidade = ArrayHelper::map($valorlimite, 'modalidade.id', 'modalidade.mod_descricao');
                            $modalidadeData = $model->isNewRecord ? $data_modalidade : ArrayHelper::map(
                                ModalidadeValorlimite::find()
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
                                'options' => ['id' => 'modalidade-id', 'placeholder' => 'Selecione a Modalidade...'],
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
                                    'url' => Url::to(['/processolicitatorio/processo-licitatorio/limite'])
                                ],
                            ]) ?>
                        </div>

                        <div class="col-lg-4">
                            <?php
                            echo $form->field($model, 'recursos_id')->widget(Select2::class, [
                                'data' => ArrayHelper::map($recurso, 'id', 'rec_descricao'),
                                'options' => ['placeholder' => 'Informe o Recurso...'],
                                'pluginOptions' => ['allowClear' => true],
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <?php
                        echo $form->field($model, 'artigo_id')->widget(Select2::class, [
                            'data' => ArrayHelper::map($artigo, 'id', 'art_descricao'),
                            'options' => ['placeholder' => 'Informe o Artigo...'],
                            'pluginOptions' => ['allowClear' => true],
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <strong><i class="bi bi-currency-dollar me-2"></i>Valores Financeiros</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2">
                            <?= $form->field($model, 'valor_limite')->widget(NumberControl::class, [
                                'disabled' => true,
                                'maskedInputOptions' => [
                                    'prefix' => 'R$ ',
                                    'digits' => 2,
                                    'groupSeparator' => '.',
                                    'radixPoint' => ',',
                                    'autoGroup' => true,
                                    'unmaskAsNumber' => true,
                                ],
                            ]) ?>
                            <?= $form->field($model, 'valor_limite_hidden')->hiddenInput()->label(false); ?>
                        </div>

                        <div class="col-lg-2">
                            <?= $form->field($model, 'valor_limite_apurado')->widget(NumberControl::class, [
                                'disabled' => true,
                                'maskedInputOptions' => [
                                    'prefix' => 'R$ ',
                                    'digits' => 2,
                                    'groupSeparator' => '.',
                                    'radixPoint' => ',',
                                    'autoGroup' => true,
                                    'unmaskAsNumber' => true,
                                ],
                            ]) ?>
                            <?= $form->field($model, 'valor_limite_apurado_hidden')->hiddenInput()->label(false); ?>
                        </div>

                        <div class="col-lg-2">
                            <?= $form->field($model, 'valor_saldo')->widget(NumberControl::class, [
                                'disabled' => true,
                                'maskedInputOptions' => [
                                    'prefix' => 'R$ ',
                                    'digits' => 2,
                                    'groupSeparator' => '.',
                                    'radixPoint' => ',',
                                    'autoGroup' => true,
                                    'unmaskAsNumber' => true,
                                ],
                            ]) ?>
                            <?= $form->field($model, 'valor_saldo_hidden')->hiddenInput()->label(false); ?>
                        </div>

                        <div class="col-lg-2">
                            <?= $form->field($model, 'prolic_valorestimado')->widget(NumberControl::class, [
                                'maskedInputOptions' => [
                                    'prefix' => 'R$ ',
                                    'digits' => 2,
                                    'groupSeparator' => '.',
                                    'radixPoint' => ',',
                                    'autoUnmask' => true,
                                    'unmaskAsNumber' => true,
                                ],
                            ]) ?>
                        </div>

                        <div class="col-lg-2">
                            <?= $form->field($model, 'prolic_valoraditivo')->widget(NumberControl::class, [
                                'maskedInputOptions' => [
                                    'prefix' => 'R$ ',
                                    'digits' => 2,
                                    'groupSeparator' => '.',
                                    'radixPoint' => ',',
                                    'autoUnmask' => true,
                                    'unmaskAsNumber' => true,
                                ],
                            ]) ?>
                        </div>

                        <div class="col-lg-2">
                            <?= $form->field($model, 'prolic_valorefetivo')->widget(NumberControl::class, [
                                'maskedInputOptions' => [
                                    'prefix' => 'R$ ',
                                    'digits' => 2,
                                    'groupSeparator' => '.',
                                    'radixPoint' => ',',
                                    'autoUnmask' => true,
                                    'unmaskAsNumber' => true,
                                ],
                            ]) ?>
                            <?= $form->field($model, 'prolic_valorefetivo_hidden')->hiddenInput()->label(false); ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <strong><i class="bi bi-diagram-3 me-2"></i>Informações Complementares</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2">
                            <?= $form->field($model, 'prolic_cotacoes')->textInput(['type' => 'number']) ?>
                        </div>

                        <div class="col-lg-4">
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

                        <div class="col-lg-3">
                            <?= $form->field($model, 'comprador_id')->widget(Select2::class, [
                                'data' => ArrayHelper::map($comprador, 'id', 'comp_descricao'),
                                'options' => ['placeholder' => 'Informe o Comprador...'],
                                'pluginOptions' => ['allowClear' => true],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <strong><i class="bi bi-calendar-check me-2"></i>Datas e Situação</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3">
                            <?= $form->field($model, 'prolic_datacertame')->widget(DateControl::class, [
                                'type' => DateControl::FORMAT_DATE,
                                'ajaxConversion' => false,
                                'widgetOptions' => ['pluginOptions' => ['autoclose' => true]],
                            ]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'prolic_datadevolucao')->widget(DateControl::class, [
                                'type' => DateControl::FORMAT_DATE,
                                'ajaxConversion' => false,
                                'widgetOptions' => ['pluginOptions' => ['autoclose' => true]],
                            ]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'prolic_datahomologacao')->widget(DateControl::class, [
                                'type' => DateControl::FORMAT_DATE,
                                'ajaxConversion' => false,
                                'widgetOptions' => ['pluginOptions' => ['autoclose' => true]],
                            ]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'situacao_id')->widget(Select2::class, [
                                'data' => ArrayHelper::map($situacao, 'id', 'sit_descricao'),
                                'options' => ['placeholder' => 'Informe a Situação...'],
                                'pluginOptions' => ['allowClear' => true],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <strong><i class="bi bi-card-text me-2"></i>Motivo do Processo</strong>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'prolic_motivo')->textarea([
                        'rows' => 3,
                        'placeholder' => 'Descreva aqui o motivo ou justificativa do processo...'
                    ]) ?>
                </div>
            </div>


            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <strong><i class="bi bi-buildings me-2"></i>Empresas Participantes</strong>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'prolic_empresa')->widget(Select2::class, [
                        'data' => $empresasFormatadas, // já selecionadas
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
        </div>
    </div>

    <div class="text-end mt-4">
        <?= Html::submitButton('<i class="bi bi-check-circle me-1"></i> Salvar Processo', [
            'class' => 'btn btn-success btn-lg shadow-sm px-4'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJsFile('@web/js/processolicitatorio.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>