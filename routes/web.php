<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Assessor\AssessorDashboardController;
use App\Http\Controllers\Trainee\TraineeDashboardController;
use App\Http\Controllers\Trainee\TraineeExamController;
use App\Http\Controllers\Trainee\TraineeResultController;
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
use App\Http\Controllers\Admin\ExamInvitationController;
use App\Http\Controllers\Admin\ExamMatrixController;
use App\Http\Controllers\Admin\WorkCloudController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\TimetableEventController;
use App\Http\Controllers\Admin\FormTypeController;
use App\Http\Controllers\Admin\RotationController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\SelfEvaluationController;
use App\Http\Controllers\Admin\Evaluation360Controller;
use App\Http\Controllers\Admin\RotationEvaluationController;
use App\Http\Controllers\Admin\CompetencyController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\QrCategoryController;
use App\Http\Controllers\Admin\GeneratedQrController;

// Exam
use App\Http\Controllers\Admin\ExamController;
    Route::get('/', function () {
        return auth()->check()
            ? redirect()->route(route: 'admin.dashboard')
            : redirect()->route('login');
    });

    Route::middleware(['auth'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserController::class);
        Route::resource('exams', ExamController::class);
        Route::resource('exam_matrices', ExamMatrixController::class);
        Route::resource('work_clouds', WorkCloudController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('topics', TopicController::class);
        Route::resource('semesters', SemesterController::class);
        Route::resource('hospitals', HospitalController::class);
        Route::resource('timetable-events', TimetableEventController::class);
        Route::resource('ads', AdController::class);
        Route::resource('form-types', FormTypeController::class);
        Route::resource('rotations', RotationController::class);
        Route::resource('assignments', AssignmentController::class);
        Route::resource('self-evaluations', SelfEvaluationController::class);
        Route::resource('trainee-evaluations', TraineeEvaluationController::class);
        Route::resource('evaluation-360', Evaluation360Controller::class);
        Route::resource('rotation-evaluations', RotationEvaluationController::class);
        Route::resource('longitudinal-requirements', LongitudinalRequirementController::class);

        Route::resource('competencies', CompetencyController::class);
        Route::resource('ratings', RatingController::class);
        Route::resource('levels', LevelController::class);
        Route::resource('qr-categories', QrCategoryController::class);
        Route::resource('qr-generate', GeneratedQrController::class);

        Route::get('/pending', [ExamInvitationController::class, 'pendingExams'])->name('exams.pending');
        Route::get('/{exam}/send-invite', [ExamInvitationController::class, 'sendInvite'])->name('exams.send-invite');
        Route::post('/{exam}/send-invite', [ExamInvitationController::class, 'sendInviteAction'])->name('exams.send-invite.action');
        Route::get('/sent-invites', [ExamInvitationController::class, 'sentInvites']) ->name('exams.sent-invites');
        Route::get('/{examId}/invited-students', [ExamInvitationController::class, 'viewInvitedStudents'])->name('exams.view-invited-students');
        Route::post('/invitations/{invitation}/update-status', [ExamInvitationController::class, 'updateStatus'])->name('exams.update-status');

        Route::get('results/pending',[ResultController::class,'pending'])->name('results.pending');
        Route::post('results/calculate/{exam}',[ResultController::class,'calculate'])->name('results.calculate');
        Route::get('results/view/{exam}',[ResultController::class,'view'])->name('results.view');
        Route::post('results/announce/{exam}',[ResultController::class,'announce'])->name('results.announce');
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
            Route::get('/invitations', [ExamInvitationController::class, 'myInvitations'])->name('invitations');

            Route::get('exams',[TraineeExamController::class,'index'])->name('exams');
            Route::post('exam/start/{exam}',[TraineeExamController::class,'start'])->name('exam.start');
            Route::post('exam/submit/{exam}',[TraineeExamController::class,'submit'])->name('exam.submit');
            Route::get('results',[TraineeResultController::class,'index'])->name('results.index');
            Route::get('results/{exam}',[TraineeResultController::class,'view'])->name('results.view');
        });
    });

require __DIR__.'/auth.php';
