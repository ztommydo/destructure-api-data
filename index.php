<?php

require_once('src/DestructureApiData/DestructureApiData.php');
require_once('src/support/RequestWrapper.php');

use Tommydo\DestructureApiData\DestructureApiData;
use Tommydo\RequestWrapper;

/**
 * Define the schema that you want to return after destucturing
 * 
 * @key: API's key
 * @value: return array key
 * 
 * Note: This example is used Starwars API: https://swapi.dev
 */
$expecteds_array_schema = [
    'films'  => 'my_films',
    'people' => 'my_people'
];

/**
 * Prepare API config
 */
$api_config = [
    'url'      => 'https://swapi.dev/api',
    'token'    => '',
    'endpoint' => ''
];

$RequestWrapper = new RequestWrapper($api_config);
$api_res = $RequestWrapper->send();
if(!empty($api_res)) {
    $api_res = json_decode($api_res, true);
}

/**
 * Create new instance of Destructor
 */
$Destructor = new DestructureApiData();
$Destructor->setOption('keep_origin', 1);

// Set expected return schema;
$Destructor->set('schema', $expecteds_array_schema);
// Set API responsed data
$Destructor->set('original_data', $api_res);

/**
 * Run destruct and print the data
 */
$my_api_data = $Destructor->destruct();

echo('This is destructed array data:'.PHP_EOL);
echo(PHP_EOL);
print_r($my_api_data);
exit;

// The end of example


