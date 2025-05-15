<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;
use app\models\base\Unidades;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="processo-licitatorio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'row g-3']
    ]); ?>

    <div class="col-md-2">
        <?= $form->field($model, 'id') ?>
    </div>

    <div class="col-md-3">
        <?php
        $anos = array_combine(
            range(date('Y') - 5, date('Y') + 1),
            range(date('Y') - 5, date('Y') + 1)
        );

        echo $form->field($model, 'ano')->widget(Select2::classname(), [
            'data' =>  $anos,
            'options' => ['placeholder' => 'Selecione o Ano...'],
            'pluginOptions' => ['allowClear' => true],
        ]);
        ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'prolic_objeto') ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'prolic_codmxm') ?>
    </div>

    <div class="col-md-6">
        <?php
        $destinos = Unidades::find()->where(['uni_codsituacao' => 1])->orderBy('uni_nomeabreviado')->all();
        $options = ArrayHelper::map($destinos, 'uni_codunidade', 'uni_nomeabreviado');
        echo $form->field($model, 'prolic_destino')->widget(Select2::classname(), [
            'data' => $options,
            'options' => ['placeholder' => 'Informe os Demandantes...', 'multiple' => true],
            'pluginOptions' => ['allowClear' => true],
        ]);
        ?>
    </div>

    <div class="col-12 d-flex justify-content-end">
        <?= Html::submitButton('<i class="bi bi-search me-1"></i> Pesquisar', ['class' => 'btn btn-primary me-2']) ?>
        <?= Html::resetButton('<i class="bi bi-x-circle me-1"></i> Limpar', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>