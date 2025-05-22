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
Route::middleware('auth:sanctum')->get('/is-favorited', function (Illuminate\Http\Request $request) {
    $user = $request->user();

    $isFavorited = Favorite::where('user_id', $user->id)
        ->where('favoritable_id', $request->id)
        ->where('favoritable_type', 'App\\Models\\' . ucfirst($request->type))
        ->exists();

    return response()->json(['favorited' => $isFavorited]);
});

Route::middleware('auth:sanctum')->post('/favorite', function (Illuminate\Http\Request $request) {
    $request->validate([
        'id' => 'required|integer',
        'type' => 'required|string'
    ]);

    $user = $request->user();

    Favorite::firstOrCreate([
        'user_id' => $user->id,
        'favoritable_id' => $request->id,
        'favoritable_type' => 'App\\Models\\' . ucfirst($request->type),
    ]);

    return response()->json(['status' => 'favorited']);
});

Route::middleware('auth:sanctum')->post('/unfavorite', function (Illuminate\Http\Request $request) {
    $request->validate([
        'id' => 'required|integer',
        'type' => 'required|string'
    ]);

    $user = $request->user();

    Favorite::where('user_id', $user->id)
        ->where('favoritable_id', $request->id)
        ->where('favoritable_type', 'App\\Models\\' . ucfirst($request->type))
        ->delete();

    return response()->json(['status' => 'unfavorited']);
});