<?php $__env->startSection('title', config('app.name') . ' - Rejected Cases'); ?>
<?php $__env->startSection('page_title', 'Rejected Cases'); ?>
<?php $__env->startSection('breadcrumb', 'Rejected Cases'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Rejected Beneficiaries</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <?php if($beneficiaries->isEmpty()): ?>
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No rejected beneficiaries found.
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
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">District</label>
                                            <select id="filterDistrict" class="form-control form-control-sm">
                                                <option value="">All Districts</option>
                                                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($district->name); ?>"><?php echo e($district->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Scheme</label>
                                            <select id="filterScheme" class="form-control form-control-sm">
                                                <option value="">All Schemes</option>
                                                <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($scheme->name); ?>"><?php echo e($scheme->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Financial Year</label>
                                            <select id="filterFinancialYear" class="form-control form-control-sm">
                                                <option value="">All Financial Years</option>
                                                <?php $__currentLoopData = $financialYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($fy->name); ?>"><?php echo e($fy->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Search Remarks</label>
                                            <input type="text" id="filterRemarks" class="form-control form-control-sm" placeholder="Search in remarks...">
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
                        <table class="table" id="rejectedTable">
                            <thead>
                                <tr>
                                    <th>CNIC</th>
                                    <th>Name</th>
                                    <th>District</th>
                                    <th>Scheme</th>
                                    <th>Amount</th>
                                    <th>Rejected At</th>
                                    <th>Rejection Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $beneficiaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficiary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($beneficiary->cnic); ?></td>
                                    <td><?php echo e($beneficiary->full_name); ?></td>
                                    <td data-financial-year="<?php echo e($beneficiary->phase->installment->fundAllocation->financialYear->name ?? ''); ?>"><?php echo e($beneficiary->phase->district->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($beneficiary->scheme->name ?? 'N/A'); ?></td>
                                    <td>Rs. <?php echo e(number_format($beneficiary->amount, 2)); ?></td>
                                    <td><?php echo e($beneficiary->rejected_at ? $beneficiary->rejected_at->format('Y-m-d H:i') : 'N/A'); ?></td>
                                    <td data-remarks="<?php echo e($beneficiary->admin_remarks ?? ''); ?>">
                                        <?php if($beneficiary->admin_remarks): ?>
                                            <span class="text-danger" title="<?php echo e($beneficiary->admin_remarks); ?>">
                                                <?php echo e(Str::limit($beneficiary->admin_remarks, 50)); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                <td>
                                    <div class="action_btns d-flex">
                                        <a href="<?php echo e(route('admin-hq.show-rejected-case', $beneficiary)); ?>" class="action_btn mr_10" title="View Details">
                                            <i class="ti-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center">No rejected beneficiaries found</td>
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
        var table = $('#rejectedTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-files"></i> Copy',
                    className: 'btn btn-sm btn-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6], // Exclude Actions column
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
                    filename: 'rejected-beneficiaries-' + new Date().toISOString().split('T')[0],
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
                    filename: 'rejected-beneficiaries-' + new Date().toISOString().split('T')[0],
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
                    filename: 'rejected-beneficiaries-' + new Date().toISOString().split('T')[0],
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
            order: [[5, 'desc']] // Sort by rejected_at descending
        });

        // Financial Year filter function
        var financialYearFilter = function(settings, data, dataIndex) {
            var fyValue = $('#filterFinancialYear').val();
            if (fyValue === '') return true;
            
            var row = table.row(dataIndex).node();
            var rowFY = $(row).find('td:eq(2)').attr('data-financial-year');
            if (!rowFY) return true;
            
            return rowFY === fyValue;
        };

        // Remarks filter function
        var remarksFilter = function(settings, data, dataIndex) {
            var remarksValue = $('#filterRemarks').val().toLowerCase();
            if (remarksValue === '') return true;
            
            var row = table.row(dataIndex).node();
            var rowRemarks = $(row).find('td:eq(6)').attr('data-remarks') || '';
            
            return rowRemarks.toLowerCase().includes(remarksValue);
        };

        // Filter by District (column 2)
        $('#filterDistrict').on('change', function() {
            var value = $(this).val();
            table.column(2).search(value).draw();
        });

        // Filter by Scheme (column 3)
        $('#filterScheme').on('change', function() {
            var value = $(this).val();
            table.column(3).search(value).draw();
        });

        // Filter by Financial Year
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

        // Filter by Remarks (column 6)
        $('#filterRemarks').on('keyup', function() {
            var index = $.fn.dataTable.ext.search.indexOf(remarksFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(remarksFilter);
            }
            
            table.draw();
        });

        // Clear all filters
        $('#clearFilters').on('click', function() {
            var fyIndex = $.fn.dataTable.ext.search.indexOf(financialYearFilter);
            if (fyIndex !== -1) {
                $.fn.dataTable.ext.search.splice(fyIndex, 1);
            }
            
            var remarksIndex = $.fn.dataTable.ext.search.indexOf(remarksFilter);
            if (remarksIndex !== -1) {
                $.fn.dataTable.ext.search.splice(remarksIndex, 1);
            }
            
            $('#filterDistrict').val('');
            $('#filterScheme').val('');
            $('#filterFinancialYear').val('');
            $('#filterRemarks').val('');
            
            table.search('').columns().search('').draw();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/admin-hq/rejected-cases.blade.php ENDPATH**/ ?>