<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\Ramo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ramo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ram_descricao')->textInput(['readonly' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'ram_status')->radioList(['1' => 'Ativo', '0' => 'Inativo']) ?>

    <div class="form-group">
        <?= Html::submitButton('Gravar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
