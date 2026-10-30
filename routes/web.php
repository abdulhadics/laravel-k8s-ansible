<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    try { DB::select('SELECT 1 AS ok'); $connected = true; }
    catch (\Throwable $e) { report($e); $connected = false; }
    return response()->view('lab', compact('connected'), $connected ? 200 : 503);
});
Route::get('/health/db', function () {
    try {
        DB::select('SELECT 1 AS ok');
        return response()->json(['application' => 'Laravel', 'database' => 'connected']);
    } catch (\Throwable $e) {
        report($e);
        return response()->json(['application' => 'Laravel', 'database' => 'unavailable'], 503);
    }
});
