<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChangePasswordController;
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
use App\Http\Controllers\Admin\Evaluation360ShareController;
use App\Http\Controllers\ExternalEvaluation360Controller;
use App\Http\Controllers\StudentEvaluation360Controller;
use App\Http\Controllers\Admin\RotationEvaluationController;
use App\Http\Controllers\Admin\CompetencyController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\QrCategoryController;
use App\Http\Controllers\Admin\GeneratedQrController;
use App\Http\Controllers\Admin\PdfController;
use App\Http\Controllers\Admin\GrandWardRoundController;
use App\Http\Controllers\Admin\DailyWardRoundController;
use App\Http\Controllers\Admin\CicuWardRoundController;
use App\Http\Controllers\Admin\ClinicalSessionController;
use App\Http\Controllers\Admin\TraineeDopsController;
use App\Http\Controllers\Admin\DiagnosisController;
use App\Http\Controllers\Admin\ProcedureController;

use App\Http\Controllers\CVBuilder\DashboardController;
use App\Http\Controllers\CVBuilder\CvController;
use App\Http\Controllers\CVBuilder\EducationClinicalController;
use App\Http\Controllers\CVBuilder\ResearchAwardController;
use App\Http\Controllers\CVBuilder\MilestoneController;
use App\Http\Controllers\CVBuilder\DocumentController;
use App\Http\Controllers\CVBuilder\TemplateController;
use App\Http\Controllers\CVBuilder\ProfileController as CvProfileController;

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
        Route::post('evaluation-360/{id}/share', [Evaluation360ShareController::class,'store']);
        Route::post('evaluation-360/share/{id}/approve', [Evaluation360ShareController::class,'approve']);
        Route::post('evaluation-360/share/{id}/unlock', [Evaluation360ShareController::class,'unlock']);
        Route::get('evaluation-360/{id}/responses', [Evaluation360Controller::class,'responses'])->name('evaluation-360.responses');

        Route::get('student/360-result', [StudentEvaluation360Controller::class,'index']);


        Route::resource('rotation-evaluations', RotationEvaluationController::class);
        Route::resource('longitudinal-requirements', LongitudinalRequirementController::class);
        Route::resource('diagnoses', DiagnosisController::class);
        Route::resource('procedures', ProcedureController::class);
        // Grand Ward Rounds
        Route::resource('grand-ward-rounds', GrandWardRoundController::class);
        Route::get('/grand-ward-rounds/{grand_ward_round}/end', [GrandWardRoundController::class, 'end'])->name('grand-ward-rounds.end');
        Route::get('grand-ward-rounds/export/excel', [GrandWardRoundController::class, 'exportExcel'])->name('grand-ward-rounds.export.excel');
        Route::get('grand-ward-rounds/export/pdf', [GrandWardRoundController::class, 'exportPdf'])->name('grand-ward-rounds.export.pdf');
        Route::get('grand-ward-rounds/performance/analysis', [GrandWardRoundController::class, 'performanceAnalysis'])->name('grand-ward-rounds.performance');
        // Clinical Sessions
        Route::resource('clinical-sessions', ClinicalSessionController::class);
        Route::get('/clinical-sessions/{clinical_session}/end', [ClinicalSessionController::class, 'end'])->name('clinical-sessions.end');
        Route::get('clinical-sessions/export/excel', [ClinicalSessionController::class, 'exportExcel'])->name('clinical-sessions.export.excel');
        Route::get('clinical-sessions/export/pdf', [ClinicalSessionController::class, 'exportPdf'])->name('clinical-sessions.export.pdf');
        Route::get('clinical-sessions/performance/analysis', [ClinicalSessionController::class, 'performanceAnalysis'])->name('clinical-sessions.performance');

        // Default Resource Routes
        Route::resource('daily-ward-rounds', DailyWardRoundController::class);
        Route::get('/daily-ward-rounds/{daily_ward_round}/end', [DailyWardRoundController::class, 'end'])->name('daily-ward-rounds.end');
        Route::get('daily-ward-rounds/export/excel', [DailyWardRoundController::class, 'exportExcel'])->name('daily-ward-rounds.export.excel');
        Route::get('daily-ward-rounds/export/pdf', [DailyWardRoundController::class, 'exportPdf'])->name('daily-ward-rounds.export.pdf');
        Route::get('daily-ward-rounds/performance/analysis', [DailyWardRoundController::class, 'performanceAnalysis'])->name('daily-ward-rounds.performance');

        Route::prefix('cicu-ward-rounds')->group(function() {
            Route::get('performance', [CicuWardRoundController::class,'performanceAnalysis'])
                ->name('cicu-ward-rounds.performance');
            Route::get('export/excel', [CicuWardRoundController::class,'exportExcel'])
                ->name('cicu-ward-rounds.export.excel');
            Route::get('export/pdf', [CicuWardRoundController::class,'exportPdf'])
                ->name('cicu-ward-rounds.export.pdf');
            Route::get('end/{cicu_ward_round}/end', [CicuWardRoundController::class,'end'])
                ->name('cicu-ward-rounds.end');

            // Resource route with proper names
            Route::resource('/', CicuWardRoundController::class)
                ->parameters(['' => 'cicu_ward_round'])
                ->names([
                    'index'   => 'cicu-ward-rounds.index',
                    'create'  => 'cicu-ward-rounds.create',
                    'store'   => 'cicu-ward-rounds.store',
                    'show'    => 'cicu-ward-rounds.show',
                    'edit'    => 'cicu-ward-rounds.edit',
                    'update'  => 'cicu-ward-rounds.update',
                    'destroy' => 'cicu-ward-rounds.destroy',
                ]);
        });


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
        Route::resource('pdfs', PdfController::class);
        Route::get('pdfs/{page_name}', [PdfController::class, 'show'])->name('pdfs.show');
        Route::get('/pdfs/stream/{filename}', [PdfController::class, 'streamPdf'])->name('pdfs.stream');
        // Stream images
        Route::get('/user/profile/{filename}', [ProfileController::class, 'streamProfileImage'])
            ->name('user.profile.stream')->middleware('auth');

        Route::get('/cv/document/{filename}', [DocumentController::class, 'streamDocument'])
        ->name('cv.document.stream')
        ->middleware('auth');
        
        Route::get('/user/signature/{filename}', [ProfileController::class, 'streamSignatureImage'])
            ->name('user.signature.stream')->middleware('auth');

        Route::get('/profile',[ProfileController::class,'index'])->name('profile.index');
        Route::get('/my-profile',[ProfileController::class, 'myProfile'])->name('my.profile');
        Route::get('/public-profile', [ProfileController::class, 'publicProfile'])->name('public.profile');
        Route::post('/profile/update',[ProfileController::class,'update'])->name('profile.update');
        Route::post('/profile/tab/save',[ProfileController::class,'saveTab'])->name('profile.tab.save');
        Route::delete('/profile/tab/{id}',[ProfileController::class,'deleteTab'])->name('profile.tab.delete');
        // Route::prefix('profile')->group(function () {
        //     Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        //     Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        //     Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        // });
         Route::get('change-password', [ChangePasswordController::class, 'edit'])
        ->name('password.edit');

        Route::put('change-password', [ChangePasswordController::class, 'update'])
        ->name('password.update');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        });

        Route::prefix('assessor')->name('assessor.')->group(function () {
            Route::get('/dashboard', [AssessorDashboardController::class, 'index'])->name('dashboard');
                    // List assigned forms
            Route::get('evaluation-360', [Evaluation360Controller::class,'index'])->name('360.index');
            // Open assigned form
            Route::get('evaluation-360/{id}', [ExternalEvaluation360Controller::class,'show'])->name('360.show');
           // Save / Submit
            Route::post('evaluation-360/{share}/save', [ExternalEvaluation360Controller::class,'save'])->name('360.save');
            Route::post('evaluation-360/{share}/submit', [ExternalEvaluation360Controller::class,'submit'])->name('360.submit');

        });

        Route::prefix('trainee')->name('trainee.')->group(function () {
            Route::get('/dashboard', [TraineeDashboardController::class, 'index'])->name('dashboard');
            Route::get('pdfs/{page_name}/{page_key}/{file?}', [TraineeDashboardController::class, 'show'])->name('pdfs.show');

            Route::get('/invitations', [ExamInvitationController::class, 'myInvitations'])->name('invitations');

            Route::get('exams',[TraineeExamController::class,'index'])->name('exams');
            Route::post('exam/start/{exam}',[TraineeExamController::class,'start'])->name('exam.start');
            Route::post('exam/submit/{exam}',[TraineeExamController::class,'submit'])->name('exam.submit');
            Route::get('results',[TraineeResultController::class,'index'])->name('results.index');
            Route::get('results/{exam}',[TraineeResultController::class,'view'])->name('results.view');

            Route::resource('/dops', TraineeDopsController::class);
            Route::get('/get-dops/{rotation}', [TraineeDopsController::class, 'getDops']); // For rotation → DOPS dropdown
            Route::get('/dops/{traine_dops}/end', [TraineeDopsController::class, 'end'])->name('dops.end');
            Route::get('/dops/export/excel', [TraineeDopsController::class, 'exportExcel'])->name('dops.export.excel');
            Route::get('/dops/export/pdf', [TraineeDopsController::class, 'exportPdf'])->name('dops.export.pdf');

            Route::get('360-result', [StudentEvaluation360Controller::class,'index'])->name('360.index');
            });
    
    
        Route::prefix('cvbuilder')->group(function () {
            Route::get('/cv-dashboard', [DashboardController::class, 'index'])->name('cv.dashboard');
            Route::resource('cv',CvController::class);
            Route::resource('education', EducationClinicalController::class);
            Route::resource('research', ResearchAwardController::class);
            Route::resource('milestone', MilestoneController::class);
            Route::resource('document', DocumentController::class);
            Route::resource('templates', TemplateController::class);
            Route::post('templates/{template}/set-default', [TemplateController::class, 'setDefault'])->name('templates.setDefault');
            Route::get('preview/{id}', [CvController::class, 'preview'])->name('cv.preview');
            Route::get('pdf/{id}', [CvController::class, 'pdf'])->name('cv.pdf');
            Route::post('share/{id}', [CvController::class, 'share'])->name('cv.share');
            Route::get('public/{token}', [CvController::class, 'publicView'])->name('cv.public');
            
            // Profile routes
            Route::post('cv-profile', [CvProfileController::class, 'store'])->name('profile.store');
            Route::put('cv-profile/{id}', [CvProfileController::class, 'update'])->name('profile.update');
        });
    });
    // External evaluation
    Route::get('360/evaluation/{share}', [ExternalEvaluation360Controller::class,'show']);
    Route::post('360/evaluation/{share}/save', [ExternalEvaluation360Controller::class,'save']);
    Route::post('360/evaluation/{share}/submit', [ExternalEvaluation360Controller::class,'submit']);
    Route::get('360/evaluation/{share}/enter-pin', [ExternalEvaluation360Controller::class,'enterPinView']);
    Route::post('360/evaluation/{share}/check-pin', [ExternalEvaluation360Controller::class,'checkPin']);

require __DIR__.'/auth.php';
