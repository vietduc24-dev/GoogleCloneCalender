<?php

use Illuminate\Support\Facades\Route;

// Mount Vue app ở route gốc
Route::get('/', function () {
    return view('app'); // hoặc welcome
});
// Tất cả các route khác (SPA) đều về Vue xử lý
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$'); // Negative lookahead to exclude /api paths