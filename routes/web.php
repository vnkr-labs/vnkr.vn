<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ReadingHistoryController;
use App\Http\Controllers\RssFeedController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\AdSlotController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BreakingNewsController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\JourneyController as AdminJourneyController;
use App\Http\Controllers\Admin\LiveBlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RedirectController as AdminRedirectController;
use App\Http\Controllers\Admin\ReusableController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

use App\Http\Controllers\DetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", [HomeController::class, "index"])->name("index");
Route::get("/result/{id}", [HomeController::class, "result"])->name("result");
Route::get("/chuyen-muc/{slug}", [HomeController::class, "resultBySlug"])->name("category.slug");
Route::get("/detail/{slug}", [HomeController::class, "detail"])->name("detail");
Route::get("/login", [UserController::class, "login"])->name("login");
Route::post("/login", [UserController::class, "postLogin"]);

Route::get("/register", [UserController::class, "register"])->name("register");
Route::post("/register", [UserController::class, "postRegister"]);

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/logon', [AdminController::class, 'logon'])->name('logon');
Route::post('/logon', [AdminController::class, 'postlogon'])->name('admin.logon');
Route::get('/sign-out', [AdminController::class, 'signOut'])->name('admin.signout');

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [DashBoardController::class, 'index'])->name('admin.index');

    Route::resource('category', CategoryController::class);
    Route::get('/category-trash', [CategoryController::class, 'trash'])->name('category.trash');
    Route::get('/category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
    Route::get('/category/{id}/forceDelete', [CategoryController::class, 'forceDelete'])->name('category.forceDelete');

    Route::resource('product', ProductController::class);
    Route::get('/product-trash', [ProductController::class, 'trash'])->name('product.trash');
    Route::get('/product/{id}/restore', [ProductController::class, 'restore'])->name('product.restore');
    Route::get('/product/{id}/forceDelete', [ProductController::class, 'forceDelete'])->name('product.forceDelete');

    Route::resource('user', UserController::class);
    Route::put('/admin/user/change-role/{user}', [UserController::class, 'changeRole'])->name('user.changeRole');

    Route::resource('contact', ContactController::class)->except(['show']);
    Route::resource('roleUs', RoleController::class);

    // Breaking News admin
    Route::get('/breaking-news', [BreakingNewsController::class, 'index'])->name('breaking.index');
    Route::post('/breaking-news', [BreakingNewsController::class, 'store'])->name('breaking.store');
    Route::patch('/breaking-news/{breakingNews}/toggle', [BreakingNewsController::class, 'toggle'])->name('breaking.toggle');
    Route::delete('/breaking-news/{breakingNews}', [BreakingNewsController::class, 'destroy'])->name('breaking.destroy');

    // Ad Slots admin
    Route::get('/ad-slots', [AdSlotController::class, 'index'])->name('ad_slots.index');
    Route::get('/ad-slots/{adSlot}/edit', [AdSlotController::class, 'edit'])->name('ad_slots.edit');
    Route::put('/ad-slots/{adSlot}', [AdSlotController::class, 'update'])->name('ad_slots.update');
    Route::post('/ad-slots/{adSlot}/toggle', [AdSlotController::class, 'toggle'])->name('ad_slots.toggle');

    // Live Blog admin
    Route::get('/live-blog', [LiveBlogController::class, 'index'])->name('live_blog.index');
    Route::get('/live-blog/{article}', [LiveBlogController::class, 'manage'])->name('live_blog.manage');
    Route::post('/live-blog/{article}/updates', [LiveBlogController::class, 'store'])->name('live_blog.store');
    Route::delete('/live-blog/{article}/updates/{update}', [LiveBlogController::class, 'destroy'])->name('live_blog.destroy');
    Route::post('/live-blog/{article}/toggle', [LiveBlogController::class, 'toggle'])->name('live_blog.toggle');

    // Comment moderation
    Route::get('/comments/pending', [CommentController::class, 'index'])->name('admin.comment.index');
    Route::post('/comments/{id}/approve', [CommentController::class, 'approve'])->name('admin.comment.approve');
    Route::post('/comments/approve-all', [CommentController::class, 'approveAll'])->name('admin.comment.approveAll');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('admin.comment.destroy');

    // Journeys admin
    Route::get('/journeys',                           [AdminJourneyController::class, 'index'])->name('admin.journeys.index');
    Route::get('/journeys/create',                    [AdminJourneyController::class, 'create'])->name('admin.journeys.create');
    Route::post('/journeys',                          [AdminJourneyController::class, 'store'])->name('admin.journeys.store');
    Route::get('/journeys/{journey}/edit',            [AdminJourneyController::class, 'edit'])->name('admin.journeys.edit');
    Route::put('/journeys/{journey}',                 [AdminJourneyController::class, 'update'])->name('admin.journeys.update');
    Route::delete('/journeys/{journey}',              [AdminJourneyController::class, 'destroy'])->name('admin.journeys.destroy');

    // Redirects admin
    Route::get('/redirects',                          [AdminRedirectController::class, 'index'])->name('admin.redirects.index');
    Route::get('/redirects/create',                   [AdminRedirectController::class, 'create'])->name('admin.redirects.create');
    Route::post('/redirects',                         [AdminRedirectController::class, 'store'])->name('admin.redirects.store');
    Route::patch('/redirects/{redirect}/toggle',      [AdminRedirectController::class, 'toggle'])->name('admin.redirects.toggle');
    Route::delete('/redirects/{redirect}',            [AdminRedirectController::class, 'destroy'])->name('admin.redirects.destroy');

    // Reusables admin
    Route::get('/reusables',                          [ReusableController::class, 'index'])->name('admin.reusables.index');
    Route::get('/reusables/create',                   [ReusableController::class, 'create'])->name('admin.reusables.create');
    Route::post('/reusables',                         [ReusableController::class, 'store'])->name('admin.reusables.store');
    Route::get('/reusables/{reusable}/edit',          [ReusableController::class, 'edit'])->name('admin.reusables.edit');
    Route::put('/reusables/{reusable}',               [ReusableController::class, 'update'])->name('admin.reusables.update');
    Route::delete('/reusables/{reusable}',            [ReusableController::class, 'destroy'])->name('admin.reusables.destroy');
});

