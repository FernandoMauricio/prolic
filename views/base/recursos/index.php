<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\RecursosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listagem de Recursos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recursos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Novo Recurso', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rec_descricao',
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'rec_status', 
                'vAlign'=>'middle'
            ], 

            ['class' => 'yii\grid\ActionColumn','template' => '{update}'],
        ],
    ]); ?>
</div>
