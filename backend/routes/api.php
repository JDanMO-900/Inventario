<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\PDFDataController;
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
use App\Http\Controllers\HistoryUserDetailController;
use App\Http\Controllers\TechnicalDescriptionController;
use App\Http\Controllers\EquipmentLicenseDetailController;
use App\Http\Controllers\PDFReportGController;
use App\Models\PDFData;

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
    [
        'middleware' => ['api', 'auth:api'],
        'prefix' => 'auth'
    ],
    function ($router) {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('userInfo', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);

    }
);

Route::group(
    [
        'middleware' => ['api'],
    ],
    function ($router) {
    Route::get('/status', fn() => response()->json(["message" => "Active"]));
    Route::resource('/equipmentState', EquipmentStateController::class);
    Route::resource('/brand', BrandController::class);
    Route::resource('/dependency', DependencyController::class);
    Route::resource('/equipmentType', EquipmentTypeController::class);
    Route::resource('/provider', ProviderController::class);
    Route::get('/equipment/{equip}', [EquipmentController::class, 'equipmentSearch']);
    Route::get('/equipment-available', [EquipmentController::class, 'availableEquipment']);
    Route::put('/available', [EquipmentController::class, 'updateAvailability']);
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
    Route::put('/updateProcessState', [HistoryChangeController::class, 'updateProcessState']);
    Route::put('/finishIncompleteMovement', [HistoryChangeController::class, 'finishIncompleteMovement']);
    Route::get('/user/{name}', [UserController::class, 'filter']);
    Route::resource('/userData', UserController::class);
    Route::get('/role', [RoleController::class, 'index']);
    Route::resource('/location', LocationController::class);
    Route::resource('/historyUserDetail', HistoryUserDetailController::class);
    Route::get('/historyUser/{username}', [HistoryUserDetailController::class, 'userFilter']);
    Route::get('/userEquipment/{username}', [HistoryUserDetailController::class, 'asignedEquipment']);
    Route::post('/reportpdf', [PDFDataController::class, 'locationReport']);
    Route::post('/allProducts', [PDFDataController::class, 'allProducts']);
    Route::post('/availableEquipment', [PDFDataController::class, 'availableEquipment']);
    Route::get('/individual-reportpdf/{serial_number}', [PDFDataController::class, 'individualReport']);
    Route::post('/reportgeneralpdf', [PDFDataController::class, 'reportGeneral']);
    Route::post('/testEquip', [EquipmentController::class, 'getReportGeneral']);
    Route::get('/getFilterMovement', [HistoryChangeController::class, 'filterMovement']);
});
