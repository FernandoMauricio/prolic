<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\base\Ano;
use app\models\base\Unidades;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="processo-licitatorio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?php   
        $ano = Ano::find()->where(['an_status' => 1])->orderBy('an_ano')->all();
        $data_ano = ArrayHelper::map($ano, 'id', 'an_ano');
            echo $form->field($model, 'ano_id')->widget(Select2::classname(), [
            'data' =>  $data_ano,
            'options' => ['placeholder' => 'Selecione o Ano...'],
            'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
    ?>

    <?= $form->field($model, 'prolic_objeto') ?>

    <?= $form->field($model, 'prolic_codmxm') ?>

    <?php
        $destinos = Unidades::find()->where(['uni_codsituacao' => 1])->orderBy('uni_nomeabreviado')->all();
        $options = ArrayHelper::map($destinos, 'uni_codunidade', 'uni_nomeabreviado');
            echo $form->field($model, 'prolic_destino')->widget(Select2::classname(), [
                'data' => $options,
                'options' => ['placeholder' => 'Informe os Destinos...', 'multiple'=>true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);  
    ?>


    <?php // echo $form->field($model, 'modalidade_valorlimite_id') ?>

    <?php // echo $form->field($model, 'prolic_sequenciamodal') ?>

    <?php // echo $form->field($model, 'artigo_id') ?>

    <?php // echo $form->field($model, 'prolic_cotacoes') ?>

    <?php // echo $form->field($model, 'prolic_centrocusto') ?>

    <?php // echo $form->field($model, 'prolic_elementodespesa') ?>

    <?php // echo $form->field($model, 'prolic_valorestimado') ?>

    <?php // echo $form->field($model, 'prolic_valoraditivo') ?>

    <?php // echo $form->field($model, 'prolic_valorefetivo') ?>

    <?php // echo $form->field($model, 'recursos_id') ?>

    <?php // echo $form->field($model, 'comprador_id') ?>

    <?php // echo $form->field($model, 'prolic_datacertame') ?>

    <?php // echo $form->field($model, 'prolic_datadevolucao') ?>

    <?php // echo $form->field($model, 'situacao_id') ?>

    <?php // echo $form->field($model, 'prolic_datahomologacao') ?>

    <?php // echo $form->field($model, 'prolic_motivo') ?>

    <?php // echo $form->field($model, 'prolic_usuariocriacao') ?>

    <?php // echo $form->field($model, 'prolic_datacriacao') ?>

    <?php // echo $form->field($model, 'prolic_usuarioatualizacao') ?>

    <?php // echo $form->field($model, 'prolic_dataatualizacao') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
