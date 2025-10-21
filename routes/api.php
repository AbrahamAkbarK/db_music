<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\SongController;
use App\Http\Controllers\API\AlbumController;
use App\Http\Controllers\API\ArtistController;
use App\Http\Controllers\Api\ComposerController;
use App\Http\Controllers\API\PlaylistController;
use App\Http\Controllers\Api\ArtistMemberController;
use App\Http\Controllers\Api\SongContractController;
use App\Http\Controllers\Api\ArtistContractController;
use App\Http\Controllers\Api\ContractController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('composers', ComposerController::class);
Route::get('/composers', [ComposerController::class, 'index']);
Route::get('/composers/{id}', [ComposerController::class, 'show']);
Route::get('composers/{composer}/songs', [ComposerController::class,'getSongs']);
Route::post('/composers', [ComposerController::class, 'store']);


// Artists API routes
Route::apiResource('artists', ArtistController::class);
Route::get('/artists/upcoming-birthdays/{days?}', [ArtistController::class, 'getUpcomingBirthdays']);
Route::get('/artists',[ArtistController::class,'index']);
Route::get('/artists/{id}',[ArtistController::class,'show']);
Route::post('/artists', [ArtistController::class, 'store']);
Route::get('artists/{artist}/albums', [ArtistController::class, 'albums']);
Route::get('artists/{artist}/songs', [ArtistController::class, 'songs']);
Route::get('artists/{artist}/stats', [ArtistController::class, 'stats']);
Route::get('/artist-categories-count', [ArtistController::class, 'getArtistsByCategoryCount']);

Route::post('/artists/{artist}/members', [ArtistMemberController::class, 'store']);

// Albums API routes
Route::apiResource('albums', AlbumController::class);
Route::get('/albums', [AlbumController::class, 'index']);
Route::get('albums/{album}/songs', [AlbumController::class, 'songs']);
Route::get('albums/{album}/artists', [AlbumController::class, 'artists']);
Route::post('albums/{album}/artists', [AlbumController::class, 'attachArtist']);
Route::delete('albums/{album}/artists/{artist}', [AlbumController::class, 'detachArtist']);

// Songs API routes
Route::apiResource('songs', SongController::class);
Route::get('/search-songs', [SongController::class, 'search']);
Route::get('/songs', [SongController::class, 'index']);
Route::get('/songs/{song}', [SongController::class, 'show']);
Route::get('/total-songs', [SongController::class, 'getTotalSongs']);
Route::get('songs/{song}/artists', [SongController::class, 'artists']);
Route::post('songs/{song}/artists', [SongController::class, 'attachArtist']);
Route::get('songs/{song}/streaming-stats', [SongController::class, 'streamingStats']);
Route::get('/songexp', [SongController::class, 'expiredContracts']);
Route::get('/contracts/{contract}/songs', [SongController::class, 'getSongsByContract']);

Route::get('/contracts',[ContractController::class, 'index']);
Route::get('/contracts/{contract}', [ContractController::class, 'show']);
Route::post('/songs/{song}/contracts', [ContractController::class, 'store']);

// Routes to list and add contracts for a specific artist
Route::get('/artists/{artist}/contracts', [ArtistContractController::class, 'index']);
Route::post('/artists/{artist}/contracts', [ArtistContractController::class, 'store']);

// Routes to list and add contracts for a specific song
Route::get('/songs/{song}/contracts', [SongContractController::class, 'index']);
Route::post('/songs/{song}/contracts', [SongContractController::class, 'store']);


// Playlists API routes
Route::apiResource('playlists', PlaylistController::class);
Route::get('playlists/{playlist}/songs', [PlaylistController::class, 'songs']);
Route::post('playlists/{playlist}/songs', [PlaylistController::class, 'addSong']);
Route::delete('playlists/{playlist}/songs/{song}', [PlaylistController::class, 'removeSong']);

// Statistics and Analytics routes
Route::get('analytics/top-artists', function() {
    return \App\Models\Artist::withCount(['albums', 'songs'])
        ->orderBy('albums_count', 'desc')
        ->limit(10)
        ->get();
});

Route::get('analytics/top-songs', function() {
    return \App\Models\Song::with(['artists', 'album'])
        ->withSum('streamingStats', 'play_count')
        ->orderBy('streaming_stats_sum_play_count', 'desc')
        ->limit(10)
        ->get();
});

Route::get('analytics/revenue', function() {
    return \App\Models\Sale::selectRaw('
            DATE_FORMAT(sale_date, "%Y-%m") as month,
            SUM(total_amount) as total_revenue,
            SUM(artist_royalty) as total_royalties,
            SUM(label_commission) as total_commission
        ')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->limit(12)
        ->get();
});

// Search routes
Route::get('search', function(Request $request) {
    $query = $request->get('q');
    $type = $request->get('type', 'all'); // all, artists, albums, songs

    $results = [];

    if ($type === 'all' || $type === 'artists') {
        $results['artists'] = \App\Models\Artist::where('name', 'LIKE', "%{$query}%")
            ->orWhere('stage_name', 'LIKE', "%{$query}%")
            ->limit(5)->get();
    }

    if ($type === 'all' || $type === 'albums') {
        $results['albums'] = \App\Models\Album::where('title', 'LIKE', "%{$query}%")
            ->with('artists')
            ->limit(5)->get();
    }

    if ($type === 'all' || $type === 'songs') {
        $results['songs'] = \App\Models\Song::where('title', 'LIKE', "%{$query}%")
            ->with(['artists', 'album'])
            ->limit(5)->get();
    }

    return $results;
});
