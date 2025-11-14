<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ProfileController;

Route::get('/test', function () {
    return 'Laravel is working!';
});

// Public routes
Route::get('/', [ResumeController::class, 'show']);
Route::get('/resume', [ResumeController::class, 'show'])->name('resume.show');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/edit-resume', [ResumeController::class, 'edit'])->name('resume.edit');

    // Profile updates
    Route::post('/profile/user-info', [ProfileController::class, 'updateUserInfo'])->name('profile.user-info');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Education CRUD
    Route::post('/education/add', [ProfileController::class, 'addEducation'])->name('education.add');
    Route::post('/education/update/{id}', [ProfileController::class, 'updateEducation'])->name('education.update');
    Route::delete('/education/delete/{id}', [ProfileController::class, 'deleteEducation'])->name('education.delete');

    // Project CRUD
    Route::post('/project/add', [ProfileController::class, 'addProject'])->name('project.add');
    Route::post('/project/update/{id}', [ProfileController::class, 'updateProject'])->name('project.update');
    Route::delete('/project/delete/{id}', [ProfileController::class, 'deleteProject'])->name('project.delete');

    // Skill CRUD
    Route::post('/skill/add', [ProfileController::class, 'addSkill'])->name('skill.add');
    Route::delete('/skill/delete/{id}', [ProfileController::class, 'deleteSkill'])->name('skill.delete');

    // Award CRUD
    Route::post('/award/add', [ProfileController::class, 'addAward'])->name('award.add');
    Route::delete('/award/delete/{id}', [ProfileController::class, 'deleteAward'])->name('award.delete');
});
