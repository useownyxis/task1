<?php
declare(strict_types=1);

require 'MyComposer.php';

$composer = new MyComposer();

$Package = [
    [
        'name' => 'A',
        'dependencies' => []
    ],
    [
        'name' => 'B',
        'dependencies' => ['H']
    ],
    [
        'name' => 'C',
        'dependencies' => ['D', 'A', 'F']
    ],
    [
        'name' => 'D',
        'dependencies' => []
    ],
    [
        'name' => 'F',
        'dependencies' => ['A', 'B', 'D']
    ],
    [
        'name' => 'H',
        'dependencies' => []
    ],
];

$composer->install($Package);
