<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'oci:dbname=//' . $_ENV['ORACLE_DB_HOST'] . ':' . $_ENV['ORACLE_DB_PORT'] . '/' . $_ENV['ORACLE_DB_SID'],
    'username' => $_ENV['ORACLE_DB_USER'],
    'password' => $_ENV['ORACLE_DB_PASS'],
    'charset' => 'AL32UTF8',
];
