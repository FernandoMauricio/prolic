<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\base\Ramo */

$this->title = 'Novo Ramo';
$this->params['breadcrumbs'][] = ['label' => 'Listagem de Ramos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ramo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
