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
     <td class="logo-esquerda"><img style="width: 150px;" src="css/img/logo-fecomercio.png"></td>
     <td colspan="5" style="text-align: center;padding-top: 30px;"><b>DESPACHOS E ENCAMINHAMENTOS</b></td>
     <td class="logo-direita"><img style="width: 100px;" src="css/img/logo.png"></td>
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
      <th>ASSUNTO</th>
      <td colspan="6"><textarea cols="140" rows="4" style="border-style: none;margin: 0px;width: 912px;"><?= $model->prolic_objeto ?></textarea></td>
    </tr>
    <tr>
      <th>EMPRESA(S) VENCEDORA(S)</th>
      <td colspan="6"><?= $model->prolic_empresa ?></td>
    </tr>
    <tr>
      <th>DESTINOS</th>
      <td colspan="6"><?= ProcessoLicitatorio::getUnidades($model->prolic_destino) ?></td>
    </tr>
    <tr>
      <td rowspan="4" colspan="5">
        <p><b> Sr(a). Gerente</b></p>
        <p> Atendendo a solicitação do(s) solicitante(s) acima, foram consultadas <b><?= $model->prolic_cotacoes; ?> empresas</b> especializadas no ramo <b><?= $model->modalidadeValorlimite->ramo->ram_descricao; ?></b>, obetendo as referidas empresas: </p>
        <p><b><?= str_replace(" / ","<br>", $model->prolic_empresa); ?></b></p>
        <p> A ofera mais vantajosa para o SENAC/AM, conforme mapa de cotação anexo no valor total de:</p>
        <p><b><?= 'R$ ' . number_format($model->prolic_valorefetivo, 2, ',', '.'); ?></b></p>
        <p> Este processo está amparado pela <b> <?= $model->artigo->art_descricao ?></b>.</p>
        <p> Há recursos para o atendimento desta despesa:</p>
        <p> CENTRO DE CUSTO: <b><?= $model->prolic_centrocusto?></b></p>
        <p> ELEMENTO: <b><?= $model->prolic_elementodespesa?></b></p>
        <P> Diante do exposto, submetemos a V.Sª. para análise e homologação.</P>
      </td>
      <th colspan="2" class="info" style="text-align: center;">AUTORIZAÇÃO / ENCAMINHAMENTO</th>
    </tr>
    <tr>
      <td colspan="2">
        <p> [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;] Homologo e adjudico o objeto deste à(s) empresa(s) vencedora(s)</p>
        <p> [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;] Não Homologo</p>
        <p> [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;] À DRG para homologação</p><br /><br /><br />
        <p style="text-align: center;"><b> Neilon Márcio Batista da Silva</b><br />
        Gerência Administrativa<br /><br />
        Data:_____/_____/_______</p>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <p> [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;] Homologo e adjudico o objeto deste à(s) empresa(s) vencedora(s)</p>
        <p> [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;] Não Homologo</p>
        <p> [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;] Ao Sr. Presidente para homologação</p><br /><br /><br />
        <p style="text-align: center;"><b> Silvana Maria Ferreira de Carvalho</b><br />
        Direção Regional<br /><br />
        Data:_____/_____/_______</p>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <p> [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;] Homologo e adjudico o objeto deste à(s) empresa(s) vencedora(s)</p>
        <p> [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;] Não Homologo</p><br /><br />
        <p style="text-align: center;"><b> José Roberto Tadros</b><br />
        Presidência<br /><br />
        Data:_____/_____/_______</p>
      </td>
    </tr>
    <tr>
      <td colspan="5" style="text-align: center;padding-top: 50px;padding-bottom: 40px;"><b> Gerente de Material</b><br />
      Data:________/_______/_____________</td>
      <td colspan="2"><b> OBSERVAÇÕES</b></td>
    </tr>
    <tr>
      <td colspan="4" style="border-right-style: hidden;">
        <b>Autorização de Despesas - Resolução 014/2016</b><br />
        <b>Gerência Administrativa</b> - Até R$ 2.500,00<br />
        <b>Direção Regional</b> - Acima de R$ 2.500,00 até R$ 6.500,00<br />
        <b>Presidência</b> - Acima de R$ 6.500,00<br />
      </td>
      <td colspan="4">Comprador: <?= $model->comprador->comp_descricao; ?></td>
    </tr>
  </tbody>
</table>

</div>
