<?php
/* @var $this yii\web\View */
// namespace yii\bootstrap;
use yii\helpers\Html;
use app\models\Comunicacaointerna;
use app\models\Destinocomunicacao;
use yii\helpers\ArrayHelper;

$this->title = 'Processo Seletivo - Senac AM';

?>

<div class="site-index">
    <h1 class="text-center"> Histórico de Versões</h1>
        <div class="body-content">
            <div class="panel panel-primary">
                <div class="panel-heading"><i class="glyphicon glyphicon-star-empty"></i> O que há de novo? - Versão 1.1 - Publicado em 06/06/2018</div>
                <div class="panel-body">
                    <h4><b style="color: #337ab7;">Implementações</b></h4>
                        <h5><i class="glyphicon glyphicon-tag"></i><b> Curriculos</b></h5>
                            <h5>- Criado a tela para acompanhamento Gerencial;</h5>
                            <h5>- Criado a pesquisa avançada contendo campos que estão ocultos na listagem dos processos;</h5>
                    <h4><b style="color: #337ab7;">Correções</b></h4>
                        <h5><i class="glyphicon glyphicon-tag"></i><b> Processos Licitatórios</b></h5>
                            <h5> - Correção do formato das datas para o padrão brasileiro;</h5>
                            <h5> - Ocultado campos como Cód. do Processo / Cód. da Modalidade / Artigo da listagem de Processos Licitatórios;</h5>
                            <h5> - Bloqueio na atualização dos campos: modalidade e ramo na tela de cadastro do valor limite;</h5><br />

                        <p><a href="index.php?r=site/versao" class="btn btn-warning" role="button">Histórico de Versões</a></p>
                </div>
            </div>
    </div>
</div>