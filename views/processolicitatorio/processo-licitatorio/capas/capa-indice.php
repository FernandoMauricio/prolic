<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

?>
<link href="../web/css/print-style.css" rel="stylesheet">

<div class="capa-padrao-view">

<table class="table table-bordered">
  <tbody>
    <tr>
     <td class="logo-esquerda"><img style="width: 100px;" src="css/img/logo.png"></td>
     <td colspan="3" style="text-align: center;padding-top: 30px;"><b>INDICE RESUMIDO PARA ARQUIVO</b></td>
    </tr>
    <tr>
      <th> PROCESSO Nº</th>
      <td><?= $model->id ?></td>
      <td><?= $model->modalidadeValorlimite->modalidade->mod_descricao ?></td>
      <td><?= $model->prolic_sequenciamodal ?></td>
    </tr>
  </tbody>
</table>

<table class="table table-bordered">
  <tbody>
    <tr>
      <td colspan="2"></td>
      <td style="text-align: center;"> DE</td>
      <td style="text-align: center;"> ATÉ</td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"> REQUISIÇÃO DE COMPRA</td>
      <td style="padding: 30px;"></td>
      <td style="padding: 30px;"></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"> EDITAL E PLANILHA DE COTAÇÃO</td>
      <td style="padding: 30px;"></td>
      <td style="padding: 30px;"></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;">RELAÇÃO DE EMPRESAS</td>
      <td style="padding: 30px;"></td>
      <td style="padding: 30px;"></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"> DOCUMENTAÇÃO</td>
      <td style="padding: 30px;"></td>
      <td style="padding: 30px;"></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"> PROPOSTAS COMERCIAIS</td>
      <td style="padding: 30px;"></td>
      <td style="padding: 30px;"></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"> MAPA DE APURAÇÃO</td>
      <td style="padding: 30px;"></td>
      <td style="padding: 30px;"></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"> HOMOLOGAÇÃO E ADJUDICAÇÃO</td>
      <td style="padding: 30px;"></td>
      <td style="padding: 30px;"></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"> ORDEM DE COMPRA</td>
      <td style="padding: 30px;"></td>
      <td style="padding: 30px;"></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"> REGISTRO DE ATENDIMENTO / NOTAS FISCAIS</td>
      <td style="padding: 30px;"></td>
      <td style="padding: 30px;"></td>
    </tr>
  </tbody>
</table>

<table class="table table-bordered">
  <tbody>
    <tr>
      <td style="text-align: center;"><b> ÁREA RESERVADO À GMA</b></td>
    </tr>
    <tr>
      <td style="padding-top: 30px">
        <p> DATA DO ARQUIVAMENTO DO PROCESSO: _____/______/_______</p>
        <p> RESPONSÁVEL PELO ARQUIVAMENTO DO PROCESSO:</p>
        <p> VISTO DA GERÊNCIA DE COMPRAS:</p>
      </td>
    </tr>
    <tr>
      <td style="padding-bottom: 100px"></td>
    </tr>
  </tbody>
</table>
<div style="text-align: justify;">
<p>1 - As páginas do processo devem ser numeradas em ordem sequencial, obedecendo a ordem cronológica dos fatos; Exemplo: 1.0, 2.0, 3.0 e etc.</p>
<p>2 - Ao final do processo, o COMPRADOR responsável pelo processo deverá observar a sequência de numeração, e caso as peças não estejam totalmente numeradas, o mesmo deverá concluir a operação.</p>
<p>3 - No índice, o responsável deverá registrar de forma manual, utilizando CANETA PRETA, a numeração INICIAL E FINAL DE cada tópico. Exemplo: Requisição de Compra DE: 1.0 ATÉ 5.0.</p>
<p>4 - As peças como ordem de compra, notas fiscais e registro de atendimento devem ser numerados observando as orientações dos itens acima.</p>
<p>5 - O gerente de compras é responsável por acompanhar o arquivamento do processo, observando se todas as peças estão inseridas no processo e devidamente numeradas.</p>
<p>6 - A ausência de peças e com sequência numérica encerrada, caracterizará Não Conformidade.</p><br />
<p style="text-align: right;"> Divisão Administrativa</p>
</div>
</div>
