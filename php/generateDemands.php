<?php

$producerJsonObj = json_decode(file_get_contents('../src/data/consumers.json'));

$consumers = [];
foreach($producerJsonObj->consumers as $c) {
    array_push($consumers, $c->id);
}

$max = 50;
for ($i = 0; $i < $max; $i++) {

    $pIndex = array_rand($consumers);

    $data = [
        'unipi' => $consumers[$pIndex],
        'kwh' => rand(1, 30),
        'price' => rand(1, 5)/100
    ];

    sendPostRequest('http://localhost:8000/demand/create', $data);

    usleep('100');

}

function sendPostRequest($url, $data)
{

//url-ify the data for the POST
    $fields_string = http_build_query($data);

//open connection
    $ch = curl_init();

//set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($data));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute post
    $result = curl_exec($ch);
    echo $result;
}
