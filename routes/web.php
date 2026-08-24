<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\QCController;
use App\Http\Controllers\SensorMonitoringController;
use App\Http\Controllers\PredictiveMonitoringController;
use App\Models\User;
use App\Http\Controllers\StaffSettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SimulatorController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\AuditController;


/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Registration
|--------------------------------------------------------------------------
*/

Route::post('/check-staff', [RegisteredUserController::class, 'checkStaff'])
    ->name('check.staff');

Route::get('/register-step2', function () {

    $staffId = session('register_staff_id');

    if (!$staffId) {
        return redirect('/register');
    }

    $staff = \App\Models\Staff::find($staffId);

    return view('auth.register-step2', compact('staff'));

})->name('register.step2');  


Route::post('/complete-register',
    [RegisteredUserController::class, 'store']
)->name('complete.register');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/sensor-data',
    [SensorMonitoringController::class, 'index'])
    ->middleware('auth')
    ->name('sensor.data');
    
Route::get('/sensor-data/export/csv',
    [SensorMonitoringController::class, 'exportCsv'])
    ->middleware('auth')
    ->name('sensor.export.csv');

Route::post('/sensor-data/import',
    [SensorMonitoringController::class, 'importDataset'])
    ->middleware('auth')
    ->name('sensor.import.dataset');
    
Route::get('/ai-prediction', function () {
    return view('staff.ai-prediction');
})->middleware('auth')->name('ai.prediction');



/*
|--------------------------------------------------------------------------
| Redirect by Role
|--------------------------------------------------------------------------
*/

Route::get('/redirect', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('staff.dashboard');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {


Route::get('/predictive-monitoring',
    [PredictiveMonitoringController::class, 'index'])
    ->middleware('auth')
    ->name('predictive.monitoring');

// ⚙️ Staff Settings
Route::get('/staff/settings', [StaffSettingsController::class, 'index'])
    ->name('staff.settings');

Route::get(
    '/change-password',
    [StaffSettingsController::class, 'changePassword']
)->name('change.password');

Route::post(
    '/change-password',
    [StaffSettingsController::class, 'updatePassword']
)->name('change.password.update');

Route::post('/staff/settings/update', [StaffSettingsController::class, 'update'])
    ->name('staff.settings.update');

Route::get('/admin/manage-staff',
    [AdminController::class, 'manageStaff']
)->name('admin.manage.staff');

Route::get('/crop-database',
    [CropController::class, 'index']
)->name('admin.crop.database');

Route::get('/add-crop',
    [CropController::class, 'create']
)->name('admin.add.crop');

Route::post('/add-crop',
    [CropController::class, 'store']
)->name('admin.store.crop');

Route::get('/edit-crop/{id}',
    [CropController::class, 'edit']
)->name('admin.edit.crop');

Route::put('/edit-crop/{id}',
    [CropController::class, 'update']
)->name('admin.update.crop');

Route::delete('/delete-crop/{id}',
    [CropController::class, 'destroy']
)->name('admin.delete.crop');

Route::get('/admin/add-staff',
    [AdminController::class, 'addStaff']
)->name('admin.add.staff');

Route::post('/admin/store-staff',
    [AdminController::class, 'storeStaff']
)->name('admin.store.staff');

Route::get('/admin/edit-staff/{id}',
    [AdminController::class, 'editStaff'])
    ->name('admin.edit.staff');

Route::put('/admin/update-staff/{id}',
    [AdminController::class, 'updateStaff'])
    ->name('admin.update.staff');

Route::delete('/admin/delete-staff/{id}',
    [AdminController::class, 'deleteStaff'])
    ->name('admin.delete.staff');

Route::get(
    '/simulator',
    [SimulatorController::class, 'index']
)->name('simulator');

Route::post(
    '/simulator/run',
    [SimulatorController::class, 'run']
)->name('simulator.run');

Route::get(
    '/audit-trail',
    [AuditController::class, 'index']
)->name('admin.audit.trail');

Route::get(
    '/audit-trail/export',
    [AuditController::class, 'exportCsv']
)->name('admin.audit.export');

    // ✅ QC
    Route::post('/qc', [QCController::class, 'evaluate'])->name('qc.evaluate');

    // 🔴 ADMIN
    Route::get('/admin/dashboard', function () {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        return view('admin.dashboard');
    })->name('admin.dashboard');

    // 🟢 STAFF
    Route::get('/staff/dashboard', function () {
    $user = Auth::user();

    if (!$user || $user->role !== 'staff') {
        abort(403);
    }

    return view('dashboard'); // 🔥 FIX
    })->name('staff.dashboard');

    // 🔥 Promote
    Route::post('/make-admin/{id}', function ($id) {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $target = User::findOrFail($id);
        $target->role = 'admin';
        $target->save();

        return back()->with('success', 'User promoted to admin');
    })->name('make.admin');

});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';