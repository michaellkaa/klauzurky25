<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UniversityController;
Use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteController;
use App\Models\Favorite;
use App\Models\Event;
use App\Models\Faculty;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/universities', [UniversityController::class, 'index']);
Route::post('/universities', [UniversityController::class, 'store']);
Route::get('/universities/{id}', [UniversityController::class, 'show']);

Route::get('/faculties', [FacultyController::class, 'index']);
Route::post('/faculties', [FacultyController::class, 'store']);
Route::get('/faculties/{id}', [FacultyController::class, 'show']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/user/photo', [AuthController::class, 'updatePhoto']);

/*Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    Auth::logout();
    return response()->json(['message' => 'Logged out']);
});*/

Route::get('/search', [SearchController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites', [FavoriteController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/favorites/check', [FavoriteController::class, 'check']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/is-favorited', [FavoriteController::class, 'isFavorited']);
    Route::post('/favorite', [FavoriteController::class, 'favorite']);
    Route::post('/unfavorite', [FavoriteController::class, 'unfavorite']);
});

Route::middleware('auth:sanctum')->get('/user/favorites/faculties', [FavoriteController::class, 'userFavoriteFaculties']);

Route::middleware('auth:sanctum')->get('/events', function (Request $request) {
    return Event::where('user_id', $request->user()->id)->orderBy('date')->get();
});

Route::post('/sync-events', [EventSyncController::class, 'store']);
Route::get('/user-events', [EventSyncController::class, 'index']); // Pro Vue kalendář

Route::middleware('auth:sanctum')->get('/favorite-events', function (Request $request) {
    $user = $request->user();

    // Get the names of favorite faculties directly
    $facultyNames = $user->favoriteFaculties()->pluck('name')->unique();

    // Find events matching these faculty names
    $events = Event::whereIn('faculty', $facultyNames)
    ->orderBy('date')
    ->get()
    ->map(function ($event) {
        return [
            'title' => ($event->type ?? 'Událost') . ' - ' . $event->faculty,
            'university'=> $event->university,
            'faculty' => $event->faculty,
            'date' => \Carbon\Carbon::parse($event->date)->format('d-m-Y'),
        ];
    });


    return $events;
});

