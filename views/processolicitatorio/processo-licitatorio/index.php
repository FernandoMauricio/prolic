<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\processolicitatorio\ProcessoLicitatorioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Processo Licitatorios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="processo-licitatorio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Processo Licitatorio', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'ano_id',
            'prolic_objeto:ntext',
            'prolic_codmxm',
            'prolic_destino:ntext',
            //'modalidade_valorlimite_id',
            //'prolic_sequenciamodal',
            //'artigo_id',
            //'prolic_cotacoes',
            //'prolic_centrocusto:ntext',
            //'prolic_elementodespesa:ntext',
            //'prolic_valorestimado',
            //'prolic_valoraditivo',
            //'prolic_valorefetivo',
            //'recursos_id',
            //'comprador_id',
            //'prolic_datacertame',
            //'prolic_datadevolucao',
            //'situacao_id',
            //'prolic_datahomologacao',
            //'prolic_motivo:ntext',
            //'prolic_empresa',
            //'ramo_descricao',
            //'prolic_usuariocriacao',
            //'prolic_datacriacao',
            //'prolic_usuarioatualizacao',
            //'prolic_dataatualizacao',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
