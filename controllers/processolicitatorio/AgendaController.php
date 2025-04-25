<?php

namespace app\controllers\processolicitatorio;

use Yii;
use yii\web\Controller;

class AgendaController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', []);
    }
}
