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

Route::get('/', function () {
    return view('welcome');
});


Route::get('users', [
    'uses' => 'UserController@index',
    'as' => 'user-list'
]);

Route::resource('users','UserController');
Route::get('users/{id}/edit/','UserController@edit');
Route::delete('users/{id}','UserController@destroy')->name('destroy');

Route::get('students', [
    'uses' => 'StudentController@index',
    'as' => 'student-list'
]);

Route::resource('dtable-posts', 'dtable\AjaxCrudController');
Route::get('dtable-posts/destroy/{id}', 'dtable\AjaxCrudController@destroy');