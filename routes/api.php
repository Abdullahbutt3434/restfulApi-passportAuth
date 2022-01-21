<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::resource('users',\App\Http\Controllers\UserController::class);
Route::post('users/login',[\App\Http\Controllers\UserController::class,'login']);
Route::post('users/register',[\App\Http\Controllers\UserController::class,'register']);


Route::resource('categories'   , \App\Http\Controllers\CategoryController::class,['only'=>['index','show','store','update','destroy']]);
Route::resource('categories.products',\App\Http\Controllers\CategoryBuyerController::class,['only'=>['index']]);
Route::resource('categories.buyers',\App\Http\Controllers\CategoryBuyerController::class,['only'=>['index']]);
Route::resource('categories.sellers',\App\Http\Controllers\CategorySellerController::class,['only'=>['index']]);
Route::resource('categories.transactions',\App\Http\Controllers\CategoryTransactionController::class,['only'=>['index']]);


Route::resource('products',\App\Http\Controllers\ProductController::class, ['only' => ['index', 'show']]);
Route::resource('products.transactions',\App\Http\Controllers\ProductTransactionController::class,['only'=>['index']]);
Route::resource('products.buyers',\App\Http\Controllers\ProductBuyerController::class,['only'=>['index']]);
Route::resource('products.categories',\App\Http\Controllers\ProductCategoryController::class,['only'=> ['index', 'update', 'destroy']]);
Route::resource('products.buyers.transactions',\App\Http\Controllers\ProductBuyerTransactionController::class,['only'=>['store']]);

Route::resource('buyers',\App\Http\Controllers\BuyerController::class,['only'=>['index','show']]);
Route::resource('buyers.transactions',\App\Http\Controllers\BuyerTransactionController::class,['only'=>['index']]);
Route::resource('buyers.products',\App\Http\Controllers\BuyerProductController::class,['only'=>['index']]);
Route::resource('buyers.sellers',\App\Http\Controllers\BuyerSellerController::class,['only'=>['index']]);
Route::resource('buyers.categories',\App\Http\Controllers\BuyerCategoryController::class,['only'=>['index']]);

Route::resource('sellers',\App\Http\Controllers\SellerController::class,['only'=>['index','show']]);
Route::resource('sellers.categories',\App\Http\Controllers\SellerCategoryController::class,['only'=>['index']]);
Route::resource('sellers.transactions',\App\Http\Controllers\SellerTransactionController::class,['only'=>['index']]);
Route::resource('sellers.products',\App\Http\Controllers\SellerProductController::class,['only'=>['index','store','update','destroy']]);
Route::resource('sellers.buyers',\App\Http\Controllers\SellerBuyerController::class,['only'=>['index']]);


Route::resource('transactions',\App\Http\Controllers\TransactionController::class);
Route::resource('transactions.categories',\App\Http\Controllers\TransactionsCategoryController::class,['only' => ['index']]);
Route::resource('transactions.sellers', \App\Http\Controllers\TransactionSellerController::class, ['only' => ['index']]);



// Authentication Routes...
//Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//Route::post('login', 'Auth\LoginController@login');
//Route::post('logout', 'Auth\LoginController@logout')->name('logout');
//
//// Registration Routes...
//Route::post('/login', [ \App\Http\Controllers\Auth\LoginController::class,'login']);

//\Illuminate\Support\Facades\Auth::routes();


//// Password Reset Routes...
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');
