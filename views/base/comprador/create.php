<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\base\Comprador */

$this->title = 'Create Comprador';
$this->params['breadcrumbs'][] = ['label' => 'Compradors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comprador-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
