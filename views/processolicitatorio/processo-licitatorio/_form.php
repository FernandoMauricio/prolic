<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\money\MaskMoney;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="processo-licitatorio-form">

    <?php $form = ActiveForm::begin(); ?>

<div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Novo Valor Limite</h3>
      </div>
        <table class="table table-condensed table-hover">
          <thead>
            <tr class="info"><th colspan="12">SEÇÃO 1: Informações</th></tr>
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
            <div class="col-md-2"><?= $form->field($model, 'prolic_codmxm')->textInput() ?></div>

            <div class="col-md-8">
                <?php 
                    $options = \yii\helpers\ArrayHelper::map($destinos, 'uni_nomeabreviado', 'uni_nomeabreviado');
                        echo $form->field($model, 'prolic_destino')->widget(Select2::classname(), [
                            'data' => $options,
                            'options' => ['placeholder' => 'Informe os Destinos...', 'multiple'=>true],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);  
                ?>
             </div>
        </div>

        <div class="row">
            <div class="col-md-12"><?= $form->field($model, 'prolic_objeto')->textarea(['rows' => 6]) ?></div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?php
                    $data_modalidade = \yii\helpers\ArrayHelper::map($modalidade, 'id', 'mod_descricao');
                    echo $form->field($model, 'modalidade')->widget(Select2::classname(), [
                        'data' => $data_modalidade,
                        'options' => ['id' => 'modalidade-id','placeholder' => 'Selecione a Modalidade...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);  
                ?>
            </div>
            <div class="col-md-3">
                <?php 
                    echo $form->field($model, 'modalidade_valorlimite_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'options'=>['id'=>'valorlimite-id'],
                        'pluginOptions'=>[
                            'depends'=>['modalidade-id'],
                            'placeholder'=>'Selecione o Ramo...',
                            'initialize' => true,
                            'url'=>Url::to(['/base/modalidade-valorlimite/limite'])
                        ]
                    ]);

                    // $options = \yii\helpers\ArrayHelper::map($valorlimite, 'id', 'ramo.ram_descricao');
                    //     echo $form->field($model, 'modalidade_valorlimite_id')->widget(Select2::classname(), [
                    //         'data' => $options,
                    //         'options' => ['placeholder' => 'Informe o Ramo...'],
                    //         'pluginOptions' => [
                    //             'allowClear' => true
                    //         ],
                    //     ]);  
                ?>
            </div>
            <div class="col-md-2">
                <?php echo $form->field($model, 'prolic_valorestimado')->widget(MaskMoney::classname(), [
                        'pluginOptions' => [
                            'prefix' => 'R$ ',
                            'allowNegative' => false
                        ]
                    ]);
                ?>
            </div>
            <div class="col-md-2">
                <?php echo $form->field($model, 'prolic_valoraditivo')->widget(MaskMoney::classname(), [
                        'pluginOptions' => [
                            'prefix' => 'R$ ',
                            'allowNegative' => false
                        ]
                    ]);
                ?>
            </div>
            <div class="col-md-2">
                <?php echo $form->field($model, 'prolic_valorefetivo')->widget(MaskMoney::classname(), [
                        'pluginOptions' => [
                            'prefix' => 'R$ ',
                            'allowNegative' => false
                        ]
                    ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-1"><?= $form->field($model, 'prolic_sequenciamodal')->textInput() ?></div>

             <div class="col-md-3">
                <?php 
                    $options = \yii\helpers\ArrayHelper::map($artigo, 'id', 'art_descricao');
                        echo $form->field($model, 'artigo_id')->widget(Select2::classname(), [
                            'data' => $options,
                            'options' => ['placeholder' => 'Informe o Artigo...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);  
                ?>
            </div>

            <div class="col-md-1"><?= $form->field($model, 'prolic_cotacoes')->textInput() ?></div>

            <div class="col-md-4">
                <?php 
                    $options = \yii\helpers\ArrayHelper::map($centrocusto, 'cen_codcentrocusto', 'cen_centrocustoreduzido');
                        echo $form->field($model, 'prolic_centrocusto')->widget(Select2::classname(), [
                            'data' => $options,
                            'options' => ['placeholder' => 'Informe os Centros de Custos...', 'multiple'=>true],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);  
                ?>
            </div>

            <div class="col-md-3"><?= $form->field($model, 'prolic_elementodespesa')->textInput() ?></div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?php 
                    $options = \yii\helpers\ArrayHelper::map($recurso, 'id', 'rec_descricao');
                        echo $form->field($model, 'recursos_id')->widget(Select2::classname(), [
                            'data' => $options,
                            'options' => ['placeholder' => 'Informe o Recurso...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);  
                ?>
            </div>

            <div class="col-md-3">
                <?php 
                    $options = \yii\helpers\ArrayHelper::map($comprador, 'id', 'comp_descricao');
                        echo $form->field($model, 'comprador_id')->widget(Select2::classname(), [
                            'data' => $options,
                            'options' => ['placeholder' => 'Informe o Comprador...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);  
                ?>
            </div>

            <div class="col-md-3"><?= $form->field($model, 'prolic_datacertame')->textInput() ?></div>

            <div class="col-md-3"><?= $form->field($model, 'prolic_datadevolucao')->textInput() ?></div>
        </div>

    <?= $form->field($model, 'situacao_id')->textInput() ?>

    <?= $form->field($model, 'prolic_datahomologacao')->textInput() ?>

    <?= $form->field($model, 'prolic_motivo')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'prolic_empresa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ramo_descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prolic_usuariocriacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prolic_datacriacao')->textInput() ?>

    <?= $form->field($model, 'prolic_usuarioatualizacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prolic_dataatualizacao')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
