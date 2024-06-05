<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FirstController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;


Route::get('/', [FirstController::class, 'index'])->name('index');

Auth::routes();




Route::post('/register/confirm', [App\Http\Controllers\UserRegisterController::class, 'register_post'])->name('register.post');





Route::prefix('user')->name('user.')->group(function () {

    Route::get('/register', [App\Http\Controllers\UserRegisterController::class, 'register'])->name('register');
    Route::get('/login', [App\Http\Controllers\UserRegisterController::class, 'user_login'])->name('login');
    Route::post('/login', [App\Http\Controllers\UserRegisterController::class, 'user_login_post'])->name('login');
    Route::get('/logout', [App\Http\Controllers\UserRegisterController::class, 'user_logout'])->name('logout');
});


Route::prefix('user')->name('user.')->middleware('user')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\UserDashboardController::class, 'user_dashboard'])->name('dashboard');

    Route::get('/post_blog', [App\Http\Controllers\UserDashboardController::class, 'user_post_blog'])->name('post_blog');

    Route::post('/post_blog', [App\Http\Controllers\UserDashboardController::class, 'user_blog_add'])->name('blog_add');

    Route::get('/my_blog', [App\Http\Controllers\UserDashboardController::class, 'user_my_blog'])->name('my_blog');
});



Route::get('my_blog/details/{slug}', [App\Http\Controllers\UserDashboardController::class, 'my_blog_details'])->name('my_blog.details')->middleware('user');



Route::get('blog/details/{slug}', [App\Http\Controllers\BlogController::class, 'blog_details'])->name('blog.details');


Route::post('user/comment/{id}', [App\Http\Controllers\BlogController::class, 'user_comment'])->name('user.comment');


Route::post('user/single_comment/{id}', [App\Http\Controllers\BlogController::class, 'user_single_comment'])->name('user.single_comment');


Route::get('category_base/post/{name}', [App\Http\Controllers\BlogController::class, 'category_base_post'])->name('category_base.post');


Route::delete('/delete-post/{id}',  [App\Http\Controllers\BlogController::class, 'deletePost'])->name('delete.post');

Route::post('blog/edit/{id}', [App\Http\Controllers\BlogController::class, 'blog_edit'])->name('blog.edit');
Route::get('category_base/post/{name}', [App\Http\Controllers\BlogController::class, 'category_base_post'])->name('category_base.post');
Route::post('edit/comment/{id}', [App\Http\Controllers\BlogController::class, 'edit_comment'])->name('edit.comment')->middleware('user');











Route::get('admin', function () {
    return redirect()->route('login');
});
Route::get('login', function () {
    return redirect()->route('login');
});
Route::get('register', function () {
    return redirect()->route('login');
});

// Authentication Routes...
Route::prefix('admin')->group(function () {
    // Authentication Routes

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    // Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');


    // Registration Routes
    // Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
    Route::get('users/list', [AdminController::class, 'users_list'])->name('admin.user_list')->middleware('auth:web');
    Route::get('category', [AdminController::class, 'category'])->name('admin.category')->middleware('auth:web');
    Route::post('/category/insert', [AdminController::class, 'category_insert'])->name('category.insert')->middleware('auth:web');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth:web');
});
