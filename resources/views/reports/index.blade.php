@extends('layouts.app')

@section('title', config('app.name') . ' - Reports')
@section('page_title', 'Reports')
@section('breadcrumb', 'Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <p class="text-muted mb-4">Select a report to view and export. All reports support Excel and PDF export.</p>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.executive-summary') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-primary"></div>
                        <i class="ti-dashboard text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Executive Summary</h5>
                        <p class="card-text small text-muted mb-0">Overview of beneficiaries, funds, and key metrics</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.district-wise') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-success"></div>
                        <i class="ti-map-alt text-success"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">District-wise Report</h5>
                        <p class="card-text small text-muted mb-0">Beneficiaries and amounts by district</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.scheme-wise') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-info"></div>
                        <i class="ti-bookmark-alt text-info"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Scheme-wise Report</h5>
                        <p class="card-text small text-muted mb-0">Beneficiaries and amounts by scheme</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.beneficiary-status') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-warning"></div>
                        <i class="ti-pie-chart text-warning"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Beneficiary Status</h5>
                        <p class="card-text small text-muted mb-0">Counts by status (pending, approved, paid, etc.)</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.phase-wise') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-secondary"></div>
                        <i class="ti-calendar text-secondary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Phase-wise Report</h5>
                        <p class="card-text small text-muted mb-0">Beneficiaries and amounts per disbursement phase</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.fund-disbursement') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-dark"></div>
                        <i class="ti-wallet text-white"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Fund Disbursement</h5>
                        <p class="card-text small text-muted mb-0">Allocation vs disbursed vs remaining</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.financial-year-summary') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-primary"></div>
                        <i class="ti-agenda text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Financial Year Summary</h5>
                        <p class="card-text small text-muted mb-0">Summary by financial year</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.lzc-wise') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-success"></div>
                        <i class="ti-id-badge text-success"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">LZC / Committee-wise</h5>
                        <p class="card-text small text-muted mb-0">Beneficiaries by Local Zakat Committee</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.institution-wise') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-info"></div>
                        <i class="ti-home text-info"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Institution-wise Report</h5>
                        <p class="card-text small text-muted mb-0">Institutional beneficiaries by institution</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.payment-report') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-warning"></div>
                        <i class="ti-credit-card text-warning"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Payment Report</h5>
                        <p class="card-text small text-muted mb-0">Paid, pending, and failed payments</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4 mb-3">
        <a href="{{ route('reports.beneficiary-list') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm card-hover-shadow-2x">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper rounded-circle me-3">
                        <div class="icon-wrapper-bg bg-secondary"></div>
                        <i class="ti-list text-secondary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Beneficiary List</h5>
                        <p class="card-text small text-muted mb-0">Detailed list with filters</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
