<?php

use yii\helpers\Html;

/** @var array $processos */
?>

<ul class="list-group">
    <?php foreach ($processos as $p): ?>
        <li class="list-group-item">
            <h5><strong>Processo:</strong> <?= Html::encode($p->prolic_codprocesso . '/' . $p->ano->an_ano) ?></h5>

            <p>
                <span class="label label-primary" style="margin-right: 5px;">
                    <?= Html::encode($p->modalidadeValorlimite['modalidade']['mod_descricao']) ?>
                </span>
                <span class="label label-info" style="margin-right: 5px;">
                    <?= Html::encode($p->modalidadeValorlimite['ramo']['ram_descricao']) ?>
                </span>
                <span class="label label-success">
                    <?= Html::encode($p->recursos['rec_descricao']) ?>
                </span>
            </p>

            <p style="margin-top: 10px;">
                <strong>Objeto:</strong><br>
                <?= Html::encode($p->prolic_objeto) ?>
            </p>
        </li>
    <?php endforeach; ?>
</ul>