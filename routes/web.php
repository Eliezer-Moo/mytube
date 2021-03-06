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
/*    $videos = Video::all();
    foreach ($videos as $video){
        echo $video-> title.'<br>';
        echo $video->user->email.'<br>';
        foreach($video->comments as $comentario){
            echo $comentario->body. '<br>';
        }
        echo '<hr>';
    }
    die();*/
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('videos','App\http\Controllers\videoController');

Route::get('/delete-video/{video_id}', array(
    'as'=> 'delete-video',
    'middleware' => 'auth',
    'uses'=> 'App\Http\Controllers\VideoController@delete_video'
));
