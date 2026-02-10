@extends('layouts.app')

@section('title', config('app.name') . ' - Mohallas')
@section('page_title', 'Mohallas')
@section('breadcrumb', 'Mohallas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Mohallas</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="{{ route('mohallas.create') }}" class="btn btn-primary">
                            <i class="ti-plus"></i> Add Mohalla
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                @if($mohallas->isEmpty())
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No mohallas found.
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
                                            <label class="form-label">Tehsil</label>
                                            <select id="filterTehsil" class="form-control form-control-sm">
                                                <option value="">All Tehsils</option>
                                                @foreach($tehsils as $tehsil)
                                                    <option value="{{ $tehsil->name }}" data-district-id="{{ $tehsil->district_id }}">{{ $tehsil->name }} ({{ $tehsil->district->name ?? '' }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Union Council</label>
                                            <select id="filterUnionCouncil" class="form-control form-control-sm">
                                                <option value="">All Union Councils</option>
                                                @foreach($unionCouncils as $uc)
                                                    <option value="{{ $uc->name }}" data-tehsil-id="{{ $uc->tehsil_id }}">{{ $uc->name }} ({{ $uc->tehsil->name ?? '' }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Village</label>
                                            <select id="filterVillage" class="form-control form-control-sm">
                                                <option value="">All Villages</option>
                                                @foreach($villages as $village)
                                                    <option value="{{ $village->name }}" data-union-council-id="{{ $village->union_council_id }}">{{ $village->name }} ({{ $village->unionCouncil->tehsil->district->name ?? '' }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Status</label>
                                            <select id="filterStatus" class="form-control form-control-sm">
                                                <option value="">All Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8 mb-3">
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
                        <table class="table" id="mohallasTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Village</th>
                                    <th>Union Council</th>
                                    <th>Tehsil</th>
                                    <th>District</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mohallas as $mohalla)
                                <tr>
                                    <td>{{ $mohalla->name }}</td>
                                    <td data-village="{{ $mohalla->village->name ?? '' }}">{{ $mohalla->village->name ?? 'N/A' }}</td>
                                    <td data-union-council="{{ $mohalla->village->unionCouncil->name ?? '' }}">{{ $mohalla->village->unionCouncil->name ?? 'N/A' }}</td>
                                    <td data-tehsil="{{ $mohalla->village->unionCouncil->tehsil->name ?? '' }}">{{ $mohalla->village->unionCouncil->tehsil->name ?? 'N/A' }}</td>
                                    <td data-district="{{ $mohalla->village->unionCouncil->tehsil->district->name ?? '' }}">{{ $mohalla->village->unionCouncil->tehsil->district->name ?? 'N/A' }}</td>
                                    <td data-status="{{ $mohalla->is_active ? 'active' : 'inactive' }}">
                                        @if($mohalla->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action_btns d-flex">
                                            <a href="{{ route('mohallas.show', $mohalla) }}" class="action_btn mr_10" title="View">
                                                <i class="ti-eye"></i>
                                            </a>
                                            <a href="{{ route('mohallas.edit', $mohalla) }}" class="action_btn mr_10" title="Edit">
                                                <i class="ti-pencil"></i>
                                            </a>
                                            <form action="{{ route('mohallas.destroy', $mohalla) }}" method="POST" class="d-inline mr_10" onsubmit="return confirmDelete(event, 'Are you sure you want to delete this mohalla?')">
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
                                    <td colspan="7" class="text-center">No mohallas found</td>
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
        var table = $('#mohallasTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-files"></i> Copy',
                    className: 'btn btn-sm btn-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5],
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
                    filename: 'mohallas-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5],
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
                    filename: 'mohallas-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5],
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
                    filename: 'mohallas-' + new Date().toISOString().split('T')[0],
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5],
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
                        doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*'];
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> Print',
                    className: 'btn btn-sm btn-info',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '9px');
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
            var rowStatus = $(row).find('td:eq(5)').attr('data-status');
            if (!rowStatus) return true;
            return rowStatus === statusValue;
        };

        var districtFilter = function(settings, data, dataIndex) {
            var districtValue = $('#filterDistrict').val();
            if (districtValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowDistrict = $(row).find('td:eq(4)').attr('data-district');
            if (!rowDistrict) return true;
            return rowDistrict === districtValue;
        };

        var tehsilFilter = function(settings, data, dataIndex) {
            var tehsilValue = $('#filterTehsil').val();
            if (tehsilValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowTehsil = $(row).find('td:eq(3)').attr('data-tehsil');
            if (!rowTehsil) return true;
            return rowTehsil === tehsilValue;
        };

        var unionCouncilFilter = function(settings, data, dataIndex) {
            var ucValue = $('#filterUnionCouncil').val();
            if (ucValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowUC = $(row).find('td:eq(2)').attr('data-union-council');
            if (!rowUC) return true;
            return rowUC === ucValue;
        };

        var villageFilter = function(settings, data, dataIndex) {
            var villageValue = $('#filterVillage').val();
            if (villageValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowVillage = $(row).find('td:eq(1)').attr('data-village');
            if (!rowVillage) return true;
            return rowVillage === villageValue;
        };

        // Cascading filters
        $('#filterDistrict').on('change', function() {
            var selectedDistrict = $(this).val();
            // Filter tehsils by matching district name in parentheses
            $('#filterTehsil option').show();
            if (selectedDistrict) {
                $('#filterTehsil option').each(function() {
                    var optionText = $(this).text();
                    var districtMatch = optionText.match(/\(([^)]+)\)/);
                    if (districtMatch && districtMatch[1] !== selectedDistrict) {
                        $(this).hide();
                    }
                });
            }
            $('#filterTehsil').val('').trigger('change');
            
            // Apply filter
            var index = $.fn.dataTable.ext.search.indexOf(districtFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(districtFilter);
            }
            table.draw();
        });

        $('#filterTehsil').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var tehsilId = selectedOption.data('tehsil-id');
            // Filter union councils
            $('#filterUnionCouncil option').show();
            if (tehsilId) {
                $('#filterUnionCouncil option').not('[value=""]').not('[data-tehsil-id="' + tehsilId + '"]').hide();
            }
            $('#filterUnionCouncil').val('').trigger('change');
            
            // Apply filter
            var index = $.fn.dataTable.ext.search.indexOf(tehsilFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(tehsilFilter);
            }
            table.draw();
        });

        $('#filterUnionCouncil').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var ucId = selectedOption.data('union-council-id');
            // Filter villages
            $('#filterVillage option').show();
            if (ucId) {
                $('#filterVillage option').not('[value=""]').not('[data-union-council-id="' + ucId + '"]').hide();
            }
            $('#filterVillage').val('').trigger('change');
            
            // Apply filter
            var index = $.fn.dataTable.ext.search.indexOf(unionCouncilFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(unionCouncilFilter);
            }
            table.draw();
        });

        $('#filterVillage').on('change', function() {
            var index = $.fn.dataTable.ext.search.indexOf(villageFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(villageFilter);
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

        $('#filterSearch').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        $('#clearFilters').on('click', function() {
            var statusIndex = $.fn.dataTable.ext.search.indexOf(statusFilter);
            if (statusIndex !== -1) {
                $.fn.dataTable.ext.search.splice(statusIndex, 1);
            }
            var districtIndex = $.fn.dataTable.ext.search.indexOf(districtFilter);
            if (districtIndex !== -1) {
                $.fn.dataTable.ext.search.splice(districtIndex, 1);
            }
            var tehsilIndex = $.fn.dataTable.ext.search.indexOf(tehsilFilter);
            if (tehsilIndex !== -1) {
                $.fn.dataTable.ext.search.splice(tehsilIndex, 1);
            }
            var ucIndex = $.fn.dataTable.ext.search.indexOf(unionCouncilFilter);
            if (ucIndex !== -1) {
                $.fn.dataTable.ext.search.splice(ucIndex, 1);
            }
            var villageIndex = $.fn.dataTable.ext.search.indexOf(villageFilter);
            if (villageIndex !== -1) {
                $.fn.dataTable.ext.search.splice(villageIndex, 1);
            }
            $('#filterDistrict').val('').trigger('change');
            $('#filterTehsil').val('').trigger('change');
            $('#filterUnionCouncil').val('').trigger('change');
            $('#filterVillage').val('');
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

