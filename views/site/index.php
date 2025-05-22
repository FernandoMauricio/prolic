<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
$session = Yii::$app->session;
$this->title = 'Processos Licitatórios - PROLIC';
?>

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">
            <i class="bi bi-file-earmark-text-fill me-2"></i> PROLIC
        </h1>
        <p class="lead text-muted">Sistema de Acompanhamento de Processos Licitatórios</p>
    </div>

    <div class="row g-4 justify-content-center">

        <?php if ($session['sess_codunidade'] == 6): ?>
            <!-- Admin - Cards completos -->

            <!-- Consulta de Requisições -->
            <div class="col-md-4">
                <a href="<?= Url::to(['/mxm/reqcompra-rco/index']) ?>" class="text-decoration-none">
                    <div class="card bg-light shadow-lg border-0 hover-shadow rounded-4 h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-basket2-fill text-danger display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Requisições de Compras</h5>
                            <p class="card-text text-muted">Consulte e acompanhe as requisições registradas no MXM.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Processos Licitatórios -->
            <div class="col-md-4">
                <a href="<?= Url::to(['/processolicitatorio/processo-licitatorio/index']) ?>" class="text-decoration-none">
                    <div class="card bg-light shadow-lg border-0 hover-shadow rounded-4 h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-clipboard-data text-info display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Processos Licitatórios</h5>
                            <p class="card-text text-muted">Gerencie todos os processos cadastrados.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Agenda -->
            <div class="col-md-4">
                <a href="<?= Url::to(['/processolicitatorio/agenda/index']) ?>" class="text-decoration-none">
                    <div class="card bg-light shadow-lg border-0 hover-shadow rounded-4 h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar3 text-success display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Agenda de Compromissos</h5>
                            <p class="card-text text-muted">Gerencie datas e eventos dos processos.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Dashboard -->
            <div class="col-md-4">
                <a href="<?= Url::to(['/processolicitatorio/dashboard/index']) ?>" class="text-decoration-none">
                    <div class="card bg-light shadow-lg border-0 hover-shadow rounded-4 h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-bar-chart-line-fill text-warning display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Acompanhamento</h5>
                            <p class="card-text text-muted">Visualize relatórios e análises de dados.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Parâmetros -->
            <div class="col-md-4">
                <a href="<?= Url::to(['/site/parametros']) ?>" class="text-decoration-none">
                    <div class="card bg-light shadow-lg border-0 hover-shadow rounded-4 h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-sliders text-secondary display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Parâmetros</h5>
                            <p class="card-text text-muted">Configure artigos, modalidades, empresas e mais.</p>
                        </div>
                    </div>
                </a>
            </div>

        <?php else: ?>
            <!-- Usuário comum - apenas consulta -->
            <!-- Consulta de Requisições -->
            <div class="col-md-4">
                <a href="<?= Url::to(['/mxm/reqcompra-rco/index']) ?>" class="text-decoration-none">
                    <div class="card bg-light shadow-lg border-0 hover-shadow rounded-4 h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-basket2-fill text-danger display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Requisições de Compras</h5>
                            <p class="card-text text-muted">Consulte e acompanhe as requisições registradas no MXM.</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="<?= Url::to(['/processolicitatorio/processo-licitatorio/index']) ?>" class="text-decoration-none">
                    <div class="card bg-light shadow-lg border-0 hover-shadow rounded-4 h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-file-text text-primary display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Processos Licitatórios</h5>
                            <p class="card-text text-muted">Consulte os processos vinculados ao seu setor.</p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
    .hover-shadow {
        transition: all 0.2s ease-in-out;
    }

    .hover-shadow:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
    }
</style>