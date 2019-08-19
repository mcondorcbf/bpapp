<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_OBJ,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'BDDCBFUIOBPAPP01'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'ivrs' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('IVRS_DATABASE', 'bddivrs'),
            'username' => env('IVRS_USERNAME', 'root'),
            'password' => env('IVRS_PASSWORD', 'Cobefec*123'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'cdr' => [
            'driver' => 'mysql',
            'host' => env('CDRDB_HOST', '172.16.21.242'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('CDR_DATABASE', 'asteriskcdrdb'),
            'username' => env('CDR_USERNAME', 'root'),
            'password' => env('CDR_PASSWORD', 'passw0rd'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'movistar' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('MOVI_DATABASE', 'bddmovistar'),
            'username' => env('MOVI_USERNAME', 'root'),
            'password' => env('MOVI_PASSWORD', 'Cobefec*123'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'predictivo2' => [
            'driver' => 'mysql',
            'host' => env('PRD2DB_HOST', '172.16.5.230'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('PRD2_DATABASE', 'call_center'),
            'username' => env('PRD2_USERNAME', 'root'),
            'password' => env('PRD2_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'manual' => [
            'driver' => 'mysql',
            'host' => env('MANUAL_HOST', '172.16.5.8'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('MANUAL_DATABASE', 'ccl_ligero'),
            'username' => env('MANUAL_USERNAME', 'root'),
            'password' => env('MANUAL_PASSWORD', 'palosanto'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'elastixec' => [
            'driver' => 'mysql',
            'host' => env('CDRECDB_HOST', '172.16.5.185'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('CDREC_DATABASE', 'asteriskcdrdb'),
            'username' => env('CDREC_USERNAME', 'root'),
            'password' => env('CDREC_PASSWORD', 'palosanto'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'elastixpe' => [
            'driver' => 'mysql',
            'host' => env('CDRPEDB_HOST', '192.168.99.251'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('CDRPE_DATABASE', 'asteriskcdrdb'),
            'username' => env('CDRPE_USERNAME', 'root'),
            'password' => env('CDRPE_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'gestionec' => [
            'driver' => 'mysql',
            'host' => env('GESECDB_HOST', '172.16.5.25'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('GESEC_DATABASE', 'copiaprod'),
            'username' => env('GESEC_USERNAME', 'root'),
            'password' => env('GESEC_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'gestionpe' => [
            'driver' => 'mysql',
            'host' => env('GESPEDB_HOST', '192.168.99.252'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('GESPE_DATABASE', 'cobefec_pruebas'),
            'username' => env('GESPE_USERNAME', 'root'),
            'password' => env('GESPE_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'bmi' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '172.16.21.35'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('BMI_DATABASE', 'bddbmi'),
            'username' => env('BMI_USERNAME', 'root'),
            'password' => env('BMI_PASSWORD', 'Cobefec*123'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],



        'claro' => [
            'driver' => 'mysql',
            'host' => env('CLARO_HOST', '172.16.5.117'),
            'port' => env('CLARO_PORT', '3306'),
            'database' => env('CLARO_DATABASE', 'asteriskcdrdb'),
            'username' => env('CLARO_USERNAME', 'root'),
            'password' => env('CLARO_PASSWORD', 'palosanto'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'claro2' => [
            'driver' => 'mysql',
            'host' => env('CLARO2_HOST', '172.16.5.8'),
            'port' => env('CLARO2_PORT', '3306'),
            'database' => env('CLARO2_DATABASE', 'asteriskcdrdb'),
            'username' => env('CLARO2_USERNAME', 'root'),
            'password' => env('CLARO2_PASSWORD', 'palosanto'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'claro3' => [
            'driver' => 'mysql',
            'host' => env('CLARO3_HOST', '172.16.5.185'),
            'port' => env('CLARO3_PORT', '3306'),
            'database' => env('CLARO3_DATABASE', 'asteriskcdrdb'),
            'username' => env('CLARO3_USERNAME', 'root'),
            'password' => env('CLARO3_PASSWORD', 'palosanto'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'claro4' => [
            'driver' => 'mysql',
            'host' => env('CLARO4_HOST', '172.16.5.127'),
            'port' => env('CLARO4_PORT', '3306'),
            'database' => env('CLARO4_DATABASE', 'call_center'),
            'username' => env('CLARO4_USERNAME', 'root'),
            'password' => env('CLARO4_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'claro5' => [
            'driver' => 'mysql',
            'host' => env('CLARO4_HOST', '172.16.21.242'),
            'port' => env('CLARO4_PORT', '3306'),
            'database' => env('CLARO4_DATABASE', 'asteriskcdrdb'),
            'username' => env('CLARO4_USERNAME', 'root'),
            'password' => env('CLARO4_PASSWORD', 'passw0rd'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'movistar' => [
            'driver' => 'mysql',
            'host' => env('MOVISTAR_HOST', '172.16.5.150'),
            'port' => env('MOVISTAR_PORT', '3306'),
            'database' => env('MOVISTAR_DATABASE', 'asteriskcdrdb'),
            'username' => env('MOVISTAR_USERNAME', 'root'),
            'password' => env('MOVISTAR_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'cnt' => [
            'driver' => 'mysql',
            'host' => env('CNT_HOST', '172.16.5.14'),
            'port' => env('CNT_PORT', '3306'),
            'database' => env('CNT_DATABASE', 'asteriskcdrdb'),
            'username' => env('CNT_USERNAME', 'root'),
            'password' => env('CNT_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'callandbuy' => [
            'driver' => 'mysql',
            'host' => env('CLARO4_HOST', '172.16.5.21'),
            'port' => env('CLARO4_PORT', '3306'),
            'database' => env('CLARO4_DATABASE', 'asteriskcdrdb'),
            'username' => env('CLARO4_USERNAME', 'root'),
            'password' => env('CLARO4_PASSWORD', 'passw0rd'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'movistarPeru' => [
            'driver' => 'mysql',
            'host' => env('MOVISTAR_HOST', '192.168.99.251'),
            'port' => env('MOVISTAR_PORT', '3306'),
            'database' => env('MOVISTAR_DATABASE', 'asteriskcdrdb'),
            'username' => env('MOVISTAR_USERNAME', 'root'),
            'password' => env('MOVISTAR_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'cobefec3' => [
            'driver' => 'mysql',
            'host' => env('COBEFEC3_HOST', '172.16.5.89'),
            'port' => env('COBEFEC3_PORT', '3306'),
            'database' => env('COBEFEC3_DATABASE', 'cobefec3'),
            'username' => env('COBEFEC3_USERNAME', 'root'),
            'password' => env('COBEFEC3_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'options'   => [PDO::ATTR_EMULATE_PREPARES => true]
        ],

        'encuestascex' => [
            'driver' => 'mysql',
            'host' => env('ENCUESTASCEX_HOST', '172.16.21.35'),
            'port' => env('ENCUESTASCEX_PORT', '3306'),
            'database' => env('ENCUESTASCEX_DATABASE', 'bddencuestascex'),
            'username' => env('ENCUESTASCEX_USERNAME', 'root'),
            'password' => env('ENCUESTASCEX_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'cobefec3Reportes' => [
            'driver' => 'mysql',
            'host' => env('COBEFEC3REPORTES_HOST', '172.16.5.51'),
            'port' => env('COBEFEC3REPORTES_PORT', '3306'),
            'database' => env('COBEFEC3REPORTES_DATABASE', 'cobefec_reportes'),
            'username' => env('COBEFEC3REPORTES_USERNAME', 'root'),
            'password' => env('COBEFEC3REPORTES_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'cuentasx88' => [
            'driver' => 'mysql',
            'host' => env('CUENTASX88_HOST', '127.0.0.1'),
            'port' => env('CUENTASX88_PORT', '3306'),
            'database' => env('CUENTASX88_DATABASE', 'bddcx88'),
            'username' => env('CUENTASX88_USERNAME', 'root'),
            'password' => env('CUENTASX88_PASSWORD', 'Cobefec*123'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'nomina' => [
            'driver' => 'mysql',
            'host' => env('NOMINA_HOST', '127.0.0.1'),
            'port' => env('NOMINA_PORT', '3306'),
            'database' => env('NOMINA_DATABASE', 'bddroles'),
            'username' => env('NOMINA_USERNAME', 'root'),
            'password' => env('NOMINA_PASSWORD', 'Cobefec*123'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'cobefec3151' => [
            'driver' => 'mysql',
            'host' => env('COBEFEC3REPORTES_HOST', '172.16.5.151'),
            'port' => env('COBEFEC3REPORTES_PORT', '3306'),
            'database' => env('COBEFEC3REPORTES_DATABASE', 'cobefec_reportes'),
            'username' => env('COBEFEC3REPORTES_USERNAME', 'root'),
            'password' => env('COBEFEC3REPORTES_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'cobefec3Peru' => [
            'driver' => 'mysql',
            'host' => env('COBEFEC3PERU_HOST', '192.168.99.51'),
            'port' => env('COBEFEC3PERU_PORT', '3306'),
            'database' => env('COBEFEC3PERU_DATABASE', 'cobefec3'),
            'username' => env('COBEFEC3PERU_USERNAME', 'root'),
            'password' => env('COBEFEC3PERU_PASSWORD', 'C0b3f3c-'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'options'   => array(
                PDO::ATTR_CASE => PDO::CASE_LOWER,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                PDO::ATTR_STRINGIFY_FETCHES => true,
                PDO::ATTR_EMULATE_PREPARES => true,
            ),
        ],

        'apiRest' => [
            'driver' => 'mysql',
            'host' => env('APIREST_HOST', '172.16.21.35'),
            'port' => env('APIREST_PORT', '3306'),
            'database' => env('APIREST_DATABASE', 'bddapirest'),
            'username' => env('APIREST_USERNAME', 'root'),
            'password' => env('APIREST_PASSWORD', 'Cobefec*123'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'options'   => array(
                PDO::ATTR_CASE => PDO::CASE_LOWER,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                PDO::ATTR_STRINGIFY_FETCHES => true,
                PDO::ATTR_EMULATE_PREPARES => true,
            ),
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
