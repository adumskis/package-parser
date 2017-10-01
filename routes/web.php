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

Route::get('/', [
    'as' => 'feed.index', 'uses' => 'FeedController@index'
]);

Route::get('feed/{feed}', [
    'as' => 'feed.show', 'uses' => 'FeedController@show'
]);

Route::get('chart-data', [
    'as' => 'feed.chartData', 'uses' => 'FeedController@chartData'
]);

Route::post('upload-feed', [
    'as' => 'feed.store', 'uses' => 'FeedController@store'
]);
