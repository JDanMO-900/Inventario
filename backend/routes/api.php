<?php

use App\Http\Controllers\HistoryUserDetailController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\DependencyController;
use App\Http\Controllers\TypeActionController;
use App\Http\Controllers\ProcessStateController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\HistoryChangeController;
use App\Http\Controllers\EquipmentStateController;
use App\Http\Controllers\EquipmentDetailController;
use App\Http\Controllers\TechnicalDescriptionController;
use App\Http\Controllers\EquipmentLicenseDetailController;

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

Route::group(
    ['middleware' => ['api', 'throttle:api'], 'prefix' => 'auth'],
    function ($router) {
        Route::post('login', [AuthController::class, 'login']);
    }
);

Route::group(
    ['middleware' => ['api', 'auth:api'], 'prefix' => 'auth'],
    function ($router) {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('userInfo', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    }
);

Route::get('/status', fn () => response()->json(["message" => "Active"]));

Route::resource('/equipmentState', EquipmentStateController::class);
Route::resource('/brand', BrandController::class);
Route::resource('/dependency', DependencyController::class);

Route::resource('/equipmentType', EquipmentTypeController::class);
Route::resource('/provider', ProviderController::class);

// Get equipment historical based in numer_internal_active
Route::get('/equipment/{equip}', [EquipmentController::class, 'equipmentSearch']);
Route::get('/equipment-available', [EquipmentController::class, 'availableEquipment']);
Route::put('/available',[EquipmentController::class, 'updateAvailability']);


Route::resource('/equipment', EquipmentController::class);

Route::get('/equipment-user/{username}', [EquipmentController::class, 'equipmentInUseByUser']);



Route::resource('/technicalDescription', TechnicalDescriptionController::class);
Route::resource('/equipmentDetail', EquipmentDetailController::class);  

Route::resource('/license', LicenseController::class);
Route::resource('/equipmentLicenseDetail', EquipmentLicenseDetailController::class);

Route::resource('/typeAction', TypeActionController::class);
Route::resource('/processState', ProcessStateController::class);

Route::resource('/historyChange', HistoryChangeController::class);

Route::put('/cancelMovement', [HistoryChangeController::class, 'updateUserCancelMovement']);

Route::put('/changeStatus', [HistoryChangeController::class, 'updateEndProcess']);

// To get role of the user
Route::get('/user/{name}', [UserController::class, 'filter']);
Route::resource('/userData', UserController::class);
Route::get('/role', [RoleController::class, 'index']);



Route::resource('/location', LocationController::class);


Route::resource('/historyUserDetail', HistoryUserDetailController::class);
Route::get('/historyUser/{username}', [HistoryUserDetailController::class, 'userFilter']);


