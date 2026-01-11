<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile and Settings
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'show'])->name('settings.show');
    Route::put('/settings/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.update-password');
    
    // Zakat Council Members
    Route::resource('zakat-council-members', \App\Http\Controllers\ZakatCouncilMemberController::class);
    
    // Districts
    Route::resource('districts', \App\Http\Controllers\DistrictController::class);
    
    // Address Libraries (Restricted: No District Users - Check in Controllers)
    Route::resource('tehsils', \App\Http\Controllers\TehsilController::class);
    Route::resource('union-councils', \App\Http\Controllers\UnionCouncilController::class);
    Route::resource('villages', \App\Http\Controllers\VillageController::class);
    Route::resource('mohallas', \App\Http\Controllers\MohallaController::class);
    
    // Users Management (Only Super Admin)
    Route::resource('users', \App\Http\Controllers\UserController::class);
    
    // Schemes
    Route::resource('schemes', \App\Http\Controllers\SchemeController::class);
    
    // Financial Years
    Route::resource('financial-years', \App\Http\Controllers\FinancialYearController::class);
    Route::post('financial-years/{financialYear}/set-current', [\App\Http\Controllers\FinancialYearController::class, 'setCurrent'])->name('financial-years.set-current');
    
    // Fund Allocations
    Route::resource('fund-allocations', \App\Http\Controllers\FundAllocationController::class);
    
    // Installments (nested under fund allocations)
    Route::prefix('fund-allocations/{fundAllocation}')->group(function () {
        Route::get('installments/create', [\App\Http\Controllers\InstallmentController::class, 'create'])->name('installments.create');
        Route::post('installments', [\App\Http\Controllers\InstallmentController::class, 'store'])->name('installments.store');
    });
    Route::resource('installments', \App\Http\Controllers\InstallmentController::class)->except(['create', 'store']);
    Route::get('installments/{installment}/print', [\App\Http\Controllers\InstallmentController::class, 'print'])->name('installments.print');
    Route::get('installments/{installment}/pdf', [\App\Http\Controllers\InstallmentController::class, 'pdf'])->name('installments.pdf');
    
    // Local Zakat Committees (Restricted: No District Users - Check in Controller)
    Route::resource('local-zakat-committees', \App\Http\Controllers\LocalZakatCommitteeController::class);
    Route::post('local-zakat-committees/{localZakatCommittee}/add-mohalla', [\App\Http\Controllers\LocalZakatCommitteeController::class, 'addMohalla'])->name('local-zakat-committees.add-mohalla');
    Route::post('local-zakat-committees/{localZakatCommittee}/remove-mohalla', [\App\Http\Controllers\LocalZakatCommitteeController::class, 'removeMohalla'])->name('local-zakat-committees.remove-mohalla');
    
    // LZC Members (Restricted: No District Users - Check in Controller)
    Route::resource('lzc-members', \App\Http\Controllers\LZCMemberController::class)->parameters([
        'lzc-members' => 'lZCMember'
    ]);
    Route::post('lzc-members/store-ajax', [\App\Http\Controllers\LZCMemberController::class, 'storeAjax'])->name('lzc-members.store-ajax');
    Route::get('lzc-members/{lZCMember}/details', [\App\Http\Controllers\LZCMemberController::class, 'getMemberDetails'])->name('lzc-members.details');
    Route::post('lzc-members/{lZCMember}/update-ajax', [\App\Http\Controllers\LZCMemberController::class, 'updateAjax'])->name('lzc-members.update-ajax');
    Route::post('lzc-members/{lZCMember}/delete-ajax', [\App\Http\Controllers\LZCMemberController::class, 'deleteAjax'])->name('lzc-members.delete-ajax');
    Route::get('lzc-members/{lZCMember}/verify', [\App\Http\Controllers\LZCMemberController::class, 'showVerify'])->name('lzc-members.verify');
    Route::post('lzc-members/{lZCMember}/verify', [\App\Http\Controllers\LZCMemberController::class, 'verify'])->name('lzc-members.verify.submit');
    
    // Disbursement Phases (Restricted: No District Users - Check in Controller)
    Route::get('phases/district-quota', [\App\Http\Controllers\PhaseController::class, 'getDistrictQuota'])->name('phases.district-quota');
    Route::get('phases/districts-for-installment', [\App\Http\Controllers\PhaseController::class, 'getDistrictsForInstallment'])->name('phases.districts-for-installment');
    Route::resource('phases', \App\Http\Controllers\PhaseController::class);
    
    // Beneficiaries
    Route::get('beneficiary-applications', [\App\Http\Controllers\BeneficiaryController::class, 'applications'])->name('beneficiary-applications.index');
    Route::get('beneficiary-applications/phases/{phase}', [\App\Http\Controllers\BeneficiaryController::class, 'phaseApplications'])->name('beneficiary-applications.phase');
    Route::get('beneficiaries/get-details/{beneficiary}', [\App\Http\Controllers\BeneficiaryController::class, 'getDetails'])->name('beneficiaries.get-details');
    Route::post('beneficiaries/store-ajax', [\App\Http\Controllers\BeneficiaryController::class, 'storeAjax'])->name('beneficiaries.store-ajax');
    Route::post('beneficiaries/update-ajax/{beneficiary}', [\App\Http\Controllers\BeneficiaryController::class, 'updateAjax'])->name('beneficiaries.update-ajax');
    Route::post('beneficiaries/verify-ajax/{beneficiary}', [\App\Http\Controllers\BeneficiaryController::class, 'verifyAjax'])->name('beneficiaries.verify-ajax');
    Route::delete('beneficiaries/delete-ajax/{beneficiary}', [\App\Http\Controllers\BeneficiaryController::class, 'deleteAjax'])->name('beneficiaries.delete-ajax');
    Route::resource('beneficiaries', \App\Http\Controllers\BeneficiaryController::class);
    Route::get('beneficiaries/scheme-categories/{scheme}', [\App\Http\Controllers\BeneficiaryController::class, 'getSchemeCategories'])->name('beneficiaries.scheme-categories');
    Route::get('beneficiaries/scheme-remaining-amount', [\App\Http\Controllers\BeneficiaryController::class, 'getSchemeRemainingAmount'])->name('beneficiaries.scheme-remaining-amount');
    
    // Administrator HQ Approval
    Route::prefix('admin-hq')->middleware('role:administrator_hq,super_admin')->group(function () {
        Route::get('/pending-approvals', [\App\Http\Controllers\AdminHQController::class, 'pendingApprovals'])->name('admin-hq.pending');
        Route::post('/approve/{beneficiary}', [\App\Http\Controllers\AdminHQController::class, 'approve'])->name('admin-hq.approve');
        Route::post('/reject/{beneficiary}', [\App\Http\Controllers\AdminHQController::class, 'reject'])->name('admin-hq.reject');
        Route::get('/export-approved', [\App\Http\Controllers\AdminHQController::class, 'exportApproved'])->name('admin-hq.export-approved');
    });

    // All Cases - Unified page with tabs (accessible to admin_hq, super_admin, and district_user)
    Route::middleware('role:administrator_hq,super_admin,district_user')->group(function () {
        Route::get('/all-cases', [\App\Http\Controllers\AdminHQController::class, 'allCases'])->name('admin-hq.all-cases');
        
        // Legacy routes for backward compatibility (redirect to all-cases with appropriate tab)
        Route::get('/approved-cases', function() {
            return redirect()->route('admin-hq.all-cases') . '?tab=approved';
        })->name('admin-hq.approved-cases');
        Route::get('/rejected-cases', function() {
            return redirect()->route('admin-hq.all-cases') . '?tab=rejected';
        })->name('admin-hq.rejected-cases');
        
        // Show individual case routes
        Route::get('/approved-cases/{beneficiary}', [\App\Http\Controllers\AdminHQController::class, 'showApprovedCase'])->name('admin-hq.show-approved-case');
        Route::get('/rejected-cases/{beneficiary}', [\App\Http\Controllers\AdminHQController::class, 'showRejectedCase'])->name('admin-hq.show-rejected-case');
        Route::get('/paid-cases/{beneficiary}', [\App\Http\Controllers\AdminHQController::class, 'showPaidCase'])->name('admin-hq.show-paid-case');
        Route::get('/payment-failed-cases/{beneficiary}', [\App\Http\Controllers\AdminHQController::class, 'showPaymentFailedCase'])->name('admin-hq.show-payment-failed-case');
    });
    
        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
            Route::get('/district-wise', [\App\Http\Controllers\ReportController::class, 'districtWise'])->name('reports.district-wise');
            Route::get('/scheme-wise', [\App\Http\Controllers\ReportController::class, 'schemeWise'])->name('reports.scheme-wise');
        });

        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
            Route::post('/{notification}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
            Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
            Route::get('/{notification}', [\App\Http\Controllers\NotificationController::class, 'show'])->name('notifications.show');
            Route::delete('/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
        });

        // JazzCash Response Upload (Only Super Admin and Administrator HQ)
        Route::prefix('jazzcash-response')->middleware('role:administrator_hq,super_admin')->group(function () {
            Route::get('/', [\App\Http\Controllers\JazzCashResponseController::class, 'index'])->name('jazzcash-response.index');
            Route::post('/upload', [\App\Http\Controllers\JazzCashResponseController::class, 'upload'])->name('jazzcash-response.upload');
            Route::get('/download-template', [\App\Http\Controllers\JazzCashResponseController::class, 'downloadTemplate'])->name('jazzcash-response.download-template');
            Route::get('/installments', [\App\Http\Controllers\JazzCashResponseController::class, 'getInstallments'])->name('jazzcash-response.installments');
        });
    });