// Live polling — public (no auth)
Route::get('/live/{articleId}/updates', [\App\Http\Controllers\Admin\LiveBlogController::class, 'poll'])->name('live.poll');



Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
    // Contact submit: 5/minute per user — tránh spam phản hồi
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit')->middleware('throttle:5,1');

    // Bookmark
    Route::post('/bookmark/{articleId}', [BookmarkController::class, 'toggle'])->name('bookmark.toggle');
    Route::get('/profile/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

    // Reading history
    Route::get('/profile/history', [ReadingHistoryController::class, 'index'])->name('history.index');

    // Member profile dashboard
    Route::get('/profile',               [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',               [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',      [ProfileController::class, 'updatePassword'])->name('profile.password');
});



// 60 searches/minute per IP
Route::get('/search', [SearchController::class, 'search'])->name('search')->middleware('throttle:60,1');

Route::get('/detail/{slug}', [DetailController::class, 'show'])->name('detail');
// Comments: 10/minute per user
Route::post('/detail/{slug}/comment', [DetailController::class, 'storeComment'])->name('detail.comment')->middleware(['auth', 'throttle:10,1']);
Route::delete('/detail/{slug}/comment/{commentId}', [DetailController::class, 'deleteComment'])->name('detail.comment.delete')->middleware('auth');
Route::post('/detail/{slug}/comment/{commentId}/like', [DetailController::class, 'likeComment'])->name('detail.comment.like')->middleware(['auth', 'throttle:30,1']);
Route::post('/detail/{slug}/comment/{commentId}/reply', [DetailController::class, 'replyComment'])->name('detail.comment.reply')->middleware(['auth', 'throttle:10,1']);

// Author profile
Route::get('/author/{username}', [AuthorController::class, 'show'])->name('author.show');

// Newsletter: 3 attempts/minute per IP — tránh abuse
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('throttle:3,1');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Offline fallback for PWA service worker
Route::get('/offline', function () {
    return response()->file(public_path('offline.html'));
})->name('offline');

// Static pages — English URLs (canonical)
Route::get('/about',      function () { return view('fe.static.about'); })->name('about');
Route::get('/terms',      function () { return view('fe.static.terms'); })->name('terms');
Route::get('/privacy',    function () { return view('fe.static.privacy'); })->name('privacy');
Route::get('/community',  function () { return view('fe.static.rules'); })->name('rules');
Route::get('/contribute', function () { return view('fe.static.contribute'); })->name('contribute');

// Journey public page
Route::get('/journey/{slug}', [JourneyController::class, 'show'])->name('journey.show');

// Legacy Vietnamese URLs → 301 redirect to English
Route::redirect('/gioi-thieu',       '/about',      301);
Route::redirect('/dieu-khoan',       '/terms',      301);
Route::redirect('/chinh-sach-bao-mat', '/privacy',  301);
Route::redirect('/quy-che-hoat-dong',  '/community',301);
Route::redirect('/dong-gop-ma-nguon',  '/contribute',301);

// Search Autocomplete API (github/docs pattern — 30 req/min)
Route::get('/api/search/autocomplete', \App\Http\Controllers\Api\V1\SearchAutocompleteController::class)
    ->name('api.search.autocomplete')
    ->middleware('throttle:30,1');

// SEO — Sitemap & RSS Feed (no session/cookie — required by Google Search Console)
Route::withoutMiddleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \App\Http\Middleware\VerifyCsrfToken::class,
])->group(function () {
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
    Route::get('/feed', [RssFeedController::class, 'index'])->name('rss.feed');
});
