<?php
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('permissions', PermissionController::class);

    Route::get('pagination/roles', [RoleController::class, 'paginate'])->name('paginate.roles');
    Route::resource('roles', RoleController::class);
    
    // Route::resource('pages', PageController::class);
    // Route::get('pagination/pages', [PageController::class, 'paginate'])->name('paginate.roles');

});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
