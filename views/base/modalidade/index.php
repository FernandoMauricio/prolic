<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\ModalidadeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listagem de Modalidades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modalidade-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nova Modalidade', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'mod_descricao',
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'mod_status', 
                'vAlign'=>'middle'
            ], 

            ['class' => 'yii\grid\ActionColumn','template' => '{update}'],
        ],
    ]); ?>
</div>
