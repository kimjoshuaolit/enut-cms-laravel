<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostItemController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SearchController;


// dashboard pages
Route::get('/', function () {
    return view('pages.dashboard.enut-cms', ['title' => 'eNutrition CMS Dashboard']);
})->name('dashboard');

// search route
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/quick', [SearchController::class, 'quickSearch'])->name('search.quick');
Route::get('/view/{type}/{id}', [SearchController::class, 'viewItem'])->name('search.view-item');
// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// resources pages
Route::get('/factsandfigure', function () {
    return view('pages.factsandfigures.facts-figures', ['title' => 'Facts and Figures']);
})->name('facts-figures');
Route::get('/monograph', function () {
    return view('pages.monograph.monograph', ['title' => 'Monograph']);
})->name('monograph');
Route::get('/presentation', function () {
    return view('pages.presentation.presentation', ['title' => 'Presentation']);
})->name('presentation');
Route::get('/infographics', function () {
    return view('pages.infographics.infographics', ['title' => 'Infographics']);
})->name('puf');
Route::get('/puf', function () {
    return view('pages.puf.puf', ['title' => 'Infographics']);
})->name('puf');

//post item routes
Route::post('/post-items', [PostItemController::class, 'store'])->name('post-items.store');
Route::get('/post-items/{id}/edit', [PostItemController::class, 'edit'])->name('post-items.edit');
Route::put('/post-items/{id}', [PostItemController::class, 'update'])->name('post-items.update');
Route::delete('/post-items/{id}', [PostItemController::class, 'destroy'])->name('post-items.destroy');

//Gallery routes
Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
Route::get('/gallery/{id}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');
Route::put('/gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');
Route::post('/gallery/update-order', [GalleryController::class, 'updateOrder'])->name('gallery.update-order');
Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

// tables pages
// Route::get('/basic-tables', function () {
//     return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
// })->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');
