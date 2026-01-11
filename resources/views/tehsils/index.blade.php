@extends('layouts.app')

@section('title', config('app.name') . ' - Tehsils')
@section('page_title', 'Tehsils')
@section('breadcrumb', 'Tehsils')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Tehsils</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('tehsils.create') }}" class="btn btn-primary">
                            <i class="ti-plus"></i> Add Tehsil
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                @if($tehsils->isEmpty())
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No tehsils found.
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
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">District</label>
                                            <select id="filterDistrict" class="form-control form-control-sm">
                                                <option value="">All Districts</option>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->name }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Status</label>
                                            <select id="filterStatus" class="form-control form-control-sm">
                                                <option value="">All Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Search</label>
                                            <input type="text" id="filterSearch" class="form-control form-control-sm" placeholder="Search by name...">
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
                        <table class="table" id="tehsilsTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>District</th>
                                    <th>Union Councils</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tehsils as $tehsil)
                                <tr>
                                    <td>{{ $tehsil->name }}</td>
                                    <td>{{ $tehsil->district->name ?? 'N/A' }}</td>
                                    <td>{{ $tehsil->unionCouncils->count() ?? 0 }}</td>
                                    <td data-status="{{ $tehsil->is_active ? 'active' : 'inactive' }}">
                                        @if($tehsil->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action_btns d-flex">
                                            <a href="{{ route('tehsils.show', $tehsil) }}" class="action_btn mr_10" title="View">
                                                <i class="ti-eye"></i>
                                            </a>
                                            <a href="{{ route('tehsils.edit', $tehsil) }}" class="action_btn mr_10" title="Edit">
                                                <i class="ti-pencil"></i>
                                            </a>
                                            <form action="{{ route('tehsils.destroy', $tehsil) }}" method="POST" class="d-inline mr_10" onsubmit="return confirmDelete(event, 'Are you sure you want to delete this tehsil?')">
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
                                    <td colspan="5" class="text-center">No tehsils found</td>
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
        var table = $('#tehsilsTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-files"></i> Copy',
                    className: 'btn btn-sm btn-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3],
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
                    filename: 'tehsils-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3],
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
                    filename: 'tehsils-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3],
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
                    filename: 'tehsils-' + new Date().toISOString().split('T')[0],
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3],
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
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader.fontSize = 11;
                        doc.styles.tableHeader.alignment = 'center';
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> Print',
                    className: 'btn btn-sm btn-info',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    },
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '11px');
                        $(win.document.body).find('tr:nth-child(odd)').css('background-color', '#f9f9f9');
                    }
                }
            ],
            order: [[0, 'asc']]
        });

        var statusFilter = function(settings, data, dataIndex) {
            var statusValue = $('#filterStatus').val();
            if (statusValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowStatus = $(row).find('td:eq(3)').attr('data-status');
            if (!rowStatus) return true;
            return rowStatus === statusValue;
        };

        $('#filterDistrict').on('change', function() {
            table.column(1).search($(this).val()).draw();
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

        $('#filterSearch').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        $('#clearFilters').on('click', function() {
            var statusIndex = $.fn.dataTable.ext.search.indexOf(statusFilter);
            if (statusIndex !== -1) {
                $.fn.dataTable.ext.search.splice(statusIndex, 1);
            }
            $('#filterDistrict').val('');
            $('#filterStatus').val('');
            $('#filterSearch').val('');
            table.search('').columns().search('').draw();
        });
    });

    function confirmDelete(event, message) {
        event.preventDefault();
        const form = event.target.closest('form');
        if (confirm(message)) {
            form.submit();
        }
        return false;
    }
</script>
@endpush
@endsection

