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
    return view('welcome');
    $posts =  \App\Post::orderBy('id','desc')->paginate(3);
    return view('welcome',[
        'posts' => $posts 
    ]);
});

Route::get('/post/master','PostController@master')->name('post.master');
Route::get('/posts/{search?}','PostController@index')->name('posts');
Route::get('/post/create','PostController@create')->name('post.create');
Route::post('/post/create','PostController@store');
Route::get('/post/excel','PostController@excel')->name('post.excel');
