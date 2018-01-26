<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\Ramo */

$this->title = 'Atualizar Ramo: '.$model->id.'';
$this->params['breadcrumbs'][] = ['label' => 'Ramos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="ramo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
