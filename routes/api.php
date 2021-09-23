<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = $api = app("Dingo\Api\Routing\Router");
$api->version('v1', function ($api) {
    $api->group(['namespace' => "App\Http\Controllers\Api\Zds"], function ($api) {
        $api->get('/good', 'GoodController@index');
        $api->post('/order/create', 'GoodController@createOrder');
        $api->get('/order', 'GoodController@getOrder');
        $api->get('/basic', 'GoodController@basic');
        $api->get('/giveaway', 'GoodController@giveaway');

    });
});
