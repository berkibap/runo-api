<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiscordController as Discord;
use App\Http\Controllers\UserController as User;
use App\Http\Controllers\LatestController as Son;
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

Route::get('/discord-login/{id}', [Discord::class, 'login']);
Route::get('/kullanici/{param}', [User::class, 'info']);
Route::get('/son-rozetler/{limit?}', [Son::class, 'badge']);
Route::get('/son-mobiler/{limit?}', [Son::class, 'furni']);
Route::get('/api-codes', [User::class, "apiCodes"]);