<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\RecursosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recursos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recursos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Recursos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'rec_descricao',
            'rec_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
