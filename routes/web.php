<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Regist;
use App\Http\Controllers\File; 

Route::get('/upload', [File::class, 'showUploadForm'])->name('show.form');
Route::post('/upload', [File::class, 'handleUpload'])->name('file.upload');

Route::get('/', function () {
    return view('home');
});
Route::get('/register', [Regist::class, 'index'])->name('regist');
Route::post('/register', [Regist::class, 'handleRegist'])->name('regist.handle');

Route::get('/sitemap.xml', function () {
    $urls = [
        secure_url('/'),
        secure_url('/regist'),
        // Tambahin semua route kamu di sini
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($urls as $url) {
        $xml .= '<url>';
        $xml .= '<loc>' . htmlentities($url) . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
    }

    $xml .= '</urlset>';

    return response($xml, 200)
        ->header('Content-Type', 'application/xml');
});
