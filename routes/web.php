<?php

use App\Http\Controllers\AdminPostsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\HomepageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserPostController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminCommentsController;
use App\Http\Controllers\DonationController;
use App\Models\User;
use App\Http\Controllers\StripeWebhookController;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();
Route::middleware('auth.custom')->group(function () {

    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('home', [HomepageController::class, 'showHomepage'])->name('home');
    Route::get('/posts/{id}', [HomepageController::class, 'show'])->name('posts.showDetails');


    Route::prefix('admin')->group(function () {
        Route::resource('/users', AdminUsersController::class)->except(['show']);

        Route::get('/profile', [AdminProfileController::class, 'index'])->name('adminprofile.index');
        Route::put('/profile/{user}', [AdminProfileController::class, 'updateProfile'])->name('adminprofile.update');
        Route::get('/security', [AdminProfileController::class, 'edit'])->name('adminPassword.edit');
        Route::put('/security/{user}', [AdminProfileController::class, 'updatePassword'])->name('adminPassword.update');

        Route::resource('/post', AdminPostsController::class)->except(['create', 'store', 'show']);
        Route::delete('/posts/delete/{postImage}', [AdminPostsController::class, 'destroyImage'])->name('images.destroy');
    });

    Route::prefix('users')->group(function () {
        Route::resource('/posts', UserPostController::class)->except('edit')->middleware('can:manage-post');
        Route::delete('/posts/delete/{postImage}', [UserPostController::class, 'destroyImage'])->name('images.destroy');

        Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/{user}', [UserProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profilePicture/{user}', [UserProfileController::class, 'updateImage'])->name('profile.updateImage');
        Route::put('/security/{user}', [UserProfileController::class, 'updatePassword'])->name('profile.security');
    });

    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    
    Route::delete('/admin/comment/{comment}', [AdminCommentsController::class, 'destroyComment'])->name('comment.destroy');
    Route::delete('/admin/reply/{comment}', [AdminCommentsController::class, 'destroyReply'])->name('reply.destroy');

    Route::get('/download', [AdminUsersController::class, 'download'])->name('download');

    Route::post('/donate/checkout', [DonationController::class, 'checkout'])->name('donate.checkout');
    Route::get('/donate/success', [DonationController::class, 'success'])->name('donate.success');
    Route::get('/donate', function () {return view('donation.index');})->name('donate.index');

    
});
Route::post('/stripe/webhook', StripeWebhookController::class)->name('stripe.webhook');


// $user = User::find(391);
// $roles = $user->getRoleNames();
// $permssions = $user->getAllPermissions();

// dd($roles);



