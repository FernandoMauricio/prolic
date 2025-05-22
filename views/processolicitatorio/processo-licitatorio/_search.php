<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;
use app\models\base\Unidades;
use app\models\base\Empresa;
use app\models\base\Comprador;
use app\models\base\ModalidadeValorlimite;

?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => ['class' => 'row gy-3 gx-3']
]); ?>

<!-- Linha 1: Código e Ano -->
<div class="col-md-6">
    <?= $form->field($model, 'prolic_sequenciamodal')->textInput([
        'placeholder' => 'Código do Processo...'
    ])->label('<i class="bi bi-hash text-muted me-1"></i> Código') ?>
</div>
<div class="col-md-6">
    <?php
    $anos = array_combine(
        range(date('Y') - 5, date('Y') + 1),
        range(date('Y') - 5, date('Y') + 1)
    );
    echo $form->field($model, 'ano')->widget(Select2::class, [
        'data' => $anos,
        'options' => ['placeholder' => 'Ano...'],
        'pluginOptions' => ['allowClear' => true],
    ])->label('<i class="bi bi-calendar text-muted me-1"></i> Ano');
    ?>
</div>

<!-- Linha 2: Objeto -->
<div class="col-md-12">
    <?= $form->field($model, 'prolic_objeto')->textInput([
        'placeholder' => 'Descrição do Objeto...'
    ])->label('<i class="bi bi-card-text text-muted me-1"></i> Objeto') ?>
</div>

<!-- Linha 3: Requisições e Demandante -->
<div class="col-md-6">
    <?= $form->field($model, 'prolic_codmxm')->textInput([
        'placeholder' => 'Ex: 1234;5678'
    ])->label('<i class="bi bi-list-ol text-muted me-1"></i> Requisições MXM') ?>
</div>
<div class="col-md-6">
    <?php
    $destinos = Unidades::find()
        ->where(['uni_codsituacao' => 1])
        ->orderBy('uni_nomeabreviado')
        ->all();
    echo $form->field($model, 'prolic_destino')->widget(Select2::class, [
        'data' => ArrayHelper::map($destinos, 'uni_codunidade', 'uni_nomeabreviado'),
        'options' => ['placeholder' => 'Unidades Demandantes...', 'multiple' => true],
        'pluginOptions' => ['allowClear' => true],
    ])->label('<i class="bi bi-building text-muted me-1"></i> Demandante(s)');
    ?>
</div>

<!-- Linha 4: Segmento -->
<div class="col-md-12">
    <?= $form->field($model, 'modalidade_valorlimite_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            ModalidadeValorlimite::find()->joinWith('ramo')->asArray()->all(),
            'id',
            'ramo.ram_descricao'
        ),
        'options' => ['placeholder' => 'Segmento...'],
        'pluginOptions' => ['allowClear' => true],
    ])->label('<i class="bi bi-diagram-3 text-muted me-1"></i> Segmento') ?>
</div>

<!-- Linha 5: Empresa -->
<div class="col-md-12">
    <?= $form->field($model, 'prolic_empresa')->textInput([
        'placeholder' => 'Empresa...'
    ])->label('<i class="bi bi-buildings text-muted me-1"></i> Empresa') ?>
</div>

<!-- Linha 6: Comprador -->
<div class="col-md-12">
    <?= $form->field($model, 'comprador_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Comprador::find()->orderBy('comp_descricao')->asArray()->all(), 'id', 'comp_descricao'),
        'options' => ['placeholder' => 'Comprador...'],
        'pluginOptions' => ['allowClear' => true],
    ])->label('<i class="bi bi-person-vcard text-muted me-1"></i> Comprador') ?>
</div>

<!-- Linha 7: Elemento de despesa e motivo -->
<div class="col-md-12">
    <?= $form->field($model, 'prolic_elementodespesa')->textInput([
        'placeholder' => 'Elemento...'
    ])->label('<i class="bi bi-stack text-muted me-1"></i> Elemento de Despesa') ?>
</div>

<!-- Botões -->
<div class="col-12 text-end pt-3">
    <?= Html::submitButton('<i class="bi bi-search me-1"></i> Pesquisar', ['class' => 'btn btn-primary me-2']) ?>
    <?= Html::resetButton('<i class="bi bi-x-circle me-1"></i> Limpar', ['class' => 'btn btn-outline-secondary']) ?>
</div>

<?php ActiveForm::end(); ?>