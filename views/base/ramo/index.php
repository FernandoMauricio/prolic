<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\RamoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ramos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ramo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ramo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'ram_descricao',
            'ram_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
