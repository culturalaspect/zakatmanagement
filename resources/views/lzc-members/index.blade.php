@extends('layouts.app')

@section('title', config('app.name') . ' - LZC Members')
@section('page_title', 'LZC Members')
@section('breadcrumb', 'LZC Members')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">LZC Members</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('lzc-members.create') }}" class="btn btn-primary">
                            <i class="ti-plus"></i> Add Member
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                @if($members->isEmpty())
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No members found.
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
                                            <label class="form-label">Committee</label>
                                            <select id="filterCommittee" class="form-control form-control-sm">
                                                <option value="">All Committees</option>
                                                @foreach($committees as $committee)
                                                    <option value="{{ $committee->name }}">{{ $committee->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Verification Status</label>
                                            <select id="filterVerificationStatus" class="form-control form-control-sm">
                                                <option value="">All Status</option>
                                                <option value="verified">Verified</option>
                                                <option value="pending">Pending</option>
                                                <option value="rejected">Rejected</option>
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
                        <table class="table" id="membersTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>CNIC</th>
                                    <th>Full Name</th>
                                    <th>Committee</th>
                                    <th>Mobile Number</th>
                                    <th>Gender</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Verification Status</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                <tr>
                                    <td><strong>{{ $member->cnic ?? 'N/A' }}</strong></td>
                                    <td>{{ $member->full_name }}</td>
                                    <td data-district="{{ $member->localZakatCommittee->district->name ?? '' }}">
                                        @if($member->localZakatCommittee)
                                            {{ $member->localZakatCommittee->name }}<br>
                                            <small class="text-muted">{{ $member->localZakatCommittee->code ?? '' }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->mobile_number ?? 'N/A' }}</td>
                                    <td>
                                        @if($member->gender == 'male')
                                            <span class="badge bg-primary">Male</span>
                                        @elseif($member->gender == 'female')
                                            <span class="badge bg-info">Female</span>
                                        @else
                                            <span class="badge bg-secondary">Other</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->start_date ? \Carbon\Carbon::parse($member->start_date)->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        @if($member->end_date)
                                            {{ \Carbon\Carbon::parse($member->end_date)->format('d M Y') }}
                                        @else
                                            <span class="text-muted">Ongoing</span>
                                        @endif
                                    </td>
                                    <td data-verification-status="{{ $member->verification_status }}">
                                        @if($member->verification_status == 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @elseif($member->verification_status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td data-status="{{ $member->is_active ? 'active' : 'inactive' }}">
                                        @if($member->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                <td>
                                    <div class="action_btns d-flex">
                                        <a href="{{ route('lzc-members.show', $member) }}" class="action_btn mr_10" title="View">
                                            <i class="ti-eye"></i>
                                        </a>
                                        @if($member->verification_status == 'pending')
                                        <a href="{{ route('lzc-members.verify', $member) }}" class="action_btn mr_10" title="Verify" style="color: #ffc107;">
                                            <i class="ti-check-box"></i>
                                        </a>
                                        @endif
                                        @if($member->verification_status != 'verified')
                                        <a href="{{ route('lzc-members.edit', $member) }}" class="action_btn mr_10" title="Edit">
                                            <i class="ti-pencil"></i>
                                        </a>
                                        @else
                                        <button type="button" class="action_btn mr_10" disabled title="Verified members cannot be edited" style="opacity: 0.5; cursor: not-allowed;">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        @endif
                                        <button type="button" class="action_btn deleteMemberBtn" data-member-id="{{ $member->id }}" data-member-name="{{ $member->full_name }}" title="Delete">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No members found.</td>
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
        var table = $('#membersTable').DataTable({
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
                    filename: 'lzc-members-' + new Date().toISOString().split('T')[0],
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
                    filename: 'lzc-members-' + new Date().toISOString().split('T')[0],
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
                    filename: 'lzc-members-' + new Date().toISOString().split('T')[0],
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
                        doc.defaultStyle.fontSize = 7;
                        doc.styles.tableHeader.fontSize = 8;
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
            order: [[1, 'asc']],
            autoWidth: false,
            columnDefs: [
                { width: "130px", targets: 0 }, // CNIC
                { width: "180px", targets: 1 }, // Full Name
                { width: "200px", targets: 2 }, // Committee
                { width: "130px", targets: 3 }, // Mobile Number
                { width: "100px", targets: 4 }, // Gender
                { width: "120px", targets: 5 }, // Start Date
                { width: "120px", targets: 6 }, // End Date
                { width: "130px", targets: 7 }, // Verification Status
                { width: "100px", targets: 8 }, // Status
                { width: "180px", targets: 9 }  // Actions
            ]
        });

        var districtFilter = function(settings, data, dataIndex) {
            var districtValue = $('#filterDistrict').val();
            if (districtValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowDistrict = $(row).find('td:eq(2)').attr('data-district');
            if (!rowDistrict) return true;
            return rowDistrict === districtValue;
        };

        var verificationStatusFilter = function(settings, data, dataIndex) {
            var statusValue = $('#filterVerificationStatus').val();
            if (statusValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowStatus = $(row).find('td:eq(7)').attr('data-verification-status');
            if (!rowStatus) return true;
            return rowStatus === statusValue;
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
            var index = $.fn.dataTable.ext.search.indexOf(districtFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(districtFilter);
            }
            table.draw();
        });

        $('#filterCommittee').on('change', function() {
            table.column(2).search($(this).val()).draw();
        });

        $('#filterVerificationStatus').on('change', function() {
            var index = $.fn.dataTable.ext.search.indexOf(verificationStatusFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(verificationStatusFilter);
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
            var districtIndex = $.fn.dataTable.ext.search.indexOf(districtFilter);
            if (districtIndex !== -1) {
                $.fn.dataTable.ext.search.splice(districtIndex, 1);
            }
            var verificationIndex = $.fn.dataTable.ext.search.indexOf(verificationStatusFilter);
            if (verificationIndex !== -1) {
                $.fn.dataTable.ext.search.splice(verificationIndex, 1);
            }
            var statusIndex = $.fn.dataTable.ext.search.indexOf(statusFilter);
            if (statusIndex !== -1) {
                $.fn.dataTable.ext.search.splice(statusIndex, 1);
            }
            $('#filterDistrict').val('');
            $('#filterCommittee').val('');
            $('#filterVerificationStatus').val('');
            $('#filterStatus').val('');
            table.search('').columns().search('').draw();
        });
        
        // Delete Member Button Click
        $(document).on('click', '.deleteMemberBtn', function() {
            const memberId = $(this).data('member-id');
            const memberName = $(this).data('member-name');
            
            Swal.fire({
                title: 'Delete Member',
                text: `Are you sure you want to delete member "${memberName}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#567AED',
                cancelButtonColor: '#000000'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': `/lzc-members/${memberId}`
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

