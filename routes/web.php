<?php

use App\Modules\Admin\Livewire\Dashboard;
use App\Modules\Admin\Livewire\Login;
use App\Modules\Association\Livewire\Profile;
use App\Modules\Geography\Livewire\DistrictIndex;
use App\Modules\Membership\Livewire\MembershipCategoryIndex;
use App\Modules\PublicSite\Http\Controllers\HomeController;
use App\Modules\Sport\Livewire\CategoryIndex;
use App\Modules\Sport\Livewire\DisciplineIndex;
use App\Modules\Sport\Livewire\SportIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

Route::middleware(['auth', 'ensure.active', 'ensure.association'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/', Dashboard::class)->name('dashboard');
        Route::get('/association', Profile::class)->name('association.profile');
        Route::get('/districts', DistrictIndex::class)->name('districts.index');
        Route::get('/sports', SportIndex::class)->name('sports.index');
        Route::get('/disciplines', DisciplineIndex::class)->name('disciplines.index');
        Route::get('/categories', CategoryIndex::class)->name('categories.index');
        Route::get('/membership-categories', MembershipCategoryIndex::class)->name('membership-categories.index');
    });

Route::middleware('ensure.association')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
});
