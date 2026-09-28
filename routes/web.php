<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\EnsureAccountIsActive;
use App\Livewire\Admin\AuditLogViewer;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\HomepageEditor;
use App\Livewire\Admin\LoginHistoryViewer;
use App\Livewire\Admin\MediaLibrary;
use App\Livewire\Admin\RolePermissionMatrix;
use App\Livewire\Admin\ThemeEditor;
use App\Http\Controllers\PublicPageController;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\WebsiteSettings;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Multi-Page NGO Architecture)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PublicPageController::class, 'about'])->name('about');
Route::get('/programs', [PublicPageController::class, 'programs'])->name('programs');
Route::get('/programs/{slug}', [PublicPageController::class, 'programDetail'])->name('programs.detail');
Route::get('/impact', [PublicPageController::class, 'impact'])->name('impact');
Route::get('/stories', [PublicPageController::class, 'stories'])->name('stories');
Route::get('/stories/{slug}', [PublicPageController::class, 'storyDetail'])->name('stories.detail');
Route::get('/events', [PublicPageController::class, 'events'])->name('events');
Route::get('/events/{slug}', [PublicPageController::class, 'eventDetail'])->name('events.detail');
Route::get('/campaigns/{slug}', [PublicPageController::class, 'campaignDetail'])->name('campaigns.detail');
Route::get('/gallery', [PublicPageController::class, 'gallery'])->name('gallery');
Route::get('/contact', [PublicPageController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicPageController::class, 'submitContact'])->name('contact.submit');
Route::get('/volunteer', [PublicPageController::class, 'volunteer'])->name('volunteer');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('throttle:10,1');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Protected Admin Portal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', EnsureAccountIsActive::class])
    ->group(function () {
        Route::get('/', Dashboard::class)->name('admin.dashboard');

        // Member & Volunteer Management (Phase 3A & 3B)
        Route::get('/members', \App\Livewire\Admin\MemberManagement::class)->middleware('can:members.view')->name('admin.members.index');
        Route::get('/volunteers', \App\Livewire\Admin\VolunteerManagement::class)->middleware('can:volunteers.view')->name('admin.volunteers.index');
        Route::get('/volunteers-directory', \App\Livewire\Admin\VolunteerManagement::class)->middleware('can:volunteers.view')->name('admin.volunteers');

        // User & Role Management
        Route::get('/users', UserManagement::class)->middleware('can:users.view')->name('admin.users');
        Route::get('/roles', RolePermissionMatrix::class)->middleware('can:roles.view')->name('admin.roles');

        // Website CMS
        Route::get('/website/homepage', HomepageEditor::class)->middleware('can:homepage.view')->name('admin.website.homepage');
        Route::get('/website/theme', ThemeEditor::class)->middleware('can:theme.view')->name('admin.website.theme');
        Route::get('/website/media', MediaLibrary::class)->middleware('can:media.view')->name('admin.website.media');
        Route::get('/website/settings', WebsiteSettings::class)->middleware('can:website.view')->name('admin.website.settings');

        // Security & Auditing
        Route::get('/security/audit-logs', AuditLogViewer::class)->middleware('can:audit_logs.view')->name('admin.security.audit-logs');
        Route::get('/security/login-history', LoginHistoryViewer::class)->middleware('can:login_histories.view')->name('admin.security.login-history');
    });
