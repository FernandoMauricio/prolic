<?php
$this->title = 'Acesso Negado';
?>

<div class="d-flex flex-column justify-content-center align-items-center vh-100 text-center bg-light">

    <div class="card shadow border-0 p-4" style="max-width: 480px;">
        <div class="card-body">
            <i class="bi bi-shield-lock-fill text-danger display-4 mb-3"></i>
            <h1 class="h4 fw-bold text-danger mb-3">Acesso Negado</h1>
            <p class="text-muted mb-4">
                Você não possui permissão para acessar este módulo.
                Caso acredite que isso seja um erro, entre em contato com o administrador do sistema.
            </p>
            <?php
            $urlAnterior = Yii::$app->request->referrer ?? 'https://portalsenac.am.senac.br';
            ?>
            <a href="<?= $urlAnterior ?>" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left me-1"></i> Retornar
            </a>

        </div>
    </div>

</div>