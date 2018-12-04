<?php

$certificates = [];

$max = 50;
for ($i = 0; $i < $max; $i++) {

    $data = [
        'unipi' => 'test',
        'kwh' => 1,
        'price' => rand(1, 5)/100
    ];

    $certificates []= $data;
}

file_put_contents('certificates.json', json_encode(['certificates' => $certificates]));
