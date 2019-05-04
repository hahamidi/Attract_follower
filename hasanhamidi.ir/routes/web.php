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

Route::get('/', 'pageControll@getAll');
Route::get('/Add', function () {
    return view('add');
});
Route::post('/Add', 'Add@add');
Route::post('/search/target', 'pageControll@searchTarget');
Route::post('/refresh/target', 'pageControll@refresh');
Route::post('/delete/target', 'pageControll@deleteTarget');
Route::post('/add/target', 'pageControll@addTarget');
Route::post('/change/condition', 'pageControll@changeCondition');
Route::get('/report/{id}','pageControll@report');
Route::get('/test','test@test1');    
Route::post('/delete/page', 'pageControll@deletePage');
Route::post('/setUnfollow/page', 'pageControll@setUnfollow');
Route::get('/testv',function()
{
    return view('test');
});
Route::get('update/page','pageControll@updatePage');
    




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
