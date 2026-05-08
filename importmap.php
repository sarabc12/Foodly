<?php

return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
        'entrypoint' => true,
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'chart.js' => [
        'version' => '4.4.1',
    ],
    '@symfony/ux-chartjs' => [
        'path' => './vendor/symfony/ux-chartjs/assets/dist/chart_controller.js',
    ],
    '@kurkle/color' => [
        'version' => '0.3.4',
    ],
];