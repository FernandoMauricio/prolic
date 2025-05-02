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
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap min-vh-100 d-flex flex-column">

        <?php include('navbar.php'); ?>

        <main class="flex-grow-1 py-4">
            <div class="container" style="max-width: 100%">
                <div class="wrap" style="padding-top: 80px;">
                    <?= Breadcrumbs::widget([
                        'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
                        'activeItemTemplate' => "<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n",
                        'links' => $this->params['breadcrumbs'] ?? [],
                        'options' => ['class' => 'breadcrumb bg-light px-3 py-2 rounded mb-3']
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

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>