<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'userModel' => [
            'driver' => 'session',
            'provider' => 'userModel',
        ],

        'userVerify' => [
            'driver' => 'session',
            'provider' => 'userVerify',
        ],

        'roomModel' => [
            'driver' => 'session',
            'provider' => 'roomModel',
        ],

        'reservationModel' => [
            'driver' => 'session',
            'provider' => 'reservationModel',
        ],

        'reasonDeclineModel' => [
            'driver' => 'session',
            'provider' => 'reasonDeclineModel',
        ],

        'reasonBackOutModel' => [
            'driver' => 'session',
            'provider' => 'reasonBackOutModel',
        ],

    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'userModel' => [
            'driver' => 'eloquent',
            'model' => App\Models\userModel::class,
        ],

        'userVerify' => [
            'driver' => 'eloquent',
            'model' => App\Models\userVerify::class,
        ],

        'roomModel' => [
            'driver' => 'eloquent',
            'model' => App\Models\roomModel::class,
        ],

        'reservationModel' => [
            'driver' => 'eloquent',
            'model' => App\Models\reservationModel::class,
        ],

        'reasonDeclineModel' => [
            'driver' => 'eloquent',
            'model' => App\Models\reasonDeclineModel::class,
        ],

        'reasonBackOutModel' => [
            'driver' => 'eloquent',
            'model' => App\Models\reasonBackOutModel::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
