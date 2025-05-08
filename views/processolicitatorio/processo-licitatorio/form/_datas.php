<?php

use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;

?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-4">
                <?= $form->field($model, 'prolic_datacertame')->widget(DateControl::class, [
                    'type' => DateControl::FORMAT_DATE,
                    'ajaxConversion' => false,
                    'widgetOptions' => ['pluginOptions' => ['autoclose' => true]],
                ]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'prolic_datadevolucao')->widget(DateControl::class, [
                    'type' => DateControl::FORMAT_DATE,
                    'ajaxConversion' => false,
                    'widgetOptions' => ['pluginOptions' => ['autoclose' => true]],
                ]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'prolic_datahomologacao')->widget(DateControl::class, [
                    'type' => DateControl::FORMAT_DATE,
                    'ajaxConversion' => false,
                    'widgetOptions' => ['pluginOptions' => ['autoclose' => true]],
                ]) ?>
            </div>
        </div>
    </div>
</div>