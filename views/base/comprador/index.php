<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\CompradorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Compradors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comprador-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Comprador', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'comp_descricao',
            'comp_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
