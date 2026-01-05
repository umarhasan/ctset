<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Assessor\AssessorDashboardController;
use App\Http\Controllers\Trainee\TraineeDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
// Master
use App\Http\Controllers\Admin\TestTypeController;
use App\Http\Controllers\Admin\MarketingTypeController;
use App\Http\Controllers\Admin\QuestionTypeController;
use App\Http\Controllers\Admin\TimeTableCategoryController;
use App\Http\Controllers\Admin\AssignmentFromTypeController;
use App\Http\Controllers\Admin\VideoMainCategoryController;
use App\Http\Controllers\Admin\VideoCategoryController;
use App\Http\Controllers\Admin\ExamDurationTypeController;
use App\Http\Controllers\Admin\YesNoOptionController;
use App\Http\Controllers\Admin\ParentMenuController;
use App\Http\Controllers\Admin\MenuPageController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\TraineeEvaluationController;
use App\Http\Controllers\Admin\EvaluationPointController;
use App\Http\Controllers\Admin\LongitudinalRequirementController;
use App\Http\Controllers\Admin\DopController;
use App\Http\Controllers\Admin\DopStepController;
// Exam
use App\Http\Controllers\Admin\ExamController;
Route::get('/', function () {
    return view('welcome');
});

    Route::middleware(['auth'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserController::class);
        Route::resource('exams', ExamController::class);
        // Master
        Route::resource('test-types', TestTypeController::class);
        Route::resource('marketing-types', MarketingTypeController::class);
        Route::resource('question-types', QuestionTypeController::class);
        Route::resource('time-table-categories', TimeTableCategoryController::class);
        Route::resource('assignment-from-types', AssignmentFromTypeController::class);
        Route::resource('video-main-categories', VideoMainCategoryController::class);
        Route::resource('video-categories', VideoCategoryController::class);
        Route::resource('exam-duration-types', ExamDurationTypeController::class);
        Route::resource('yes-no-options', YesNoOptionController::class);
        Route::resource('parent-menus', ParentMenuController::class);
        Route::resource('menu-pages', MenuPageController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('qr-codes', QrCodeController::class);
        Route::resource('ads', AdController::class);
        Route::resource('trainee-evaluations', TraineeEvaluationController::class);
        Route::resource('evaluation-points', EvaluationPointController::class);
        Route::resource('longitudinal-requirements', LongitudinalRequirementController::class);
        Route::resource('dops', DopController::class);
        Route::resource('dop-steps', DopStepController::class); 

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

    
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        });

        Route::prefix('assessor')->name('assessor.')->group(function () {
            Route::get('/dashboard', [AssessorDashboardController::class, 'index'])->name('dashboard');
        });

        Route::prefix('trainee')->name('trainee.')->group(function () {
            Route::get('/dashboard', [TraineeDashboardController::class, 'index'])->name('dashboard');
        });
    });

require __DIR__.'/auth.php';
