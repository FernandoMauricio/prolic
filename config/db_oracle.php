<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'oci:dbname=(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=' . $_ENV['ORACLE_DB_HOST'] . ')(PORT=' . $_ENV['ORACLE_DB_PORT'] . ')))(CONNECT_DATA=(SERVICE_NAME=' . $_ENV['ORACLE_DB_SERVICE'] . ')));charset=WE8MSWIN1252',
    'username' => $_ENV['ORACLE_DB_USER'],
    'password' => $_ENV['ORACLE_DB_PASS'],
    'charset' => 'AL32UTF8',
];
