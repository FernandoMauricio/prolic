<?php

use Dotenv\Dotenv;

// Força leitura manual, mesmo que falhe antes no index.php
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad(); // ← não lança erro se .env estiver ausente

return [
    'mxm.baseUrl' => getenv('MXM_BASE_URL'),
    'mxm.username' => getenv('MXM_USERNAME'),
    'mxm.password' => getenv('MXM_PASSWORD'),
    'mxm.env'      => getenv('MXM_ENV'),
];
