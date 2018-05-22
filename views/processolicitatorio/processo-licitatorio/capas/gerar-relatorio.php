<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\Observacoes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="observacoes-form">

    <?php $form = ActiveForm::begin(['options'=>['target'=>'_blank']]); ?>

<div class="panel-body">
    <div class="row">
        <div class="col-md-4">
            <?php 
                echo $form->field($model, 'cap_tipo')->widget(Select2::classname(), [
                    'data' => [0 => 'Padrão', 1 => 'Fecomércio/Senac', 2 => 'Índice'],
                    'options' => ['placeholder' => 'Informe o tipo da Capa...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);  
            ?>
        </div>
    </div>

    <div class="form-group"><?= Html::submitButton('Imprimir', ['class' => 'btn btn-success']) ?></div>
</div>
    <?php ActiveForm::end(); ?>

</div>
