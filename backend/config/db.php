<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=tailor',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
    'tablePrefix' => '',
    //开启数据库字段缓存
    // 'enableSchemaCache'=>true,
    //字段缓存生效时间
    'schemaCacheDuration' => 3600,
    //字段换从使用的组件名称
    'schemaCache' => 'cache',
];
