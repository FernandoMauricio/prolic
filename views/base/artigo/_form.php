<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\base\Artigo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="artigo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'art_descricao')->textInput(['readonly' => !$model->isNewRecord]) ?>

    <?php
        echo $form->field($model, 'art_tipo')->widget(Select2::classname(), [
        'data' =>  ['Valor' => 'Valor', 'Situação' => 'Situação'],
        'options' => ['placeholder' => 'Selecione o tipo...'],
        'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?= $form->field($model, 'art_status')->radioList(['1' => 'Ativo', '0' => 'Inativo']) ?>

    <div class="form-group">
        <?= Html::submitButton('Gravar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
