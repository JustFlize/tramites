<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'modules' => [
        'user' => [
            'class' => Da\User\Module::class,
            'allowUnconfirmedEmailLogin' => false, //No permitir acceso sin confirmar email
            'enableRegistration' => true, //registros de usuarios
            'enableEmailConfirmation' => true, //bligar a confirmar el email antes de acceder
            'administratorPermissionName' => 'admin', //nombre del rol de administrador
            // php yii migrate/up --migrationPath=@Da/User/migrations

        ],
    ],

    'components' => [

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '9HuLFjdQakxaNTVS8dawjPSW0dICqkDV',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        /*'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        */

        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => Da\User\Model\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['/user/security/login'], // Ruta de inicio de sesión
        ],


        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                /*
                'login' => 'user/security/login',
                'logout' => 'user/security/logout',
                'register' => 'user/registration/register',
                'request-password-reset' => 'user/recovery/request',
                */
            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
