<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\TestCategoryController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerTestController;
use App\Http\Controllers\OrderReportController;

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

Auth::routes();

Route::get('/', function () {
    return view('home');
});

// Lab system resources
Route::resource('branches', BranchController::class);
Route::resource('test-types', TestTypeController::class)->except(['show']);
Route::get('/testtypes', [TestTypeController::class, 'testType'])->name('testtypes');
Route::resource('equipment', EquipmentController::class)->except(['show']);
Route::resource('test-categories', TestCategoryController::class)->except(['show']);
Route::resource('lab-tests', LabTestController::class)->except(['show']);
Route::resource('customers', CustomerController::class)->except(['show']);

// Customer test ordering workflow
Route::get('customers/{customer}/tests', [CustomerTestController::class, 'index'])->name('customers.tests');
Route::post('customers/{customer}/orders', [CustomerTestController::class, 'storeOrder'])->name('customers.orders.store');
Route::delete('customers/{customer}/orders/{order}', [CustomerTestController::class, 'destroyOrder'])->name('customers.orders.destroy');
Route::post('customers/{customer}/orders/{order}/items', [CustomerTestController::class, 'storeItems'])->name('customers.orders.items.store');
Route::delete('customers/{customer}/orders/{order}/types/{typeId}', [CustomerTestController::class, 'destroyTestType'])->name('customers.orders.type.destroy');
Route::post('customers/{customer}/orders/{order}/discount', [CustomerTestController::class, 'updateDiscount'])->name('customers.orders.discount');
Route::post('customers/{customer}/orders/{order}/payments', [CustomerTestController::class, 'storePayment'])->name('customers.orders.payments.store');
Route::post('customers/{customer}/orders/{order}/items/{item}/result', [CustomerTestController::class, 'postResult'])
    ->name('customers.orders.items.result');
Route::post('customers/{customer}/orders/{order}/type-result', [CustomerTestController::class, 'postTypeResult'])
    ->name('customers.orders.type.result');

// Reports
Route::get('/orders/{order}/report', [OrderReportController::class, 'single'])->name('orders.report.single');
Route::get('customers/{customer}/report/all', [OrderReportController::class, 'customerAll'])->name('customers.report.all');
Route::get('/orders/{order}/slip', [OrderReportController::class, 'invoiceSlip'])->name('orders.slip');
Route::get('/orders/{order}/receipt', [OrderReportController::class, 'thermalReceipt'])->name('orders.receipt');

// Customer utilities
Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
Route::get('customers/{customer}/test-history', [CustomerTestController::class, 'testHistory'])->name('customers.test_history');

// Lab sub-tests (nested under lab-tests)
Route::middleware(['auth'])->group(function () {
    Route::get('lab-tests/{labTest}/sub-tests', [\App\Http\Controllers\LabSubTestController::class, 'index'])
        ->name('lab-tests.sub-tests.index');
    Route::get('lab-tests/{labTest}/sub-tests/create', [\App\Http\Controllers\LabSubTestController::class, 'create'])
        ->name('lab-tests.sub-tests.create');
    Route::post('lab-tests/{labTest}/sub-tests', [\App\Http\Controllers\LabSubTestController::class, 'store'])
        ->name('lab-tests.sub-tests.store');
    Route::get('lab-tests/{labTest}/sub-tests/{subTest}/edit', [\App\Http\Controllers\LabSubTestController::class, 'edit'])
        ->name('lab-tests.sub-tests.edit');
    Route::put('lab-tests/{labTest}/sub-tests/{subTest}', [\App\Http\Controllers\LabSubTestController::class, 'update'])
        ->name('lab-tests.sub-tests.update');
    Route::delete('lab-tests/{labTest}/sub-tests/{subTest}', [\App\Http\Controllers\LabSubTestController::class, 'destroy'])
        ->name('lab-tests.sub-tests.destroy');
});

// Price calculator — returns active test types as JSON
Route::get('/calculator/types', function () {
    return response()->json(
        \App\Models\TestType::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'price'])
    );
})->middleware('auth')->name('calculator.types');

// Dashboard
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/index', [UserController::class, 'index'])->name('user.index')->middleware('auth');

