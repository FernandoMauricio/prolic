<?php

use yii\helpers\Html;

/** @var string $nome */
/** @var string $situacao */
/** @var array $processos */


// Mapeia as situações para classes de cor Bootstrap
function getSituacaoLabelClass($situacao)
{
    $map = [
        'Concluido' => 'success',
        'Cancelado' => 'default',
        'Em Andamento' => 'warning',
        'Em Homologação' => 'info',
        'Em Licitação' => 'primary',
        'Deserto' => 'danger',
    ];
    return $map[$situacao] ?? 'default';
}
?>

<h4>
    <strong><i class="glyphicon glyphicon-user"></i> <?= Html::encode($nome) ?></strong>
    — Situação: <span class="label label-<?= getSituacaoLabelClass($situacao) ?>">
        <?= Html::encode($situacao) ?>
    </span>
</h4>

<div class="list-group" style="margin-top: 15px;">
    <?php foreach ($processos as $p): ?>
        <div class="list-group-item">
            <h5>
                <i class="glyphicon glyphicon-file text-muted"></i>
                <strong>Processo:</strong> <?= Html::encode($p->prolic_codprocesso) ?>
            </h5>

            <p style="margin: 5px 0;">
                <span class="label label-primary" style="margin-right: 5px;">
                    <?= Html::encode($p->modalidadeValorlimite->modalidade->mod_descricao ?? '-') ?>
                </span>
                <span class="label label-info" style="margin-right: 5px;">
                    <?= Html::encode($p->modalidadeValorlimite->ramo->ram_descricao ?? '-') ?>
                </span>
                <span class="label label-success">
                    <?= Html::encode($p->recursos->rec_descricao ?? '-') ?>
                </span>
            </p>

            <p style="margin-top: 10px;">
                <strong>Objeto:</strong><br>
                <span title="<?= Html::encode($p->prolic_objeto) ?>">
                    <?= Html::encode(\yii\helpers\StringHelper::truncate($p->prolic_objeto, 120)) ?>
                </span>
            </p>
        </div>
    <?php endforeach; ?>
</div>