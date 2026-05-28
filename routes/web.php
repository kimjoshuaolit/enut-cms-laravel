<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostItemController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PufController;
use Symfony\Component\HttpKernel\Profiler\Profile;

// ============================================
// GUEST ROUTES (Public - No Login Required)
// ============================================
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Register routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// ============================================
// AUTHENTICATED ROUTES (Require Login)
// ============================================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Dashboard (Home)
    Route::get('/', function () {
        // Existing metrics
        $totalRows = \Illuminate\Support\Facades\DB::table('puf_csv_logs')->count();
        $totalVisits = \Illuminate\Support\Facades\DB::table('puf_csv_logs')
            ->whereNotNull('csf_data')
            ->count();
        $currentDate = now()->format('M d, Y');

        // Feedback data from csf_data JSON
        $feedbackLogs = \Illuminate\Support\Facades\DB::table('puf_csv_logs')
            ->whereNotNull('csf_data')
            ->whereNotNull('downloaded_at')
            ->orderBy('downloaded_at', 'desc')
            ->get()
            ->map(function ($log) {
                $log->csf_data = json_decode($log->csf_data, true);
                return $log;
            });

        // Ratings averages
        $ratingKeys = ['responsiveness', 'reliability', 'access', 'communication', 'costs', 'integrity', 'assurance', 'outcome', 'overall'];
        $ratingsData = [];
        foreach ($ratingKeys as $key) {
            $values = $feedbackLogs
                ->filter(fn($l) => isset($l->csf_data['ratings'][$key]) && $l->csf_data['ratings'][$key] !== 'NA')
                ->map(fn($l) => (float) $l->csf_data['ratings'][$key]);
            $ratingsData[$key] = $values->count() > 0 ? round($values->avg(), 2) : 0;
        }

        // Affiliation counts
        $affiliationData = $feedbackLogs
            ->filter(fn($l) => !empty($l->csf_data['affiliation']))
            ->groupBy(fn($l) => $l->csf_data['affiliation'])
            ->map->count();

        // Purpose counts
        $purposeData = $feedbackLogs
            ->flatMap(fn($l) => $l->csf_data['purpose'] ?? [])
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(8);

        // NPS
        $npsData = ['promoters' => 0, 'passives' => 0, 'detractors' => 0];
        foreach ($feedbackLogs as $log) {
            $nps = $log->csf_data['nps'] ?? null;
            if ($nps === null) continue;
            $nps = (int) $nps;
            if ($nps >= 9) $npsData['promoters']++;
            elseif ($nps >= 7) $npsData['passives']++;
            else $npsData['detractors']++;
        }
        $npsTotal = array_sum($npsData);
        $npsScore = $npsTotal > 0
            ? round((($npsData['promoters'] - $npsData['detractors']) / $npsTotal) * 100)
            : 0;

        // Responses over time (last 30 days)
        $responsesOverTime = \Illuminate\Support\Facades\DB::table('puf_csv_logs')
            ->whereNotNull('csf_data')
            ->whereNotNull('downloaded_at')
            ->where('downloaded_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(downloaded_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('pages.dashboard.enut-cms', [
            'title'             => 'eNutrition CMS Dashboard',
            'totalRows'         => $totalRows,
            'totalVisits'       => $totalVisits,
            'currentDate'       => $currentDate,
            'ratingsData'       => $ratingsData,
            'affiliationData'   => $affiliationData,
            'purposeData'       => $purposeData,
            'npsData'           => $npsData,
            'npsScore'          => $npsScore,
            'responsesOverTime' => $responsesOverTime,
            'feedbackCount'     => $feedbackLogs->count(),
        ]);
    })->name('dashboard');

    // Search Routes
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/quick', [SearchController::class, 'quickSearch'])->name('search.quick');
    Route::get('/view/{type}/{id}', [SearchController::class, 'viewItem'])->name('search.view-item');

    // Profile
    Route::get('/profile', function () {
        return view('pages.profile', ['title' => 'Profile']);
    })->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // ============================================
    // RESOURCE PAGES
    // ============================================
    Route::get('/factsandfigure', function () {
        return view('pages.factsandfigures.facts-figures', ['title' => 'Facts and Figures']);
    })->name('factsandfigure');

    Route::get('/monograph', function () {
        return view('pages.monograph.monograph', ['title' => 'Monograph']);
    })->name('monograph');

    Route::get('/presentation', function () {
        return view('pages.presentation.presentation', ['title' => 'Presentation']);
    })->name('presentation');

    Route::get('/infographics', function () {
        return view('pages.infographics.infographics', ['title' => 'Infographics']);
    })->name('infographics');

    Route::get('/puf', function () {
        return view('pages.puf.puf', ['title' => 'PUF']);
    })->name('puf');

    // ============================================
    // POST ITEM ROUTES (CRUD)
    // ============================================
    Route::post('/post-items', [PostItemController::class, 'store'])->name('post-items.store');
    Route::get('/post-items/{id}/edit', [PostItemController::class, 'edit'])->name('post-items.edit');
    Route::put('/post-items/{id}', [PostItemController::class, 'update'])->name('post-items.update');
    Route::delete('/post-items/{id}', [PostItemController::class, 'destroy'])->name('post-items.destroy');

    // Announcement ROUTES (CRUD)
    Route::get('/announcement', function () {
        $announcements = \App\Models\Announcement::orderBy('ann_date', 'desc')->get();
        return view('pages.announcement.announcement', [
            'title' => 'Announcement',
            'announcements' => $announcements
        ]);
    })->name('announcement');

    Route::post('/announcement', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::get('/announcement/{id}/edit', [AnnouncementController::class, 'edit'])->name('announcement.edit');
    Route::put('/announcement/{id}', [AnnouncementController::class, 'update'])->name('announcement.update');
    Route::delete('/announcement/{id}', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');

    // ============================================
    // GALLERY ROUTES (CRUD)
    // ============================================
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::get('/gallery/{id}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');
    Route::put('/gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::post('/gallery/update-order', [GalleryController::class, 'updateOrder'])->name('gallery.update-order');
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

    // ============================================
    // PUF UPLOAD (CRUD)
    // ============================================
    Route::get('/puf', function () {
        $postItems = \App\Models\PostItem::where('post_cat', 'PUF')
            ->select(
                'id',
                'post_title',
                'post_description',
                'post_type',
                'post_survey',
                'post_year',
                'date_pub',
                'pic_file',
                'pdf_path'
            )
            ->orderBy('post_year', 'desc')
            ->paginate(15);

        $surveys = \App\Models\PostItemsCat::where('cat_name', 'PUF')
            ->orderBy('value', 'desc')
            ->get();

        // Get puf_csv entries keyed by post_item_id

        $pufFiles = \Illuminate\Support\Facades\DB::table('puf_csv')
            ->whereIn('post_item_id', $postItems->pluck('id'))
            ->get()
            ->keyBy('post_item_id');

        return view('pages.puf.puf', [
            'title'     => 'PUF',
            'postItems' => $postItems,
            'surveys'   => $surveys,
            'pufFiles'  => $pufFiles,
        ]);
    })->name('puf')->middleware('auth');

    Route::post('/puf', [PufController::class, 'store'])->name('puf.store')->middleware('auth');
    Route::get('/puf/{id}/edit', [PufController::class, 'edit'])->name('puf.edit');
    Route::put('/puf/{id}', [PufController::class, 'update'])->name('puf.update')->middleware('auth');
    Route::delete('/puf/{id}', [PufController::class, 'destroy'])->name('puf.destroy')->middleware('auth');

    // ============================================
    // OTHER PAGES
    // ============================================
    Route::get('/blank', function () {
        return view('pages.blank', ['title' => 'Blank']);
    })->name('blank');

    // Error pages
    Route::get('/error-404', function () {
        return view('pages.errors.error-404', ['title' => 'Error 404']);
    })->name('error-404');

    // Chart pages
    Route::get('/line-chart', function () {
        return view('pages.chart.line-chart', ['title' => 'Line Chart']);
    })->name('line-chart');

    Route::get('/bar-chart', function () {
        return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
    })->name('bar-chart');

    // UI Elements pages
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
});
