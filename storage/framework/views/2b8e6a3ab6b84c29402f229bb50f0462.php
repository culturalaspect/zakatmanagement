

<?php $__env->startSection('title', config('app.name') . ' - Schemes'); ?>
<?php $__env->startSection('page_title', 'Schemes'); ?>
<?php $__env->startSection('breadcrumb', 'Schemes'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Schemes</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="<?php echo e(route('schemes.create')); ?>" class="btn btn-primary">
                            <i class="ti-plus"></i> Add Scheme
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <?php if($schemes->isEmpty()): ?>
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No schemes found.
                    </div>
                <?php else: ?>
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
                                            <label class="form-label">Status</label>
                                            <select id="filterStatus" class="form-control form-control-sm">
                                                <option value="">All Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Age Restriction</label>
                                            <select id="filterAgeRestriction" class="form-control form-control-sm">
                                                <option value="">All</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
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
                        <table class="table" id="schemesTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Percentage</th>
                                    <th>Age Restriction</th>
                                    <th>Categories</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($scheme->name); ?></td>
                                    <td><?php echo e(number_format($scheme->percentage, 2)); ?>%</td>
                                    <td data-age-restriction="<?php echo e($scheme->has_age_restriction ? 'yes' : 'no'); ?>">
                                        <?php if($scheme->has_age_restriction): ?>
                                            <span class="badge bg-info">Yes (<?php echo e($scheme->minimum_age); ?>+)</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($scheme->categories->count()); ?> category(ies)</td>
                                    <td data-status="<?php echo e($scheme->is_active ? 'active' : 'inactive'); ?>">
                                        <?php if($scheme->is_active): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                <td>
                                    <div class="action_btns d-flex">
                                        <a href="<?php echo e(route('schemes.show', $scheme)); ?>" class="action_btn mr_10" title="View">
                                            <i class="ti-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('schemes.edit', $scheme)); ?>" class="action_btn mr_10" title="Edit">
                                            <i class="ti-pencil"></i>
                                        </a>
                                        <form action="<?php echo e(route('schemes.destroy', $scheme)); ?>" method="POST" class="d-inline mr_10" onsubmit="return confirmDelete(event, 'Are you sure you want to delete this scheme?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="action_btn" title="Delete">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center">No schemes found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- DataTables Buttons JS -->
<script src="<?php echo e(asset('assets/vendors/datatable/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendors/datatable/js/vfs_fonts.js')); ?>"></script>

<script>
    $(document).ready(function() {
        var table = $('#schemesTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-files"></i> Copy',
                    className: 'btn btn-sm btn-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
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
                    filename: 'schemes-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
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
                    filename: 'schemes-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
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
                    filename: 'schemes-' + new Date().toISOString().split('T')[0],
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
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
                        doc.defaultStyle.fontSize = 9;
                        doc.styles.tableHeader.fontSize = 10;
                        doc.styles.tableHeader.alignment = 'center';
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> Print',
                    className: 'btn btn-sm btn-info',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '10px');
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
            var rowStatus = $(row).find('td:eq(4)').attr('data-status');
            if (!rowStatus) return true;
            return rowStatus === statusValue;
        };

        var ageRestrictionFilter = function(settings, data, dataIndex) {
            var ageValue = $('#filterAgeRestriction').val();
            if (ageValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowAge = $(row).find('td:eq(2)').attr('data-age-restriction');
            if (!rowAge) return true;
            return rowAge === ageValue;
        };

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

        $('#filterAgeRestriction').on('change', function() {
            var index = $.fn.dataTable.ext.search.indexOf(ageRestrictionFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(ageRestrictionFilter);
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
            var ageIndex = $.fn.dataTable.ext.search.indexOf(ageRestrictionFilter);
            if (ageIndex !== -1) {
                $.fn.dataTable.ext.search.splice(ageIndex, 1);
            }
            $('#filterStatus').val('');
            $('#filterAgeRestriction').val('');
            $('#filterSearch').val('');
            table.search('').columns().search('').draw();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/schemes/index.blade.php ENDPATH**/ ?>