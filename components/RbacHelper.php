<?php

namespace app\components;

use Yii;

class RbacHelper
{
    public static function isAdmin(): bool
    {
        return Yii::$app->session->get('sess_codunidade') == 6;
    }

    public static function isOnlyViewer(): bool
    {
        return !self::isAdmin();
    }

    public static function ensureAdminAccess(): void
    {
        if (!self::isAdmin()) {
            Yii::$app->controller->redirect(['protalsenac'])->send();
            Yii::$app->end();
        }
    }
}
