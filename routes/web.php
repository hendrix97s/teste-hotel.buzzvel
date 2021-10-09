<?php

use Illuminate\Support\Facades\Auth;
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

/**
 * Rota da pagina nicial
 * GET
 */
Route::get('/', 'HomeController@welcome')->name('home');

/**
 * INICIA AUTH
 */
Auth::routes();
/**
 * Pagina inicial do dashboard
 * GET
 */
Route::get('/home', 'HomeController@index')->middleware('auth')->name('home');

/**
 * Rota de consulta de hotéis proximos
 * POST
 */
Route::post('/search', 'HotelController@searchNearbyHotels')->middleware('auth')->name('serach');