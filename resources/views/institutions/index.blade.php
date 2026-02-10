@extends('layouts.app')

@section('title', config('app.name') . ' - Institutions')
@section('page_title', 'Institutions')
@section('breadcrumb', 'Institutions')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Institutions</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('institutions.create') }}" class="btn btn-primary">
                            <i class="ti-plus"></i> Add Institution
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti-check"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($institutions->isEmpty())
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No institutions found.
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
                                            <label class="form-label">Type</label>
                                            <select id="filterType" class="form-control form-control-sm">
                                                <option value="">All Types</option>
                                                @foreach($types as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                            <label class="form-label">Status</label>
                                            <select id="filterStatus" class="form-control form-control-sm">
                                                <option value="">All Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Search</label>
                                            <input type="text" id="filterSearch" class="form-control form-control-sm" placeholder="Search by name or code...">
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
                        <table class="table" id="institutionsTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>District</th>
                                    <th>Principal/Director</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($institutions as $institution)
                                <tr>
                                    <td><strong>{{ $institution->code ?? 'N/A' }}</strong></td>
                                    <td>{{ $institution->name }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $institution->type_label }}</span>
                                    </td>
                                    <td>{{ $institution->district->name ?? 'N/A' }}</td>
                                    <td>{{ $institution->principal_director_name ?? 'N/A' }}</td>
                                    <td>{{ $institution->phone ?? 'N/A' }}</td>
                                    <td>{{ $institution->email ?? 'N/A' }}</td>
                                    <td data-status="{{ $institution->is_active ? 'active' : 'inactive' }}">
                                        @if($institution->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action_btns d-flex">
                                            <a href="{{ route('institutions.show', $institution) }}" class="action_btn mr_10" title="View">
                                                <i class="ti-eye"></i>
                                            </a>
                                            <a href="{{ route('institutions.edit', $institution) }}" class="action_btn mr_10" title="Edit">
                                                <i class="ti-pencil"></i>
                                            </a>
                                            <button type="button" class="action_btn deleteInstitutionBtn" data-institution-id="{{ $institution->id }}" data-institution-name="{{ $institution->name }}" title="Delete">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No institutions found.</td>
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
        var table = $('#institutionsTable').DataTable({
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-files"></i> Copy',
                    className: 'btn btn-sm btn-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="ti-file"></i> CSV',
                    className: 'btn btn-sm btn-secondary',
                    filename: 'institutions-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="ti-file"></i> Excel',
                    className: 'btn btn-sm btn-success',
                    filename: 'institutions-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="ti-file"></i> PDF',
                    className: 'btn btn-sm btn-danger',
                    filename: 'institutions-' + new Date().toISOString().split('T')[0],
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 7;
                        doc.styles.tableHeader.fontSize = 8;
                        doc.styles.tableHeader.alignment = 'center';
                        doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*'];
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> Print',
                    className: 'btn btn-sm btn-info',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '9px');
                        $(win.document.body).find('tr:nth-child(odd)').css('background-color', '#f9f9f9');
                    }
                }
            ],
            order: [[1, 'asc']],
            autoWidth: false,
            columnDefs: [
                { width: "100px", targets: 0 }, // Code
                { width: "200px", targets: 1 }, // Name
                { width: "120px", targets: 2 }, // Type
                { width: "150px", targets: 3 }, // District
                { width: "150px", targets: 4 }, // Principal/Director
                { width: "130px", targets: 5 }, // Phone
                { width: "180px", targets: 6 }, // Email
                { width: "100px", targets: 7 }, // Status
                { width: "150px", targets: 8 }  // Actions
            ]
        });

        // Apply filters
        $('#filterType').on('change', function() {
            table.column(2).search(this.value).draw();
        });

        $('#filterDistrict').on('change', function() {
            table.column(3).search(this.value).draw();
        });

        $('#filterStatus').on('change', function() {
            const value = this.value.toLowerCase();
            if (value === 'active') {
                table.column(7).search('Active').draw();
            } else if (value === 'inactive') {
                table.column(7).search('Inactive').draw();
            } else {
                table.column(7).search('').draw();
            }
        });

        $('#filterSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#clearFilters').on('click', function() {
            $('#filterType').val('');
            $('#filterDistrict').val('');
            $('#filterStatus').val('');
            $('#filterSearch').val('');
            table.search('').columns().search('').draw();
        });

        // Delete Institution Button Click
        $(document).on('click', '.deleteInstitutionBtn', function() {
            const institutionId = $(this).data('institution-id');
            const institutionName = $(this).data('institution-name');
            
            Swal.fire({
                title: 'Delete Institution',
                text: `Are you sure you want to delete institution "${institutionName}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#567AED',
                cancelButtonColor: '#000000'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': `/institutions/${institutionId}`
                    });
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
