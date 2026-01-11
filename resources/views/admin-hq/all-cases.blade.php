@extends('layouts.app')

@section('title', config('app.name') . ' - All Cases')
@section('page_title', 'All Cases')
@section('breadcrumb', 'All Cases')

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #495057;
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
    }
    
    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }
    
    .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: 600;
    }
    
    .tab-content {
        padding-top: 20px;
    }
    
    .badge-count {
        margin-left: 5px;
        font-size: 0.75rem;
    }
    
    /* DataTables Buttons Styling */
    .dt-buttons {
        margin-bottom: 15px;
    }
    
    .dt-buttons .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    .dt-buttons .btn i {
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Beneficiary Cases</h3>
                    </div>
                    @if(auth()->user()->isAdministratorHQ() || auth()->user()->isSuperAdmin())
                    <div class="header_more_tool">
                        <div class="dropdown">
                            <span class="dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                <i class="ti-more-alt"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('admin-hq.export-approved') }}"><i class="ti-download"></i> Export Approved</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="white_card_body">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="casesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                            Pending Approvals
                            <span class="badge bg-warning badge-count">{{ $pendingBeneficiaries->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                            Approved Cases
                            <span class="badge bg-success badge-count">{{ $approvedBeneficiaries->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">
                            Rejected Cases
                            <span class="badge bg-danger badge-count">{{ $rejectedBeneficiaries->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab">
                            Paid Cases
                            <span class="badge bg-info badge-count">{{ $paidBeneficiaries->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payment-failed-tab" data-bs-toggle="tab" data-bs-target="#payment-failed" type="button" role="tab">
                            Payment Failed Cases
                            <span class="badge bg-danger badge-count">{{ $paymentFailedBeneficiaries->count() }}</span>
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="casesTabContent">
                    <!-- Pending Approvals Tab -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        @if($pendingBeneficiaries->isEmpty())
                            <div class="alert alert-info">
                                <i class="ti-info-alt"></i> No pending approvals found.
                            </div>
                        @else
                            @include('admin-hq.partials.cases-table', [
                                'beneficiaries' => $pendingBeneficiaries,
                                'districts' => $districts,
                                'schemes' => $schemes,
                                'financialYears' => $financialYears,
                                'tableId' => 'pendingTable',
                                'type' => 'pending',
                                'emptyMessage' => 'No pending approvals found.'
                            ])
                        @endif
                    </div>

                    <!-- Approved Cases Tab -->
                    <div class="tab-pane fade" id="approved" role="tabpanel">
                        @include('admin-hq.partials.cases-table', [
                            'beneficiaries' => $approvedBeneficiaries,
                            'districts' => $districts,
                            'schemes' => $schemes,
                            'financialYears' => $financialYears,
                            'tableId' => 'approvedTable',
                            'type' => 'approved',
                            'emptyMessage' => 'No approved beneficiaries found.'
                        ])
                    </div>

                    <!-- Rejected Cases Tab -->
                    <div class="tab-pane fade" id="rejected" role="tabpanel">
                        @include('admin-hq.partials.cases-table', [
                            'beneficiaries' => $rejectedBeneficiaries,
                            'districts' => $districts,
                            'schemes' => $schemes,
                            'financialYears' => $financialYears,
                            'tableId' => 'rejectedTable',
                            'type' => 'rejected',
                            'emptyMessage' => 'No rejected beneficiaries found.'
                        ])
                    </div>

                    <!-- Paid Cases Tab -->
                    <div class="tab-pane fade" id="paid" role="tabpanel">
                        @include('admin-hq.partials.cases-table', [
                            'beneficiaries' => $paidBeneficiaries,
                            'districts' => $districts,
                            'schemes' => $schemes,
                            'financialYears' => $financialYears,
                            'tableId' => 'paidTable',
                            'type' => 'paid',
                            'emptyMessage' => 'No paid beneficiaries found.'
                        ])
                    </div>

                    <!-- Payment Failed Cases Tab -->
                    <div class="tab-pane fade" id="payment-failed" role="tabpanel">
                        @include('admin-hq.partials.cases-table', [
                            'beneficiaries' => $paymentFailedBeneficiaries,
                            'districts' => $districts,
                            'schemes' => $schemes,
                            'financialYears' => $financialYears,
                            'tableId' => 'paymentFailedTable',
                            'type' => 'payment-failed',
                            'emptyMessage' => 'No payment failed beneficiaries found.'
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables Buttons JS -->
<script src="{{ asset('assets/vendors/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/jszip.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/vfs_fonts.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables for each tab when it becomes active
        $('#casesTab button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const target = $(e.target).data('bs-target');
            const tableId = target === '#pending' ? 'pendingTable' :
                           target === '#approved' ? 'approvedTable' :
                           target === '#rejected' ? 'rejectedTable' :
                           target === '#paid' ? 'paidTable' :
                           'paymentFailedTable';
            
            if (!$.fn.DataTable.isDataTable('#' + tableId)) {
                initializeDataTable(tableId, target);
            }
        });

        // Initialize the active tab's DataTable on page load
        const activeTab = $('#casesTab .nav-link.active').data('bs-target');
        const activeTableId = activeTab === '#pending' ? 'pendingTable' :
                             activeTab === '#approved' ? 'approvedTable' :
                             activeTab === '#rejected' ? 'rejectedTable' :
                             activeTab === '#paid' ? 'paidTable' :
                             'paymentFailedTable';
        
        if (activeTableId) {
            initializeDataTable(activeTableId, activeTab);
        }

        function initializeDataTable(tableId, tabTarget) {
            const table = $('#' + tableId);
            if (table.length === 0) return;

            // Get filter inputs for this tab
            const filterPrefix = tabTarget.replace('#', '');
            const filterDistrict = $('#filterDistrict_' + filterPrefix);
            const filterScheme = $('#filterScheme_' + filterPrefix);
            const filterFinancialYear = $('#filterFinancialYear_' + filterPrefix);
            const filterSearch = $('#filterSearch_' + filterPrefix);

            const dataTable = table.DataTable({
                responsive: true,
                pageLength: 25,
                scrollX: true,
                scrollCollapse: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="ti-files"></i> Copy',
                        className: 'btn btn-sm btn-secondary',
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude Actions column
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="ti-file"></i> CSV',
                        className: 'btn btn-sm btn-secondary',
                        filename: filterPrefix + '-cases-' + new Date().toISOString().split('T')[0],
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="ti-file"></i> Excel',
                        className: 'btn btn-sm btn-success',
                        filename: filterPrefix + '-cases-' + new Date().toISOString().split('T')[0],
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="ti-file"></i> PDF',
                        className: 'btn btn-sm btn-danger',
                        filename: filterPrefix + '-cases-' + new Date().toISOString().split('T')[0],
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="ti-printer"></i> Print',
                        className: 'btn btn-sm btn-info',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries to show",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                }
            });

            // Apply filters
            if (filterDistrict.length) {
                filterDistrict.on('change', function() {
                    dataTable.column(2).search(this.value).draw();
                });
            }

            if (filterScheme.length) {
                filterScheme.on('change', function() {
                    dataTable.column(3).search(this.value).draw();
                });
            }

            if (filterFinancialYear.length) {
                filterFinancialYear.on('change', function() {
                    dataTable.column(2).search(this.value).draw();
                });
            }

            if (filterSearch.length) {
                filterSearch.on('keyup', function() {
                    dataTable.search(this.value).draw();
                });
            }

            // Clear filters button
            $('#clearFilters_' + filterPrefix).on('click', function() {
                if (filterDistrict.length) filterDistrict.val('').trigger('change');
                if (filterScheme.length) filterScheme.val('').trigger('change');
                if (filterFinancialYear.length) filterFinancialYear.val('').trigger('change');
                if (filterSearch.length) filterSearch.val('');
                dataTable.search('').columns().search('').draw();
            });
        }
    });
</script>
@endpush

