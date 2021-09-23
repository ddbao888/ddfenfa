<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/fenfa/login', function () {
    return view('web.oauth.login');
});

Route::post('/login', 'Web\AuthController@postLogin')->name('auth.login');
Route::get('/web/logout', 'Web\AuthController@logout')->name('auth.logout');


Route::group(['namespace' => 'Web', 'prefix' => 'app'], function(){
    Route::get('/{id}', 'AppController@info');
    Route::get('/install/{id}','AppController@install');
});

Route::group(['namespace' => 'Web', 'prefix' => 'web'], function() {

        Route::get('/home', 'HomeController@index')->name('zds.home');
        Route::get('/dashboard', 'HomeController@dashboard')->name('web.dashboard');
        Route::get("/account", "AuthController@account")->name("account.index");
        Route::post("/account", "AuthController@updateAccount")->name("account.update");
        Route::get('/app','AppController@index');
        Route::get('/apps', 'AppController@list')->name('app.list');
        Route::post('/app','AppController@store')->name('app.store');
        Route::post('/app/upload','AppController@upload')->name('app.upload');
        Route::post('/app/{id?}','AppController@update')->name('app.update');
        Route::delete('/app/{id?}', 'AppController@delete')->name('app.delete');
        Route::post('/img/upload','AppController@uploadImg');


});


Route::get('/web/cash_term', 'Web\CashTerminalController@index')->name('web.cash_terminal.index');
