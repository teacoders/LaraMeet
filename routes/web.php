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

Route::get('/', function () {
    // broadcast(new TestEvent('this is test'));
    return view('welcome');
});
Route::middleware('auth')->group(function(){
    Route::post('get-users','ChatController@getUser');
    Route::post('get-user-messages','ChatController@getUserMessage');
    Route::post('send-message','ChatController@sendMessage');
    Route::post('call-init','ChatController@callInit');
    Route::post('call-response','ChatController@callResponse');
    Route::post('end-call','ChatController@endCall');
    Route::post('auth/video_chat', 'ChatController@auth');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
