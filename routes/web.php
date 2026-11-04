<?php

use App\Modules\Admin\Livewire\Dashboard;
use App\Modules\Admin\Livewire\Login;
use App\Modules\Association\Livewire\Profile;
use App\Modules\Geography\Livewire\DistrictIndex;
use App\Modules\Membership\Livewire\MembershipCategoryIndex;
use App\Modules\PublicSite\Http\Controllers\DistrictDirectoryController;
use App\Modules\PublicSite\Http\Controllers\EventController;
use App\Modules\PublicSite\Http\Controllers\HomeController;
use App\Modules\PublicSite\Http\Controllers\PageController;
use App\Modules\PublicSite\Http\Controllers\SportDirectoryController;
use App\Modules\Programme\Livewire\DistrictAssociationIndex;
use App\Modules\Programme\Livewire\EventEntryIndex;
use App\Modules\Programme\Livewire\EventForm;
use App\Modules\Programme\Livewire\EventIndex;
use App\Modules\Programme\Livewire\EventMediaIndex;
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

        Route::get('/district-bodies', DistrictAssociationIndex::class)->name('district-bodies.index');

        Route::get('/events', EventIndex::class)->name('events.index');
        Route::get('/events/create', EventForm::class)->name('events.create');
        Route::get('/events/{event}/edit', EventForm::class)->name('events.edit');
        Route::get('/events/{event}/entries', EventEntryIndex::class)->name('events.entries');
        Route::get('/events/{event}/media', EventMediaIndex::class)->name('events.media');
    });

Route::middleware('ensure.association')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/governance', [PageController::class, 'governance'])->name('governance');
    Route::get('/membership', [PageController::class, 'membership'])->name('membership');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');
    Route::get('/results', [PageController::class, 'results'])->name('results.index');
    Route::get('/news', [PageController::class, 'news'])->name('news.index');
    Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery.index');
    Route::get('/downloads', [PageController::class, 'downloads'])->name('downloads.index');
    Route::get('/register', [PageController::class, 'register'])->name('register');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');

    Route::get('/districts', [DistrictDirectoryController::class, 'index'])->name('districts.index');
    Route::get('/districts/{district}', [DistrictDirectoryController::class, 'show'])->name('districts.show');

    Route::get('/sports', [SportDirectoryController::class, 'index'])->name('sports.index');
    Route::get('/sports/{sport}', [SportDirectoryController::class, 'show'])->name('sports.show');
});
