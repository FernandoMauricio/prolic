<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="observacoes-form">

    <?php $form = ActiveForm::begin(); ?>

<div class="panel-body">
    <div class="row">
        <div class="col-md-3">
		    <?php
		        $data_ano = ArrayHelper::map($ano, 'id', 'an_ano');
		        echo $form->field($model, 'ano_id')->widget(Select2::classname(), [
		        'data' =>  $data_ano,
		        'options' => ['placeholder' => 'Ano...'],
		        'pluginOptions' => [
		                'allowClear' => true
		            ],
		        ]);
		    ?>
	    </div>
	    <div class="col-md-3"><?= $form->field($model, 'prolic_codmxm')->textInput() ?></div>
	    <div class="col-md-6">
            <?php 
                $options = ArrayHelper::map($destinos, 'uni_codunidade', 'uni_nomeabreviado');
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
        <div class="col-md-12"><?= $form->field($model, 'prolic_objeto')->textarea(['rows' => 3]) ?></div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?php
                $data_modalidade = ArrayHelper::map($valorlimite, 'id', 'modalidade.mod_descricao');
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
                        'url'=>Url::to(['/processolicitatorio/processo-licitatorio/limite'])],
					]);
            ?>
        </div>
        <div class="col-md-6">
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
	</div>
    <div class="row">
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
        <div class="col-md-3">
            <?php 
                $options = ArrayHelper::map($centrocusto, 'cen_centrocustoreduzido', 'cen_centrocustoreduzido');
                    echo $form->field($model, 'prolic_centrocusto')->widget(Select2::classname(), [
                        'data' => $options,
                        'options' => ['placeholder' => 'Informe os Centros de Custos...', 'multiple'=>true],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
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


    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
