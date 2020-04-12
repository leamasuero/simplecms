<?php

return [
    'layout' => [
        'backend' => 'admin.layout'
    ],
    'routes' => [
        'login' => 'publico.login'
    ],
    'publicaciones' =>
        [
            'notificaciones' => [
                'waiting' => env('SIMPLECMS_PUBLICACIONES_NOTIFICACIONES_WAITING', 30)
            ]
        ]
];
