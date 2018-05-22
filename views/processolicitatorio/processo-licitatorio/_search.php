<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

    <?= $form->field($model, 'ano_id') ?>

    <?= $form->field($model, 'prolic_objeto') ?>

    <?= $form->field($model, 'prolic_codmxm') ?>

    <?= $form->field($model, 'prolic_destino') ?>

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
