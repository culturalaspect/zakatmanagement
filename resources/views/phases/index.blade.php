@extends('layouts.app')

@section('title', config('app.name') . ' - Phases')
@section('page_title', 'Phases')
@section('breadcrumb', 'Phases')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Phases</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('phases.create') }}" class="btn btn-primary">
                            <i class="ti-plus"></i> Add Phase
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                @if($phases->isEmpty())
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No phases found.
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
                                            <label class="form-label">Status</label>
                                            <select id="filterStatus" class="form-control form-control-sm">
                                                <option value="">All Status</option>
                                                <option value="open">Open</option>
                                                <option value="closed">Closed</option>
                                            </select>
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

                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table" id="phasesTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Phase Name</th>
                                    <th>Phase Number</th>
                                    <th>Installment</th>
                                    <th>District</th>
                                    <th>Max Beneficiaries</th>
                                    <th>Max Amount (Rs.)</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($phases as $phase)
                                <tr>
                                    <td>{{ $phase->name }}</td>
                                    <td>{{ $phase->phase_number }}</td>
                                    <td data-financial-year="{{ $phase->installment->fundAllocation->financialYear->name ?? '' }}" data-scheme="{{ $phase->scheme->name ?? '' }}">
                                        @if($phase->installment)
                                            Installment {{ $phase->installment->installment_number }} - {{ $phase->installment->fundAllocation->financialYear->name ?? 'N/A' }}
                                            <br><small class="text-muted">Rs. {{ number_format($phase->installment->installment_amount, 2) }}</small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $phase->district->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($phase->max_beneficiaries) }}</td>
                                    <td>{{ number_format($phase->max_amount, 2) }}</td>
                                    <td>{{ $phase->start_date ? $phase->start_date->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $phase->end_date ? $phase->end_date->format('Y-m-d') : 'N/A' }}</td>
                                    <td data-status="{{ $phase->status }}">
                                        @if($phase->status == 'open')
                                            <span class="badge bg-success">Open</span>
                                        @elseif($phase->status == 'closed')
                                            <span class="badge bg-warning">Closed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($phase->status) }}</span>
                                        @endif
                                    </td>
                                <td>
                                    <div class="action_btns d-flex">
                                        <a href="{{ route('phases.show', $phase) }}" class="action_btn mr_10" title="View">
                                            <i class="ti-eye"></i>
                                        </a>
                                        <a href="{{ route('phases.edit', $phase) }}" class="action_btn mr_10" title="Edit">
                                            <i class="ti-pencil"></i>
                                        </a>
                                        <form action="{{ route('phases.destroy', $phase) }}" method="POST" class="d-inline mr_10" onsubmit="return confirmDelete(event, 'Are you sure you want to delete this phase?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action_btn" title="Delete">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No phases found</td>
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
        var table = $('#phasesTable').DataTable({
            responsive: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-files"></i> Copy',
                    className: 'btn btn-sm btn-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
                    filename: 'phases-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
                    filename: 'phases-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
                    filename: 'phases-' + new Date().toISOString().split('T')[0],
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
                        doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*'];
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> Print',
                    className: 'btn btn-sm btn-info',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '9px');
                        $(win.document.body).find('tr:nth-child(odd)').css('background-color', '#f9f9f9');
                    }
                }
            ],
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: 0 },
                { width: "8%", targets: 1 },
                { width: "15%", targets: 2 },
                { width: "12%", targets: 3 },
                { width: "10%", targets: 4 },
                { width: "12%", targets: 5 },
                { width: "10%", targets: 6 },
                { width: "10%", targets: 7 },
                { width: "8%", targets: 8 },
                { width: "10%", targets: 9, orderable: false }
            ],
            order: [[1, 'asc']],
            pageLength: 15,
            lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]]
        });

        var financialYearFilter = function(settings, data, dataIndex) {
            var fyValue = $('#filterFinancialYear').val();
            if (fyValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowFY = $(row).find('td:eq(2)').attr('data-financial-year');
            if (!rowFY) return true;
            return rowFY === fyValue;
        };

        var schemeFilter = function(settings, data, dataIndex) {
            var schemeValue = $('#filterScheme').val();
            if (schemeValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowScheme = $(row).find('td:eq(2)').attr('data-scheme');
            if (!rowScheme) return true;
            return rowScheme === schemeValue;
        };

        var statusFilter = function(settings, data, dataIndex) {
            var statusValue = $('#filterStatus').val();
            if (statusValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowStatus = $(row).find('td:eq(8)').attr('data-status');
            if (!rowStatus) return true;
            return rowStatus === statusValue;
        };

        $('#filterDistrict').on('change', function() {
            table.column(3).search($(this).val()).draw();
        });

        $('#filterScheme').on('change', function() {
            var index = $.fn.dataTable.ext.search.indexOf(schemeFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(schemeFilter);
            }
            table.draw();
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

        $('#filterStatus').on('change', function() {
            var index = $.fn.dataTable.ext.search.indexOf(statusFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(statusFilter);
            }
            table.draw();
        });

        $('#clearFilters').on('click', function() {
            var fyIndex = $.fn.dataTable.ext.search.indexOf(financialYearFilter);
            if (fyIndex !== -1) {
                $.fn.dataTable.ext.search.splice(fyIndex, 1);
            }
            var schemeIndex = $.fn.dataTable.ext.search.indexOf(schemeFilter);
            if (schemeIndex !== -1) {
                $.fn.dataTable.ext.search.splice(schemeIndex, 1);
            }
            var statusIndex = $.fn.dataTable.ext.search.indexOf(statusFilter);
            if (statusIndex !== -1) {
                $.fn.dataTable.ext.search.splice(statusIndex, 1);
            }
            $('#filterDistrict').val('');
            $('#filterScheme').val('');
            $('#filterFinancialYear').val('');
            $('#filterStatus').val('');
            table.search('').columns().search('').draw();
        });
    });

    function confirmDelete(event, message) {
        event.preventDefault();
        const form = event.target.closest('form');
        
        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#000000',
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                cancelButton: 'btn btn-dark',
                confirmButton: 'btn btn-primary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        
        return false;
    }
</script>
@endpush
@endsection

