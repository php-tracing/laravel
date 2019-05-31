<?php

/**
 * db - trace db queries
 * log - trace log data
 */
return [
    'default' => 'jaeger',
    'jaeger'  => [
        'db'  => true,
        'log' => true,
    ],
    'zipkin'  => [
        'db'  => true,
        'log' => true,
    ],
];
