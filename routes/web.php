<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('home');
//});



Route::post('/upload-files', 'FileController@postProtocolForm')->middleware('auth');;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');;
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');;

Route::get('/standings', 'HomeController@standings')->name('standings');
Route::get('/top10scorers', 'HomeController@top10scorers')->name('top10scorers');
Route::get('/top10roughest', 'HomeController@top10roughest')->name('top10roughest');
Route::get('/top10referees', 'HomeController@top10referees')->name('top10referees');
Route::get('/top10popularteams', 'HomeController@top10popularteams')->name('top10popularteams');


