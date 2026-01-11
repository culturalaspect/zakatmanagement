

<?php $__env->startSection('title', config('app.name') . ' - Fund Allocations'); ?>
<?php $__env->startSection('page_title', 'Fund Allocations'); ?>
<?php $__env->startSection('breadcrumb', 'Fund Allocations'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Fund Allocations</h3>
                    </div>
                    <div class="header_more_tool">
                        <a href="<?php echo e(route('fund-allocations.create')); ?>" class="btn btn-primary">
                            <i class="ti-plus"></i> Add Fund Allocation
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <?php if($allocations->isEmpty()): ?>
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No fund allocations found.
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
                                            <label class="form-label">Financial Year</label>
                                            <select id="filterFinancialYear" class="form-control form-control-sm">
                                                <option value="">All Financial Years</option>
                                                <?php $__currentLoopData = $financialYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($fy->name); ?>"><?php echo e($fy->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Status</label>
                                            <select id="filterStatus" class="form-control form-control-sm">
                                                <option value="">All Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="allocated">Allocated</option>
                                                <option value="disbursing">Disbursing</option>
                                                <option value="completed">Completed</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Search</label>
                                            <input type="text" id="filterSearch" class="form-control form-control-sm" placeholder="Search by source...">
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
                        <table class="table" id="allocationsTable">
                            <thead>
                                <tr>
                                    <th>Financial Year</th>
                                    <th>Total Amount</th>
                                    <th>Date</th>
                                    <th>Source</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($allocation->financialYear ? $allocation->financialYear->name : 'N/A'); ?></td>
                                    <td>Rs. <?php echo e(number_format($allocation->total_amount, 2)); ?></td>
                                    <td><?php echo e($allocation->date->format('Y-m-d')); ?></td>
                                    <td><?php echo e($allocation->source ?? 'N/A'); ?></td>
                                    <td data-status="<?php echo e($allocation->status); ?>">
                                        <?php if($allocation->status == 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif($allocation->status == 'allocated'): ?>
                                            <span class="badge bg-info">Allocated</span>
                                        <?php elseif($allocation->status == 'disbursing'): ?>
                                            <span class="badge bg-primary">Disbursing</span>
                                        <?php elseif($allocation->status == 'completed'): ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                <td>
                                    <div class="action_btns d-flex">
                                        <a href="<?php echo e(route('fund-allocations.show', $allocation)); ?>" class="action_btn mr_10" title="View">
                                            <i class="ti-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('fund-allocations.edit', $allocation)); ?>" class="action_btn mr_10" title="Edit">
                                            <i class="ti-pencil"></i>
                                        </a>
                                        <form action="<?php echo e(route('fund-allocations.destroy', $allocation)); ?>" method="POST" class="d-inline mr_10" onsubmit="return confirmDelete(event, 'Are you sure you want to delete this fund allocation?')">
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
                                <td colspan="6" class="text-center">No fund allocations found</td>
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
        var table = $('#allocationsTable').DataTable({
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
                    filename: 'fund-allocations-' + new Date().toISOString().split('T')[0],
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
                    filename: 'fund-allocations-' + new Date().toISOString().split('T')[0],
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
                    filename: 'fund-allocations-' + new Date().toISOString().split('T')[0],
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
            order: [[0, 'desc'], [2, 'desc']]
        });

        var statusFilter = function(settings, data, dataIndex) {
            var statusValue = $('#filterStatus').val();
            if (statusValue === '') return true;
            var row = table.row(dataIndex).node();
            var rowStatus = $(row).find('td:eq(4)').attr('data-status');
            if (!rowStatus) return true;
            return rowStatus === statusValue;
        };

        $('#filterFinancialYear').on('change', function() {
            table.column(0).search($(this).val()).draw();
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
            table.column(3).search($(this).val()).draw();
        });

        $('#clearFilters').on('click', function() {
            var statusIndex = $.fn.dataTable.ext.search.indexOf(statusFilter);
            if (statusIndex !== -1) {
                $.fn.dataTable.ext.search.splice(statusIndex, 1);
            }
            $('#filterFinancialYear').val('');
            $('#filterStatus').val('');
            $('#filterSearch').val('');
            table.search('').columns().search('').draw();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/fund-allocations/index.blade.php ENDPATH**/ ?>