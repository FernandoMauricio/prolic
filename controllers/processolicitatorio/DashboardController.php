<?php

namespace app\controllers\processolicitatorio;

use Yii;
use yii\web\Controller;
use app\models\services\DashboardService;
use app\models\FiltroDashboardForm;


class DashboardController extends Controller
{
    public function actionIndex()
    {
        $filtroModel = new FiltroDashboardForm();
        $filtroModel->load(Yii::$app->request->get());

        return $this->render('index', [
            'filtroModel' => $filtroModel,
            'kpi' => DashboardService::getKpis($filtroModel),
            'modalidades' => DashboardService::getDistribuicaoPorModalidade($filtroModel),
            'situacoes' => DashboardService::getDistribuicaoPorSituacao($filtroModel),
            'topCompradores' => DashboardService::getTopCompradores($filtroModel),
            'alertas' => DashboardService::getAlertas($filtroModel),
            'anosDisponiveis' => DashboardService::getAnosDisponiveis(),
        ]);
    }
}
