<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StoreBranchController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\FloorController;
use App\Http\Controllers\Api\PlaceTypeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MenuTypeController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\StaffStoreController;
use App\Models\OrderItems;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/pykit/auth/resgister', [AuthController::class, 'register'])->name('auth.register');
Route::post('/pykit/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/pykit/auth/checktoken', [AuthController::class, 'loginByToken'])->name('auth.loginByToken');

//get
Route::post('/pykit/app/get-stores', [StoreController::class, 'getStores'])->name('app.getStores')->middleware('auth:sanctum');
Route::post('/pykit/app/get-branchs', [StoreBranchController::class, 'getBranchs'])->name('app.getBranchs')->middleware('auth:sanctum');
Route::post('/pykit/app/get-floors',[FloorController::class, 'getFloors'])->name('app.getFloors')->middleware('auth:sanctum');
Route::post('/pykit/app/get-order-today',[OrderController::class, 'getOrderToday'])->name('app.getOrderToday')->middleware('auth:sanctum');
//create
Route::post('/pykit/app/create-address',[AddressController::class, 'createAddress'])->name('app.createAddress')->middleware('auth:sanctum');
Route::post('/pykit/app/create-place-type',[PlaceTypeController::class, 'createPlaceType'])->name('app.createPlaceType')->middleware('auth:sanctum');
Route::post('/pykit/app/create-menu-type',[MenuTypeController::class, 'createMenuType'])->name('app.createMenuType')->middleware('auth:sanctum');

//! edit
Route::post('/pykit/app/change-status-order-item',[OrderItemController::class, 'changeStatusOrderItem'])->name('app.changeStatusOrderItem');
Route::post('/pykit/app/change-quantity',[OrderItemController::class, 'changeQuantity'])->name('app.changeQuantity')->middleware('auth:sanctum');

//add staff
Route::post('/pykit/app/add-staff',[StaffStoreController::class, 'addStaff'])->name('app.addStaff')->middleware('auth:sanctum');

// add menuitem
Route::post('/pykit/app/add-menu-item',[StoreController::class, 'addMenuItem'])->name('app.addMenuItem')->middleware('auth:sanctum');
Route::post('/pykit/app/get-all-image',[StoreController::class, 'getAllImage'])->name('app.getAllImage')->middleware('auth:sanctum');

// add StoreBranch
Route::post('/pykit/app/add-store-branch',[StoreBranchController::class, 'addStoreBranch'])->name('app.addStoreBranch')->middleware('auth:sanctum');


//get all menuitem
Route::post('/pykit/app/get-all-menu-item',[StoreController::class, 'getAllMenuItem'])->name('app.getAllMenuItem')->middleware('auth:sanctum');

//get all promotion
Route::post('/pykit/app/get-promotion-by-store-branchs-id',[OrderItemController::class, 'getPromotionByStoreBranchsId'])->name('app.getAllPromotion')->middleware('auth:sanctum');

//find user
Route::get('/pykit/app/find-user',[AuthController::class, 'findUser'])->name('app.findUser')->middleware('auth:sanctum');