// Publication routes
Route::get('/publication', [App\Http\Controllers\PublicationController::class, 'index'])->name('publication');
Route::post('/storepublication', [App\Http\Controllers\PublicationController::class, 'store'])->name('storepublication');
Route::get('/editpublication/{id}', [App\Http\Controllers\PublicationController::class, 'edit'])->name('editpublication');
Route::get('/managepublication', [App\Http\Controllers\PublicationController::class, 'show'])->name('managepublication');
Route::post('/updatepublication', [App\Http\Controllers\PublicationController::class, 'update'])->name('updatepublication');
Route::get('/downloadpublication/{publication}', [App\Http\Controllers\PublicationController::class, 'downloadPublication'])->name('downloadpublication');
Route::get('/adminthread', [App\Http\Controllers\AdminThreadController::class, 'index'])->name('adminthread');
Route::get('/fetchthread/{user_id}', [App\Http\Controllers\AdminThreadController::class, 'fetchThread'])->name('fetchthread');
Route::get('/publicationallocation', [App\Http\Controllers\PublicationController::class, 'publicationAllocation'])->name('publicationallocation');
Route::get('/unlinkpublication/{pivot_id}', [App\Http\Controllers\PublicationController::class, 'unlinkPublication'])->name('unlinkpublication');
Route::get('/verifyallocation/{pubid}/{userid}', [App\Http\Controllers\PublicationController::class, 'verifyAllocation'])->name('verifyallocation');
Route::post('/storeallocation', [App\Http\Controllers\PublicationController::class, 'storeAllocation'])->name('storeallocation');
Route::get('/publicationdetails/{pub_id}', [App\Http\Controllers\PublicationController::class, 'publicationDetails'])->name('publicationdetails');
Route::get('/userthread', [App\Http\Controllers\UserThreadController::class, 'index'])->name('userthread');
Route::get('/sendmessage/{message}/{chat_id}', [App\Http\Controllers\UserThreadController::class, 'store'])->name('sendmessage');
Route::get('/userpublications', [App\Http\Controllers\PublicationController::class, 'index'])->name('userpublications');
Route::post('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');

// Media routes
Route::get('/createmedia', [App\Http\Controllers\MediaController::class, 'index'])->name('createmedia');
Route::post('/storemedia', [App\Http\Controllers\MediaController::class, 'store'])->name('storemedia');
Route::get('/editmedia/{id}', [App\Http\Controllers\MediaController::class, 'edit'])->name('editmedia');
Route::get('/deletemedia/{id}', [App\Http\Controllers\MediaController::class, 'destroy'])->name('deletemedia');

// Media category routes
Route::get('/mediacategory', [App\Http\Controllers\MediaCategoryController::class, 'index'])->name('mediacategory');
Route::post('/storecategory', [App\Http\Controllers\MediaCategoryController::class, 'store'])->name('storecategory');
Route::get('/editcategory/{id}', [App\Http\Controllers\MediaCategoryController::class, 'edit'])->name('editCategory');
Route::post('/update', [App\Http\Controllers\MediaCategoryController::class, 'update'])->name('updatecategory');
Route::get('/deletecategory/{id}', [App\Http\Controllers\MediaCategoryController::class, 'destroy'])->name('deletecategory');

// Lab settings (admin only)
Route::get('/lab-settings', [\App\Http\Controllers\LabSettingController::class, 'edit'])->name('lab-settings.edit');
Route::put('/lab-settings', [\App\Http\Controllers\LabSettingController::class, 'update'])->name('lab-settings.update');
Route::post('/lab-settings/upload-image', [\App\Http\Controllers\LabSettingController::class, 'uploadImage'])->name('lab-settings.upload-image');
Route::post('/lab-settings/delete-image', [\App\Http\Controllers\LabSettingController::class, 'deleteImage'])->name('lab-settings.delete-image');
Route::post('/lab-settings/save-canvas', [\App\Http\Controllers\LabSettingController::class, 'saveCanvas'])->name('lab-settings.save-canvas');
Route::post('/lab-settings/save-watermark-canvas', [\App\Http\Controllers\LabSettingController::class, 'saveWatermarkCanvas'])->name('lab-settings.save-watermark-canvas');

// User management routes
Route::get('/showusers', [UserController::class, 'showUsers'])->name('showusers');
Route::post('/store-user', [UserController::class, 'store'])->name('storeUser');
Route::get('/edituser/{id}', [UserController::class, 'editUser'])->name('editUser');
Route::put('/update-user', [UserController::class, 'update'])->name('updateUser');
