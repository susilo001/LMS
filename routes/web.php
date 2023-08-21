<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserAssignmentSubmissionController;
use App\Http\Controllers\UserQuizAttemptController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Public Routes
 */
Route::get('/', [HomeController::class, 'index'])->name('dashboard');

Route::resource('course', CourseController::class)
    ->parameter('course', 'course:slug')
    ->only(['index', 'show']);

/**
 * Authenticated Routes
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class)->parameter('categories', 'category:slug');

    Route::get('/module/{module}', [ModuleController::class, 'show'])->name('module.show');

    Route::get('/assignment/{assignment}', [AssignmentController::class, 'show'])->name('assignment.show');

    Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');

    Route::post('/quiz/{quiz}/attempt', [UserQuizAttemptController::class, 'store'])->name('quiz.attempt');

    Route::post('/assignment/{assignment}/submit', [UserAssignmentSubmissionController::class, 'store'])->name('assignment.submit');

    /**
     * Teacher Routes
     */
    Route::group(['middleware' => ['role:teacher']], function () {

        Route::resource('courses', CourseController::class)
            ->parameter('courses', 'course:slug')
            ->except(['index', 'show']);

        Route::resource('modules', ModuleController::class)->except(['index', 'show']);

        Route::resource('assignments', AssignmentController::class)->except(['index', 'show']);

        Route::resource('quizzes', QuizController::class)->except(['index', 'show']);
    });

    /**
     * Student Routes
     */
    Route::group(['middleware' => ['role:student']], function () {
        //
    });

    /**
     * Admin Routes
     */
    Route::group(['middleware' => ['role:admin']], function () {
        //
    });
});

require __DIR__.'/auth.php';
