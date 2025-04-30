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
?>

<?php $this->registerJsFile('@web/js/processolicitatorio.js', ['depends' => [\yii\web\JqueryAsset::class]]); ?>

<div class="processo-licitatorio-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Novo Processo Licitatório</h3>
        </div>
        <table class="table table-condensed table-hover">
            <thead>
                <tr class="info">
                    <th colspan="12">SEÇÃO 1: Informações</th>
                </tr>
            </thead>
        </table>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <?php
                    $data_ano = ArrayHelper::map($ano, 'id', 'an_ano');
                    echo $form->field($model, 'ano_id')->widget(Select2::classname(), [
                        'data' =>  $data_ano,
                        'options' => ['placeholder' => 'Selecione o Ano...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo $form->field($model, 'prolic_dataprocesso')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATE,
                        'ajaxConversion' => false,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ]);
                    ?>
                </div>

                <div class="row">
                    <div class="col-md-6">
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
                                    'data' => new JsExpression('function(params) {
                return { term: params.term };
            }'),
                                    'processResults' => new JsExpression('function(data) {
                return data;
            }'),
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            ],
                        ]) ?>

                    </div>
                </div>

                <div id="requisicao-feedback" class="requisicao-feedback" style="display: none;"></div>
                <div id="requisicao-preview"></div>

                <div class="col-md-5">
                    <?php
                    $options = ArrayHelper::map($destinos, 'uni_codunidade', 'uni_nomeabreviado');
                    echo $form->field($model, 'prolic_destino')->widget(Select2::classname(), [
                        'data' => $options,
                        'options' => ['placeholder' => 'Informe os Destinos...', 'multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12"><?= $form->field($model, 'prolic_objeto')->textarea(['rows' => 3]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?php
                    $data_modalidade = ArrayHelper::map($valorlimite, 'modalidade.id', 'modalidade.mod_descricao');
                    if ($model->isNewRecord) {
                        echo  $form->field($model, 'modalidade')->widget(Select2::class, [
                            'data' =>  $data_modalidade,
                            'options' => ['placeholder' => 'Selecione a Modalidade...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    } else {
                        $valorlimiteUpdate = ModalidadeValorlimite::find()
                            ->innerJoinWith('modalidade')
                            ->where(['mod_status' => 1])
                            ->andWhere(['!=', 'homologacao_usuario', ''])
                            ->andWhere(['modalidade_id' => $model->modalidadeValorlimite->modalidade->id])
                            ->all();

                        $data_modalidadeUpdate = ArrayHelper::map($valorlimiteUpdate, 'modalidade.id', 'modalidade.mod_descricao');
                        echo $form->field($model, 'modalidade')->widget(Select2::classname(), [
                            'data' => $data_modalidadeUpdate,
                            'options' => ['id' => 'modalidade-id', 'placeholder' => 'Selecione a Modalidade...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    }

                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo $form->field($model, 'modalidade_valorlimite_id')->widget(DepDrop::classname(), [
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                        'options' => ['id' => 'valorlimite-id'],
                        'pluginOptions' => [
                            'depends' => ['modalidade-id'],
                            'placeholder' => 'Selecione o Ramo...',
                            'initialize' => true,
                            'url' => Url::to(['/processolicitatorio/processo-licitatorio/limite'])
                        ],
                        'options' => [
                            'onchange' => '
                                    var select = this;
                                    $.getJSON( "' . Url::toRoute('/processolicitatorio/processo-licitatorio/get-limite') . '", { limiteId: $(this).val() } )
                                    .done(function( data ) {

                                           var $divPanelBody = $(select).parent().parent().parent().parent();

                                           $divPanelBody.find("input").eq(5).val(data.valor_limite);
                                           $divPanelBody.find("input").eq(7).val(data.valor_limite);
                                        });

                                    $.getJSON( "' . Url::toRoute('/processolicitatorio/processo-licitatorio/get-sum-limite') . '", { limiteId: $(this).val(), processo: ' . $model->id . ' } )
                                    .done(function( data ) {

                                           var $divPanelBody = $(select).parent().parent().parent().parent().parent();

                                           $divPanelBody.find("input").eq(8).val(data.valor_limite_apurado);
                                           $divPanelBody.find("input").eq(10).val(data.valor_limite_apurado);
                                           $divPanelBody.find("input").eq(11).val(data.valor_saldo);
                                           $divPanelBody.find("input").eq(13).val(data.valor_saldo);
                                        });
                                    '
                        ]
                    ]);

                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    $options = ArrayHelper::map($artigo, 'id', 'art_descricao');
                    echo $form->field($model, 'artigo_id')->widget(Select2::classname(), [
                        'data' => $options,
                        'options' => ['placeholder' => 'Informe o Artigo...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    $options = ArrayHelper::map($recurso, 'id', 'rec_descricao');
                    echo $form->field($model, 'recursos_id')->widget(Select2::classname(), [
                        'data' => $options,
                        'options' => ['placeholder' => 'Informe o Recurso...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <?php
                    echo $form->field($model, 'valor_limite')->widget(NumberControl::classname(), [
                        'maskedInputOptions' => [
                            'prefix' => 'R$ ',
                            //'alias' => 'numeric',
                            'digits' => 2,
                            'digitsOptional' => false,
                            'groupSeparator' => '.',
                            'radixPoint' => ',',
                            'autoGroup' => true,
                            'autoUnmask' => true,
                            'unmaskAsNumber' => true,
                        ],
                        'disabled' => true,
                    ])
                    ?>
                    <?= $form->field($model, 'valor_limite_hidden')->hiddenInput()->label(false); ?>
                </div>
                <div class="col-md-2">
                    <?php
                    echo $form->field($model, 'valor_limite_apurado')->widget(NumberControl::classname(), [
                        'maskedInputOptions' => [
                            'prefix' => 'R$ ',
                            //'alias' => 'numeric',
                            'digits' => 2,
                            'digitsOptional' => false,
                            'groupSeparator' => '.',
                            'radixPoint' => ',',
                            'autoGroup' => true,
                            'autoUnmask' => true,
                            'unmaskAsNumber' => true,
                        ],
                        'disabled' => true,
                    ])
                    ?>
                    <?= $form->field($model, 'valor_limite_apurado_hidden')->hiddenInput()->label(false); ?>
                </div>
                <div class="col-md-2">
                    <?php
                    echo $form->field($model, 'valor_saldo')->widget(NumberControl::classname(), [
                        'maskedInputOptions' => [
                            'prefix' => 'R$ ',
                            //'alias' => 'numeric',
                            'digits' => 2,
                            'digitsOptional' => false,
                            'groupSeparator' => '.',
                            'radixPoint' => ',',
                            'autoGroup' => true,
                            'autoUnmask' => true,
                            'unmaskAsNumber' => true,
                        ],
                        'disabled' => true,
                    ])
                    ?>
                    <?= $form->field($model, 'valor_saldo_hidden')->hiddenInput()->label(false); ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'prolic_valorestimado')->widget(NumberControl::classname(), [
                        'maskedInputOptions' => [
                            'prefix' => 'R$ ',
                            //'alias' => 'numeric',
                            'digits' => 2,
                            'digitsOptional' => false,
                            'groupSeparator' => '.',
                            'radixPoint' => ',',
                            //'autoGroup' => true,
                            'autoUnmask' => true,
                            'unmaskAsNumber' => true,
                        ],
                    ])
                    ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'prolic_valoraditivo')->widget(NumberControl::classname(), [
                        'maskedInputOptions' => [
                            'prefix' => 'R$ ',
                            //'alias' => 'numeric',
                            'digits' => 2,
                            'digitsOptional' => false,
                            'groupSeparator' => '.',
                            'radixPoint' => ',',
                            //'autoGroup' => true,
                            'autoUnmask' => true,
                            'unmaskAsNumber' => true,
                        ],
                    ])
                    ?></div>
                <div class="col-md-2">
                    <?php
                    echo $form->field($model, 'prolic_valorefetivo')->widget(NumberControl::classname(), [
                        'maskedInputOptions' => [
                            'prefix' => 'R$ ',
                            //'alias' => 'numeric',
                            'digits' => 2,
                            'digitsOptional' => false,
                            'groupSeparator' => '.',
                            'radixPoint' => ',',
                            //'autoGroup' => true,
                            'autoUnmask' => true,
                            'unmaskAsNumber' => true,
                        ],
                    ])
                    ?>
                    <?= $form->field($model, 'prolic_valorefetivo_hidden')->hiddenInput()->label(false); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2"><?= $form->field($model, 'prolic_cotacoes')->textInput() ?></div>
                <div class="col-md-4">
                    <?php
                    $options = ArrayHelper::map($centrocusto, 'cen_centrocustoreduzido', 'cen_centrocustoreduzido');
                    echo $form->field($model, 'prolic_centrocusto')->widget(Select2::classname(), [
                        'data' => $options,
                        'options' => ['placeholder' => 'Informe os Centros de Custos...', 'multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo $form->field($model, 'prolic_elementodespesa')->widget(JqueryTagsInput::className(), [
                        'clientOptions' => [
                            'defaultText' => '',
                            'width' => '100%',
                            'height' => '100%',
                            'delimiter' => ' / ',
                            'interactive' => true,
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    $options = ArrayHelper::map($comprador, 'id', 'comp_descricao');
                    echo $form->field($model, 'comprador_id')->widget(Select2::classname(), [
                        'data' => $options,
                        'options' => ['placeholder' => 'Informe o Comprador...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <?php
                    echo $form->field($model, 'prolic_datacertame')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATE,
                        'ajaxConversion' => false,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo $form->field($model, 'prolic_datadevolucao')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATE,
                        'ajaxConversion' => false,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo $form->field($model, 'prolic_datahomologacao')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATE,
                        'ajaxConversion' => false,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    $options = ArrayHelper::map($situacao, 'id', 'sit_descricao');
                    echo $form->field($model, 'situacao_id')->widget(Select2::classname(), [
                        'data' => $options,
                        'options' => ['placeholder' => 'Informe a Situação...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <?= $form->field($model, 'prolic_motivo')->textarea(['rows' => 3]) ?>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo $form->field($model, 'prolic_empresa')->widget(Select2::classname(), [
                        'data' => $empresasFormatadas, // importante para mostrar os já selecionados
                        'options' => ['multiple' => true],
                        'pluginOptions' => [
                            'placeholder' => 'Digite o CPF/CNPJ da empresa...',
                            'minimumInputLength' => 8,
                            'ajax' => [
                                'url' => Url::to(['processolicitatorio/processo-licitatorio/buscar-fornecedor']),
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new \yii\web\JsExpression('function(params) { return { q: params.term }; }'),
                                'processResults' => new \yii\web\JsExpression('function(data) {
                return {
                    results: data.map(function(item) {
                        return { id: item.id, text: item.text };
                    })
                };
            }'),
                            ],
                            'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
                        ],
                    ]);

                    ?>
                </div>
            </div>


            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJsFile('@web/js/processolicitatorio.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>