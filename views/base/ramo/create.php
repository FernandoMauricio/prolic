<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\base\Ramo */

$this->title = 'Novo Segmento';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = ['label' => 'Segmentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ramo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>