<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\Observacoes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="capas-form">

    <?php $form = ActiveForm::begin(['options' => ['target' => '_blank']]); ?>

    <div class="mb-3 row">
        <div class="col-md-6">
            <?= $form->field($model, 'cap_tipo')->widget(Select2::class, [
                'data' => [0 => 'Capa', 1 => 'Homologação', 2 => 'Índice'],
                'options' => ['placeholder' => 'Informe o tipo da Capa...'],
                'pluginOptions' => ['allowClear' => true],
            ]) ?>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <?= Html::submitButton('<i class="bi bi-printer me-1"></i> Imprimir', [
            'class' => 'btn btn-success shadow-sm'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>