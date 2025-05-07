<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap min-vh-100 d-flex flex-column">

        <?php include('navbar.php'); ?>

        <main class="flex-grow-1 py-4">
            <div class="container" style="max-width: 100%">
                <div class="wrap" style="padding-top: 80px;">
                    <?= Breadcrumbs::widget([
                        // 1) Primeiro item “Home” com ícone e template próprio
                        'homeLink' => [
                            'label'    => '<i class="bi bi-house-door-fill"></i>',
                            'url'      => Yii::$app->homeUrl,
                            'encode'   => false,
                            'template' => '<li class="breadcrumb-item me-1">{link}</li>',
                        ],
                        // 2) Seus breadcrumbs normais (strings ou arrays [‘label’=>…, ‘url’=>…])
                        'links' => $this->params['breadcrumbs'] ?? [],

                        // 3) Template para cada link intermediário
                        'itemTemplate'       => '<li class="breadcrumb-item me-1">{link}</li>',
                        // 4) Template para o link ativo (último)
                        'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',

                        // 5) Permite usar HTML (para ícones, spans, etc.)
                        'encodeLabels' => false,

                        // 6) Classe do container e outros atributos
                        'options'      => [
                            'class'      => 'breadcrumb shadow-sm p-2 rounded mb-4',
                            'style'      => 'background-color: #f5f5f5;',
                            'aria-label' => 'breadcrumb',
                        ],
                    ]) ?>
                </div>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </main>

        <footer class="footer mt-auto py-3 bg-light border-top">
            <div class="container d-flex justify-content-between small text-muted">
                <span>&copy; Gerência de Tecnologia da Informação - GTI <?= date('Y') ?></span>
                <span>PROLIC (v. 1.1)</span>
            </div>
        </footer>

    </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>