<?php

namespace app\widgets;

use Yii;
use yii\bootstrap5\Alert as BootstrapAlert;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Alert extends \yii\bootstrap5\Widget
{
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning',
    ];

    public $closeButton = [];

    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        // Trata flash personalizada
        if (isset($flashes['empresaAtualizadaViaApi'])) {
            $flashes['info'] = array_merge(
                (array) ($flashes['info'] ?? []),
                (array) $flashes['empresaAtualizadaViaApi']
            );
            unset($flashes['empresaAtualizadaViaApi']);
        }

        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        $icons = [
            'success' => 'check-circle-fill',
            'info' => 'info-circle-fill',
            'warning' => 'exclamation-triangle-fill',
            'danger' => 'x-circle-fill',
            'error' => 'x-circle-fill',
        ];

        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            foreach ((array) $flash as $i => $message) {
                $icon = isset($icons[$type]) ? "<i class='bi bi-{$icons[$type]} me-2'></i>" : '';

                echo BootstrapAlert::widget([
                    'body' => $icon . $message,
                    'closeButton' => $this->closeButton,
                    'options' => array_merge($this->options, [
                        'id' => $this->getId() . '-' . $type . '-' . $i,
                        'class' => $this->alertTypes[$type] . ' alert-dismissible fade show' . $appendClass,
                        'role' => 'alert',
                    ]),
                ]);
            }

            $session->removeFlash($type);
        }
    }
}
