<?php

namespace app\commands;

use yii\console\Controller;

class CacheController extends Controller
{
    public function actionPing()
    {
        echo "pong\n";
    }
}
