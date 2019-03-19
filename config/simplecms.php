<?php

return [
    'backend' => [
        'layout' => 'admin.layout'
    ],
    'frontend' => [
        'layout' => 'publico.layout'
    ],
    'formats' => [
        'just-date' => 'd/m/Y',
        'just-time' => 'H:i',
        'datetime' => 'd/m/Y H:i',
        'moment-just-date' => 'DD/MM/YYYY',
        'moment-just-time' => 'HH:mm',
        'moment-datetime' => 'DD/MM/YYYY HH:mm',
        'month-year' => 'F y'
    ],
    'default' => [
        'imagenes' => [
            'portada_home' => 'img/intro-bg.jpg',
            'portada_publicacion' => 'img/intro-bg-shorted.png',
            'preview_publicacion' => 'img/noimagen-difuminadoconico.png'
        ]
    ],
];
