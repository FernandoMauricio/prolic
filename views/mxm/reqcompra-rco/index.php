<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\oracle\ReqcompraRcoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requisições de Compra';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'RCO_NUMERO',
        'RCO_DATA',
        'RCO_EMPRESA',
        'RCO_TIPO',
        'RCO_REQUISITANTE',
        'RCO_MOEDA',
        // outros campos...

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>