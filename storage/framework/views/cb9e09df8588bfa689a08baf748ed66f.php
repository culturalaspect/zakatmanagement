<?php $__env->startSection('title', config('app.name') . ' - Beneficiary Applications'); ?>
<?php $__env->startSection('page_title', 'Beneficiary Applications'); ?>
<?php $__env->startSection('breadcrumb', 'Beneficiary Applications'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Horizontal scrollable DataTable */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    #phasesTable_wrapper {
        overflow-x: auto;
    }
    
    #phasesTable {
        min-width: 100%;
        width: 100% !important;
    }
    
    /* Ensure DataTable scroll container works */
    .dataTables_wrapper .dataTables_scroll {
        overflow-x: auto;
    }
    
    .dataTables_wrapper .dataTables_scrollBody {
        overflow-x: auto !important;
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Active Phases - Add Beneficiaries</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <?php if($phases->isEmpty()): ?>
                    <div class="alert alert-info">
                        <i class="ti-info-alt"></i> No active phases available at the moment.
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
                        <table id="phasesTable" class="table table-hover" style="width:100%; min-width: 1200px;">
                            <thead>
                                <tr>
                                    <th>Phase Name</th>
                                    <th>Financial Year</th>
                                    <th>Installment</th>
                                    <th>District</th>
                                    <th>Scheme</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Beneficiaries</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $phases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr style="cursor: pointer;" onclick="window.location.href='<?php echo e(route('beneficiary-applications.phase', $phase->id)); ?>'">
                                    <td>
                                        <strong><?php echo e($phase->name); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo e($phase->installment->fundAllocation->financialYear->name ?? 'N/A'); ?>

                                    </td>
                                    <td>
                                        Installment #<?php echo e($phase->installment->installment_number ?? 'N/A'); ?>

                                    </td>
                                    <td><?php echo e($phase->district->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($phase->scheme->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($phase->start_date ? $phase->start_date->format('d M Y') : 'N/A'); ?></td>
                                    <td><?php echo e($phase->end_date ? $phase->end_date->format('d M Y') : 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo e(number_format($phase->current_beneficiaries_count)); ?> / <?php echo e(number_format($phase->max_beneficiaries)); ?>

                                        </span>
                                        <?php if($phase->remaining_beneficiaries > 0): ?>
                                            <br><small class="text-success">Remaining: <?php echo e(number_format($phase->remaining_beneficiaries)); ?></small>
                                        <?php else: ?>
                                            <br><small class="text-danger">Limit Reached</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            Rs. <?php echo e(number_format($phase->current_amount, 2)); ?> / Rs. <?php echo e(number_format($phase->max_amount, 2)); ?>

                                        </span>
                                        <?php if($phase->remaining_amount > 0): ?>
                                            <br><small class="text-success">Remaining: Rs. <?php echo e(number_format($phase->remaining_amount, 2)); ?></small>
                                        <?php else: ?>
                                            <br><small class="text-danger">Limit Reached</small>
                                        <?php endif; ?>
                                    </td>
                                    <td data-status="<?php echo e($phase->status); ?>">
                                        <?php if($phase->status == 'open'): ?>
                                            <span class="badge bg-success">Open</span>
                                        <?php elseif($phase->status == 'closed'): ?>
                                            <span class="badge bg-warning">Closed</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e(ucfirst($phase->status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('beneficiary-applications.phase', $phase->id)); ?>" 
                                           class="action_btn mr_10" 
                                           title="Manage Beneficiaries"
                                           onclick="event.stopPropagation();">
                                            <i class="ti-eye"></i> Manage
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

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
        var table = $('#phasesTable').DataTable({
            scrollX: true,
            scrollCollapse: true,
            autoWidth: false,
            fixedColumns: false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ti-files"></i> Copy',
                    className: 'btn btn-sm btn-secondary',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Exclude Actions column
                        format: {
                            body: function(data, row, column, node) {
                                // Remove HTML tags and get text content
                                var div = document.createElement('div');
                                div.innerHTML = data;
                                var text = div.textContent || div.innerText || '';
                                // Clean up extra whitespace
                                return text.trim().replace(/\s+/g, ' ');
                            }
                        }
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="ti-file"></i> CSV',
                    className: 'btn btn-sm btn-secondary',
                    filename: 'beneficiary-applications-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Exclude Actions column
                        format: {
                            body: function(data, row, column, node) {
                                // Remove HTML tags and get text content
                                var div = document.createElement('div');
                                div.innerHTML = data;
                                var text = div.textContent || div.innerText || '';
                                // Clean up extra whitespace
                                return text.trim().replace(/\s+/g, ' ');
                            }
                        }
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="ti-file"></i> Excel',
                    className: 'btn btn-sm btn-success',
                    filename: 'beneficiary-applications-' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Exclude Actions column
                        format: {
                            body: function(data, row, column, node) {
                                // Remove HTML tags and get text content
                                var div = document.createElement('div');
                                div.innerHTML = data;
                                var text = div.textContent || div.innerText || '';
                                // Clean up extra whitespace
                                return text.trim().replace(/\s+/g, ' ');
                            }
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="ti-file"></i> PDF',
                    className: 'btn btn-sm btn-danger',
                    filename: 'beneficiary-applications-' + new Date().toISOString().split('T')[0],
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Exclude Actions column
                        format: {
                            body: function(data, row, column, node) {
                                // Remove HTML tags and get text content
                                var div = document.createElement('div');
                                div.innerHTML = data;
                                var text = div.textContent || div.innerText || '';
                                // Clean up extra whitespace
                                return text.trim().replace(/\s+/g, ' ');
                            }
                        }
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.styles.tableHeader.fontSize = 9;
                        doc.styles.tableHeader.alignment = 'center';
                        doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*', '*'];
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> Print',
                    className: 'btn btn-sm btn-info',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] // Exclude Actions column
                    },
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '10px');
                        $(win.document.body).find('tr:nth-child(odd)').css('background-color', '#f9f9f9');
                    }
                }
            ],
            order: [[5, 'desc']], // Sort by start date descending
            columnDefs: [
                { width: "20%", targets: 0 }, // Phase Name
                { width: "10%", targets: 1 }, // Financial Year
                { width: "8%", targets: 2 },  // Installment
                { width: "10%", targets: 3 }, // District
                { width: "12%", targets: 4 }, // Scheme
                { width: "8%", targets: 5 },  // Start Date
                { width: "8%", targets: 6 },  // End Date
                { width: "10%", targets: 7 }, // Beneficiaries
                { width: "12%", targets: 8 }, // Amount
                { width: "6%", targets: 9 },  // Status
                { width: "10%", targets: 10, orderable: false } // Actions
            ],
            pageLength: 25,
            language: {
                search: "Search phases:",
                lengthMenu: "Show _MENU_ phases per page",
                info: "Showing _START_ to _END_ of _TOTAL_ phases",
                infoEmpty: "No phases available",
                infoFiltered: "(filtered from _TOTAL_ total phases)"
            }
        });

        // Store status filter function reference
        var statusFilter = function(settings, data, dataIndex) {
            var statusValue = $('#filterStatus').val().toLowerCase();
            if (statusValue === '') return true;
            
            var row = table.row(dataIndex).node();
            var rowStatus = $(row).find('td:eq(9)').attr('data-status');
            if (!rowStatus) return true;
            
            return rowStatus.toLowerCase() === statusValue;
        };

        // Filter by District (column 3)
        $('#filterDistrict').on('change', function() {
            var value = $(this).val();
            table.column(3).search(value).draw();
        });

        // Filter by Scheme (column 4)
        $('#filterScheme').on('change', function() {
            var value = $(this).val();
            table.column(4).search(value).draw();
        });

        // Filter by Financial Year (column 1)
        $('#filterFinancialYear').on('change', function() {
            var value = $(this).val();
            table.column(1).search(value).draw();
        });

        // Filter by Status (column 9) - using data attribute
        $('#filterStatus').on('change', function() {
            // Remove existing status filter if any
            var index = $.fn.dataTable.ext.search.indexOf(statusFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            
            // Add status filter if a value is selected
            if ($(this).val() !== '') {
                $.fn.dataTable.ext.search.push(statusFilter);
            }
            
            table.draw();
        });

        // Clear all filters
        $('#clearFilters').on('click', function() {
            // Remove status filter function
            var index = $.fn.dataTable.ext.search.indexOf(statusFilter);
            if (index !== -1) {
                $.fn.dataTable.ext.search.splice(index, 1);
            }
            
            $('#filterDistrict').val('');
            $('#filterScheme').val('');
            $('#filterFinancialYear').val('');
            $('#filterStatus').val('');
            
            table.search('').columns().search('').draw();
        });
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/beneficiaries/applications.blade.php ENDPATH**/ ?>