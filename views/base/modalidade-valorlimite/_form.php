<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modalidade-valorlimite-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Novo Valor Limite</h3>
        </div>
        <table class="table table-condensed table-hover">
            <thead>
                <tr class="info">
                    <th colspan="12">SEÇÃO 1: Informações</th>
                </tr>
            </thead>
        </table>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-3">
                    <?php
                    $data_modalidade = ArrayHelper::map($modalidade, 'id', 'mod_descricao');
                    echo $form->field($model, 'modalidade_id')->widget(Select2::classname(), [
                        'data' =>  $data_modalidade,
                        'disabled' => !$model->isNewRecord ? true : false,
                        'options' => ['placeholder' => 'Selecione a modalidade...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-5">
                    <?php
                    $data_ramo = ArrayHelper::map($ramo, 'id', 'ram_descricao');
                    echo $form->field($model, 'ramo_id')->widget(Select2::classname(), [
                        'data' =>  $data_ramo,
                        'disabled' => !$model->isNewRecord ? true : false,
                        'options' => ['placeholder' => 'Selecione o Segmento...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
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
                <div class="col-md-2">
                    <?= $form->field($model, 'status')->radioList(['1' => 'Ativo', '0' => 'Inativo']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $form->field($model, 'valor_limite')->widget(MaskMoney::classname(), [
                        'pluginOptions' => [
                            'prefix' => 'R$ ',
                            'allowNegative' => false
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'tipo')->radioList(['1' => 'Ilimitado', '0' => 'Limitado']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Gravar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>