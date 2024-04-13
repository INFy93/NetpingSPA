<?php

use App\Http\Controllers\Api\CameraController;
use App\Http\Controllers\Api\NetpingApiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/',  function () {
    if(Auth::check()) {
        return redirect('/netping');
    }
    else {
        return Inertia::render('Auth/Login');
    }
})->name('enter');

Route::middleware('auth')->group( function () {
   Route::resource('netping', \App\Http\Controllers\NetpingController::class);
   Route::get('temperature', \App\Http\Controllers\TemperatureGraphsController::class)->name('temperature-graphs');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => 'auth', 'prefix' => 'api'], function () {
    Route::get('/power', [NetpingApiController::class, 'get_power_data']);
    Route::get('/secure', [NetpingApiController::class, 'get_secure_data'])->name('secure');
    Route::get('/door', [NetpingApiController::class, 'get_door_data']);
    Route::get('/alarm', [NetpingApiController::class, 'get_alarm_data']);
    Route::get('/alarm/set/{id}', [NetpingApiController::class, 'switchAlarm']);

    Route::get('/bdcoms_current_temp', [\App\Http\Controllers\Api\BdcomApiController::class, 'getBdcomCurrentTemperature']);
    Route::get('/bdcoms', [\App\Http\Controllers\Api\BdcomApiController::class, 'getBdcoms']);
    Route::get('/netping_camera/{id}', [CameraController::class, 'getCamera']);

    //Route::get('/temp/{id}', [\App\Http\Controllers\Api\BdcomController::class, 'getTemperature']);

});

require __DIR__.'/auth.php';
