@extends('layouts.app')

@section('title', config('app.name') . ' - Pending Approvals')
@section('page_title', 'Pending Approvals')
@section('breadcrumb', 'Pending Approvals')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Pending Beneficiary Approvals</h3>
                    </div>
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
                </div>
            </div>
            <div class="white_card_body">
                @if($beneficiaries->isEmpty())
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No pending approvals found.
                    </div>
                @else
                    <!-- Filters Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="ti-filter"></i> Filters
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">District</label>
                                            <select id="filterDistrict" class="form-control form-control-sm">
                                                <option value="">All Districts</option>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->name }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Scheme</label>
                                            <select id="filterScheme" class="form-control form-control-sm">
                                                <option value="">All Schemes</option>
                                                @foreach($schemes as $scheme)
                                                    <option value="{{ $scheme->name }}">{{ $scheme->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Financial Year</label>
                                            <select id="filterFinancialYear" class="form-control form-control-sm">
                                                <option value="">All Financial Years</option>
                                                @foreach($financialYears as $fy)
                                                    <option value="{{ $fy->name }}">{{ $fy->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Submitted By</label>
                                            <input type="text" id="filterSubmittedBy" class="form-control form-control-sm" placeholder="Search by name...">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" id="clearFilters" class="btn btn-sm btn-secondary">
                                                <i class="ti-close"></i> Clear Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="pendingTable">
                            <thead>
                                <tr>
                                    <th>CNIC</th>
                                    <th>Name</th>
                                    <th>District</th>
                                    <th>Scheme</th>
                                    <th>Amount</th>
                                    <th>Submitted By</th>
                                    <th>Submitted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($beneficiaries as $beneficiary)
                                <tr>
                                    <td>{{ $beneficiary->cnic }}</td>
                                    <td>{{ $beneficiary->full_name }}</td>
                                    <td data-financial-year="{{ $beneficiary->phase->installment->fundAllocation->financialYear->name ?? '' }}">{{ $beneficiary->phase->district->name ?? 'N/A' }}</td>
                                    <td>{{ $beneficiary->scheme->name ?? 'N/A' }}</td>
                                    <td>Rs. {{ number_format($beneficiary->amount, 2) }}</td>
                                    <td>{{ $beneficiary->submittedBy->name ?? 'N/A' }}</td>
                                    <td>{{ $beneficiary->submitted_at ? $beneficiary->submitted_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td>
                                    <div class="action_btns d-flex">
                                        <a href="{{ route('beneficiaries.show', $beneficiary) }}" class="action_btn mr_10" title="View & Approve/Reject">
                                            <i class="ti-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No pending approvals</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
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
        var table = $('#pendingTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-files"></i> Copy',
                    className: 'btn btn-sm btn-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6],
                        format: {
                            body: function(data, row, column, node) {
                                var div = document.createElement('div');
                                div.innerHTML = data;
                                var text = div.textContent || div.innerText || '';
                                return text.trim().replace(/\s+/g, ' ');
                            }
                        }
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="ti-file"></i> CSV',
                    className: 'btn btn-sm btn-secondary',
                    filename: 'pending-approvals-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6],
                        format: {
                            body: function(data, row, column, node) {
                                var div = document.createElement('div');
                                div.innerHTML = data;
                                var text = div.textContent || div.innerText || '';
                                return text.trim().replace(/\s+/g, ' ');
                            }
                        }
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="ti-file"></i> Excel',
                    className: 'btn btn-sm btn-success',
                    filename: 'pending-approvals-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6],
                        format: {
                            body: function(data, row, column, node) {
                                var div = document.createElement('div');
                                div.innerHTML = data;
                                var text = div.textContent || div.innerText || '';
                                return text.trim().replace(/\s+/g, ' ');
                            }
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="ti-file"></i> PDF',
                    className: 'btn btn-sm btn-danger',
                    filename: 'pending-approvals-' + new Date().toISOString().split('T')[0],
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6],
                        format: {
                            body: function(data, row, column, node) {
                                var div = document.createElement('div');
                                div.innerHTML = data;
                                var text = div.textContent || div.innerText || '';
                                return text.trim().replace(/\s+/g, ' ');
                            }
                        }
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.styles.tableHeader.fontSize = 9;
                        doc.styles.tableHeader.alignment = 'center';
                        doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*'];
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> Print',
                    className: 'btn btn-sm btn-info',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '10px');
                        $(win.document.body).find('tr:nth-child(odd)').css('background-color', '#f9f9f9');
                    }
                }
            ],
            order: [[6, 'asc']]
        });

        var financialYearFilter = function(settings, data, dataIndex) {
            var fyValue = $('#filterFinancialYear').val();
            if (fyValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowFY = $(row).find('td:eq(2)').attr('data-financial-year');
            if (!rowFY) return true;
            return rowFY === fyValue;
        };

        $('#filterDistrict').on('change', function() {
            table.column(2).search($(this).val()).draw();
        });

        $('#filterScheme').on('change', function() {
            table.column(3).search($(this).val()).draw();
        });

        $('#filterFinancialYear').on('change', function() {
            var index = $.fn.dataTable.ext.search.indexOf(financialYearFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(financialYearFilter);
            }
            table.draw();
        });

        $('#filterSubmittedBy').on('keyup', function() {
            table.column(5).search($(this).val()).draw();
        });

        $('#clearFilters').on('click', function() {
            var fyIndex = $.fn.dataTable.ext.search.indexOf(financialYearFilter);
            if (fyIndex !== -1) {
                $.fn.dataTable.ext.search.splice(fyIndex, 1);
            }
            $('#filterDistrict').val('');
            $('#filterScheme').val('');
            $('#filterFinancialYear').val('');
            $('#filterSubmittedBy').val('');
            table.search('').columns().search('').draw();
        });
    });
</script>
@endpush
@endsection

