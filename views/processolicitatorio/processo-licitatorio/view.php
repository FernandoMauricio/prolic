<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Processo Licitatorios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="processo-licitatorio-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ano_id',
            'prolic_objeto:ntext',
            'prolic_codmxm',
            'prolic_destino:ntext',
            'modalidade_valorlimite_id',
            'prolic_sequenciamodal',
            'artigo_id',
            'prolic_cotacoes',
            'prolic_centrocusto:ntext',
            'prolic_elementodespesa:ntext',
            'prolic_valorestimado',
            'prolic_valoraditivo',
            'prolic_valorefetivo',
            'recursos_id',
            'comprador_id',
            'prolic_datacertame',
            'prolic_datadevolucao',
            'situacao_id',
            'prolic_datahomologacao',
            'prolic_motivo:ntext',
            'prolic_usuariocriacao',
            'prolic_datacriacao',
            'prolic_usuarioatualizacao',
            'prolic_dataatualizacao',
        ],
    ]) ?>

</div>
