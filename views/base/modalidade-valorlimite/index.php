<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\ModalidadeValorlimiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modalidade Valorlimites';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modalidade-valorlimite-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Modalidade Valorlimite', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'modalidade_id',
            'ramo_id',
            'ano_id',
            'valor_limite',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
