<?php

use craft\helpers\App;

return [
    'checkDevServer' => true,
    'errorEntry' => 'assets/js/main.js',
    'devServerInternal' => App::env('PRIMARY_SITE_URL') . ':5173',
    'devServerPublic' => App::env('PRIMARY_SITE_URL') . ':5174',
    'manifestPath' => '@webroot/dist/.vite/manifest.json',
    'serverPublic' => App::env('PRIMARY_SITE_URL') . '/dist/',
    'useDevServer' => App::parseBooleanEnv('$VITE_USE_DEV_SERVER'),
];