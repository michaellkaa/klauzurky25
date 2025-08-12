<?php
/*use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::middleware(['auth', 'verified'])->get('/{any}', function () {
    return view('app');
})->where('any', '.*');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('home');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth:sanctum')->post('/user/photo', [ProfileController::class, 'updatePhoto']);

Route::get('/login', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('login');

Route::get('/register', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('register');

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logged out']);
})->middleware('auth');

Route::middleware(['auth', 'verified'])->get('/calendar', function () {
    return Inertia::render('CalendarPage');
})->name('calendar');

Route::middleware(['auth', 'verified'])->get('/profile', function () {
    return Inertia::render('ProfilePage');
})->name('profile');
*/


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 🔓 Jediná veřejná route — Inertia Login Page
Route::get('/login', function () {
    return Inertia::render('LoginPage'); // <-- tvůj vlastní login component
})->name('login');

// 🔒 Vše ostatní jen pro přihlášené
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', fn() => Inertia::render('Dashboard'))->name('home');
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/calendar', fn() => Inertia::render('CalendarPage'))->name('calendar');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('auth:sanctum')->post('/user/photo', [ProfileController::class, 'updatePhoto']);

    // Catch-all na Vue router — POUZE pro přihlášené
    Route::get('/{any}', fn() => view('app'))->where('any', '.*');
});

// 🔒 Logout (jen pro přihlášené)
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logged out']);
})->middleware('auth');
