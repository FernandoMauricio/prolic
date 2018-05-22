<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\models\processolicitatorio\ProcessoLicitatorio;

?>
<link href="../web/css/print-style.css" rel="stylesheet">

<div class="capa-padrao-view">

<table class="table table-bordered">
  <tbody>
    <tr>
      <td width="10%"><img style="width: 100px;" src="css/img/logo.png"></td>
      <td colspan="6">SERVIÇO NACIONAL DE APRENDIZAGEM COMERCIAL - SENAC<br />
                      DEPARTAMENTO REGIONAL NO AMAZONAS<br /></td>
    </tr>
    <tr>
      <th>PROCESSO Nº</th>
      <td><?= $model->id ?></td>
      <th>MODALIDADE</th>
      <td><?= $model->modalidadeValorlimite->modalidade->mod_descricao ?></td>
      <td><?= $model->prolic_sequenciamodal ?></td>
      <th>RECURSOS</th>
      <td><?= $model->recursos->rec_descricao ?></td>
    </tr>
    <tr>
      <td colspan="3"><?= $model->artigo->art_descricao ?></td>
      <th>DATA CERTAME</th>
      <td><?= $model->prolic_datacertame != NULL ? date('d/m/Y', strtotime($model->prolic_datacertame)) : '' ?></td>
      <th>ENCAMINHAR P/ HOMOLOGAÇÃO ATÉ</th>
      <td><?= $model->prolic_datahomologacao != NULL ? date('d/m/Y', strtotime($model->prolic_datahomologacao)) : ''?></td>
    </tr>
    <tr>
      <th>OBJETO</th>
      <td colspan="6"><textarea cols="140" rows="4" style="border-style: none;margin: 0px;width: 912px;"><?= $model->prolic_objeto ?></textarea></td>
    </tr>
    <tr>
      <th>DESTINOS</th>
      <td colspan="6"><?= ProcessoLicitatorio::getUnidades($model->prolic_destino) ?></td>
    </tr>
    <tr>
      <th>EMPRESA(S) VENCEDORA(S)</th>
      <td colspan="6"><?= $model->prolic_empresa ?></td>
    </tr>
    <tr>
      <th>DESPESA ESTIMADA</th>
      <td><?= 'R$ ' . number_format($model->prolic_valorestimado, 2, ',', '.'); ?></td>
      <th>DESPESA EFETIVA</th>
      <td><?= 'R$ ' . number_format($model->prolic_valorefetivo, 2, ',', '.'); ?></td>
      <th>COMPRADOR</th>
      <td colspan="2"><?= $model->comprador->comp_descricao ?></td>
    </tr>
    <tr>
      <th class="info" colspan="7" style="text-align: center;">DESPACHOS</th>
    </tr>
    <tr>
      <th>DE</th>
      <td></td>
      <th>PARA</th>
      <td></td>
      <th>DATA:</th>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td colspan="7" style="border-style: 1px;height: 850px;" ></td>
    </tr>
  </tbody>
</table>

</div>
