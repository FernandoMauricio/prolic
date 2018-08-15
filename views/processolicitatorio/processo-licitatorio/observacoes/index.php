<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\processolicitatorio\ObservacoesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Observacoes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="observacoes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Observacoes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'obs_descricao',
            'obs_usuariocriacao',
            'obs_datacriacao',
            'processo_licitatorio_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
