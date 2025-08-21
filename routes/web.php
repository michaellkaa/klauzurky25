<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Response;

Route::get('/login', function () {
    return Inertia::render('LoginPage');
})->name('login');

// tady musis mit login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', fn() => Inertia::render('Dashboard'))->name('home');
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/calendar', fn() => Inertia::render('CalendarPage'))->name('calendar');
    Route::get('/calendar', fn() => Inertia::render('QuizPage'))->name('quiz');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('auth:sanctum')->post('/user/photo', [ProfileController::class, 'updatePhoto']);

    Route::get('/{any}', fn() => view('app'))->where('any', '.*');
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logged out']);
})->middleware('auth');

Route::get('/ical/{username}.ics', function ($username) {
    $user = User::where('username', $username)->firstOrFail();

    $events = $user->events;

    $ics = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//SfYNs//Calendar//EN\r\n";

    foreach ($events as $event) {
        $date = \Carbon\Carbon::parse($event->date)->format('Ymd');
        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "SUMMARY:{$event->title}\r\n";
        $ics .= "DTSTART;VALUE=DATE:{$date}\r\n";
        $ics .= "DTEND;VALUE=DATE:{$date}\r\n";
        $ics .= "DESCRIPTION:{$event->university} - {$event->faculty}\r\n";
        $ics .= "END:VEVENT\r\n";
    }

    $ics .= "END:VCALENDAR\r\n";

    return Response::make($ics, 200, [ //tohle doufam ze pujde, podobnou funkci mam i v calendar page
        'Content-Type' => 'text/calendar; charset=utf-8',
        'Content-Disposition' => 'inline; filename="calendar.ics"',
    ]);
});

