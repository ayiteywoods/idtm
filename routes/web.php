<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Faculty\DashboardController as FacultyDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Portal\StudentSearchController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pages/{slug}', [HomeController::class, 'page'])->name('pages.show');
Route::get('/programmes/{programme:slug}', [HomeController::class, 'programme'])->name('programmes.show');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/admin/login', [LoginController::class, 'createAdmin'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'storeAdmin'])->name('admin.login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/portal/student-search', StudentSearchController::class)
    ->middleware('auth')
    ->name('portal.student-search');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/students', [AdminDashboardController::class, 'students'])->name('students.index');
    Route::get('/students/create', [AdminDashboardController::class, 'createStudent'])->name('students.create');
    Route::post('/students', [AdminDashboardController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/{student}/print', [AdminDashboardController::class, 'printStudent'])->name('students.print');
    Route::get('/students/{student}/edit', [AdminDashboardController::class, 'editStudent'])->name('students.edit');
    Route::put('/students/{student}', [AdminDashboardController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{student}', [AdminDashboardController::class, 'destroyStudent'])->name('students.destroy');
    Route::get('/faculty', [AdminDashboardController::class, 'faculty'])->name('faculty.index');
    Route::get('/faculty/create', [AdminDashboardController::class, 'createFaculty'])->name('faculty.create');
    Route::post('/faculty', [AdminDashboardController::class, 'storeFaculty'])->name('faculty.store');
    Route::get('/faculty/{faculty}/edit', [AdminDashboardController::class, 'editFaculty'])->name('faculty.edit');
    Route::put('/faculty/{faculty}', [AdminDashboardController::class, 'updateFaculty'])->name('faculty.update');
    Route::delete('/faculty/{faculty}', [AdminDashboardController::class, 'destroyFaculty'])->name('faculty.destroy');
    Route::get('/courses', [AdminDashboardController::class, 'courses'])->name('courses.index');
    Route::get('/courses/create', [AdminDashboardController::class, 'createCourse'])->name('courses.create');
    Route::post('/courses', [AdminDashboardController::class, 'storeCourse'])->name('courses.store');
    Route::get('/courses/{course}/edit', [AdminDashboardController::class, 'editCourse'])->name('courses.edit');
    Route::put('/courses/{course}', [AdminDashboardController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{course}', [AdminDashboardController::class, 'destroyCourse'])->name('courses.destroy');
    Route::get('/exam-reports', [AdminDashboardController::class, 'examReports'])->name('exam-reports.index');
    Route::get('/exam-reports/{course}/print', [AdminDashboardController::class, 'printExamReport'])->name('exam-reports.print');
    Route::get('/change-requests', [AdminDashboardController::class, 'changeRequests'])->name('change-requests.index');
    Route::patch('/change-requests/{changeRequest}', [AdminDashboardController::class, 'reviewChangeRequest'])->name('change-requests.review');
    Route::delete('/change-requests/{changeRequest}', [AdminDashboardController::class, 'destroyChangeRequest'])->name('change-requests.destroy');
    Route::get('/settings', [AdminDashboardController::class, 'siteSettings'])->name('settings.index');
    Route::put('/settings', [AdminDashboardController::class, 'updateSiteSettings'])->name('settings.update');
    Route::get('/website', [AdminDashboardController::class, 'website'])->name('website.index');
    Route::get('/website/homepage/edit', [AdminDashboardController::class, 'editHomepage'])->name('website.homepage.edit');
    Route::put('/website/homepage', [AdminDashboardController::class, 'updateHomepage'])->name('website.homepage.update');
    Route::get('/website/footer/edit', [AdminDashboardController::class, 'editFooter'])->name('website.footer.edit');
    Route::put('/website/footer', [AdminDashboardController::class, 'updateFooter'])->name('website.footer.update');
    Route::get('/website/pages/create', [AdminDashboardController::class, 'createWebsitePage'])->name('website.pages.create');
    Route::post('/website/pages', [AdminDashboardController::class, 'storeWebsitePage'])->name('website.pages.store');
    Route::get('/website/pages/{sitePage}/edit', [AdminDashboardController::class, 'editWebsitePage'])->name('website.pages.edit');
    Route::put('/website/pages/{sitePage}', [AdminDashboardController::class, 'updateWebsitePage'])->name('website.pages.update');
});

Route::middleware(['auth', 'role:student'])->prefix('portal')->name('student.')->group(function () {
    Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
    Route::get('/wallet', [StudentDashboardController::class, 'wallet'])->name('wallet');
    Route::get('/registration', [StudentDashboardController::class, 'registration'])->name('registration');
    Route::get('/registration/catalog', [StudentDashboardController::class, 'registrationCatalog'])->name('registration.catalog');
    Route::post('/registration', [StudentDashboardController::class, 'storeRegistration'])->name('registration.store');
    Route::get('/grades', [StudentDashboardController::class, 'grades'])->name('grades');
    Route::get('/assessments', [StudentDashboardController::class, 'assessments'])->name('assessments');
    Route::post('/assessments/{assessment}/submit', [StudentDashboardController::class, 'submitAssessment'])->name('assessments.submit');
    Route::get('/assessments/{assessment}/brief', [StudentDashboardController::class, 'downloadAssessmentBrief'])->name('assessments.brief');
    Route::get('/submissions/{submission}/download', [StudentDashboardController::class, 'downloadSubmission'])->name('submissions.download');
    Route::get('/learning-materials', [StudentDashboardController::class, 'learningMaterials'])->name('learning-materials');
    Route::get('/materials/{material}/download', [StudentDashboardController::class, 'downloadMaterial'])->name('materials.download');
    Route::get('/library', [StudentDashboardController::class, 'library'])->name('library');
    Route::get('/library/{book}/download', [StudentDashboardController::class, 'downloadLibraryBook'])->name('library.download');
    Route::get('/help-desk', [StudentDashboardController::class, 'helpDesk'])->name('help-desk');
    Route::get('/change-requests', [StudentDashboardController::class, 'changeRequests'])->name('change-requests');
    Route::post('/change-requests', [StudentDashboardController::class, 'storeChangeRequest'])->name('change-requests.store');
    Route::post('/wallet/deposit', [StudentDashboardController::class, 'storeWalletDeposit'])->name('wallet.deposit');
});

Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/', [FacultyDashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', [FacultyDashboardController::class, 'courses'])->name('courses.index');
    Route::get('/courses/{course}/students', [FacultyDashboardController::class, 'courseStudents'])->name('courses.students');
    Route::get('/materials', [FacultyDashboardController::class, 'materials'])->name('materials.index');
    Route::post('/materials', [FacultyDashboardController::class, 'storeMaterial'])->name('materials.store');
    Route::get('/materials/{material}/download', [FacultyDashboardController::class, 'downloadMaterial'])->name('materials.download');
    Route::delete('/materials/{material}', [FacultyDashboardController::class, 'destroyMaterial'])->name('materials.destroy');
    Route::get('/grades', [FacultyDashboardController::class, 'grades'])->name('grades.index');
    Route::post('/grades', [FacultyDashboardController::class, 'storeGrade'])->name('grades.store');
    Route::get('/assessments', [FacultyDashboardController::class, 'assessments'])->name('assessments.index');
    Route::post('/assessments', [FacultyDashboardController::class, 'storeAssessment'])->name('assessments.store');
    Route::get('/assessments/{assessment}', [FacultyDashboardController::class, 'showAssessment'])->name('assessments.show');
    Route::delete('/assessments/{assessment}', [FacultyDashboardController::class, 'destroyAssessment'])->name('assessments.destroy');
    Route::get('/assessments/{assessment}/brief', [FacultyDashboardController::class, 'downloadAssessmentBrief'])->name('assessments.brief');
    Route::post('/submissions/{submission}/grade', [FacultyDashboardController::class, 'gradeSubmission'])->name('submissions.grade');
    Route::get('/submissions/{submission}/download', [FacultyDashboardController::class, 'downloadSubmission'])->name('submissions.download');
    Route::get('/library', [FacultyDashboardController::class, 'library'])->name('library.index');
    Route::post('/library', [FacultyDashboardController::class, 'storeLibraryBook'])->name('library.store');
    Route::get('/library/{book}/download', [FacultyDashboardController::class, 'downloadLibraryBook'])->name('library.download');
    Route::delete('/library/{book}', [FacultyDashboardController::class, 'destroyLibraryBook'])->name('library.destroy');
});
