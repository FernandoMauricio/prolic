<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\Ano */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ano-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'an_ano')->textInput(['readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'an_status')->radioList(['1' => 'Ativo', '0' => 'Inativo']) ?>

    <div class="form-group">
        <?= Html::submitButton('Gravar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>