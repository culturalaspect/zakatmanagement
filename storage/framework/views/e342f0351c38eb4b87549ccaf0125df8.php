

<?php $__env->startSection('title', config('app.name') . ' - Upload JazzCash Response'); ?>
<?php $__env->startSection('page_title', 'Upload JazzCash Response'); ?>
<?php $__env->startSection('breadcrumb', 'Upload JazzCash Response'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Ensure horizontal scrolling for results table */
    #resultsTable_wrapper {
        overflow-x: auto;
        width: 100%;
    }
    
    #resultsTable {
        min-width: 1200px;
    }
    
    .dataTables_scrollHeadInner,
    .dataTables_scrollBody {
        width: 100% !important;
    }
    
    .dataTables_scrollHeadInner table,
    .dataTables_scrollBody table {
        width: 100% !important;
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
                        <h3 class="m-0">Upload JazzCash Disbursement Response</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>


                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('jazzcash-response.upload')); ?>" method="POST" enctype="multipart/form-data" id="uploadForm">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Financial Year <span class="text-danger">*</span></label>
                            <select name="financial_year_id" id="financial_year_id" class="form-control" required>
                                <option value="">Select Financial Year</option>
                                <?php $__currentLoopData = $financialYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($fy->id); ?>" <?php echo e(old('financial_year_id') == $fy->id ? 'selected' : ''); ?>>
                                        <?php echo e($fy->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['financial_year_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Installment Number <span class="text-danger">*</span></label>
                            <select name="installment_number" id="installment_number" class="form-control" required>
                                <option value="">Select Financial Year first</option>
                            </select>
                            <?php $__errorArgs = ['installment_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <strong><i class="ti-info-alt"></i> Instructions:</strong>
                                <ol class="mb-0 mt-2">
                                    <li>Click "Download Template" to get an empty Excel template with headers</li>
                                    <li>Fill in all beneficiary data (Name, CNIC, Mobile, LZC Name, Amount, etc.) in the template</li>
                                    <li>Fill in the payment details (CHARGES, TOTAL, STATUS, REASON, TID) after disbursement</li>
                                    <li>Select Financial Year and Installment Number, then upload the completed template file</li>
                                </ol>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Excel File <span class="text-danger">*</span></label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls" required>
                            <small class="form-text text-muted">Upload the completed JazzCash response Excel file (.xlsx or .xls format, max 10MB). Please use the template file.</small>
                            <?php $__errorArgs = ['excel_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="ti-upload"></i> Upload and Process
                            </button>
                            <button type="button" class="btn btn-success" id="downloadTemplateBtn">
                                <i class="ti-download"></i> Download Template
                            </button>
                            <a href="<?php echo e(route('jazzcash-response.index')); ?>" class="btn btn-secondary">
                                <i class="ti-close"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>

                <?php if(session('allRecords') && count(session('allRecords')) > 0): ?>
                    <div class="mt-4">
                        <h5>Processing Results - All Records</h5>
                        
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
                                                <label class="form-label">Processing Status</label>
                                                <select id="filterProcessingStatus" class="form-control form-control-sm">
                                                    <option value="">All Statuses</option>
                                                    <option value="Success">Success</option>
                                                    <option value="Failed">Failed</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">JazzCash Status</label>
                                                <select id="filterJazzCashStatus" class="form-control form-control-sm">
                                                    <option value="">All Statuses</option>
                                                    <option value="SUCCESS">SUCCESS</option>
                                                    <option value="FAILED">FAILED</option>
                                                    <option value="PENDING">PENDING</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">LZC Name</label>
                                                <select id="filterLZC" class="form-control form-control-sm">
                                                    <option value="">All LZCs</option>
                                                    <?php
                                                        $uniqueLZCs = collect(session('allRecords'))->pluck('lzc_name')->filter()->unique()->sort()->values();
                                                    ?>
                                                    <?php $__currentLoopData = $uniqueLZCs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lzc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($lzc); ?>"><?php echo e($lzc); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Search</label>
                                                <input type="text" id="filterSearch" class="form-control form-control-sm" placeholder="Search by CNIC, Name...">
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
                        
                        <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                            <table class="table table-bordered table-striped" id="resultsTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>S No</th>
                                        <th>Name</th>
                                        <th>Father/Husband Name</th>
                                        <th>CNIC</th>
                                        <th>Mobile</th>
                                        <th>LZC Name</th>
                                        <th>Amount</th>
                                        <th>Charges</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>TID</th>
                                        <th>Processing Status</th>
                                        <th>Failure Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = session('allRecords'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="<?php echo e($record['success'] ? 'table-success' : 'table-danger'); ?>">
                                            <td><?php echo e($record['s_no']); ?></td>
                                            <td><?php echo e($record['name']); ?></td>
                                            <td><?php echo e($record['father_husband_name']); ?></td>
                                            <td><?php echo e($record['cnic']); ?></td>
                                            <td><?php echo e($record['mobile']); ?></td>
                                            <td><?php echo e($record['lzc_name']); ?></td>
                                            <td><?php echo e(number_format($record['amount'], 2)); ?></td>
                                            <td><?php echo e(number_format($record['charges'], 2)); ?></td>
                                            <td><?php echo e(number_format($record['total'], 2)); ?></td>
                                            <td><?php echo e($record['status']); ?></td>
                                            <td><?php echo e($record['tid']); ?></td>
                                            <td class="text-center">
                                                <?php if($record['success']): ?>
                                                    <span class="badge bg-success" style="font-size: 18px;">✓</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger" style="font-size: 18px;">✗</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!$record['success'] && !empty($record['failure_reason'])): ?>
                                                    <span class="text-danger"><?php echo e($record['failure_reason']); ?></span>
                                                <?php elseif($record['success']): ?>
                                                    <span class="text-success">Successfully processed</span>
                                                <?php else: ?>
                                                    <span class="text-muted">Not processed</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
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
        $('#financial_year_id').on('change', function() {
            const financialYearId = $(this).val();
            const installmentSelect = $('#installment_number');
            
            installmentSelect.html('<option value="">Loading...</option>');
            
            if (!financialYearId) {
                installmentSelect.html('<option value="">Select Financial Year first</option>');
                return;
            }
            
            $.ajax({
                url: '<?php echo e(route("jazzcash-response.installments")); ?>',
                method: 'GET',
                data: {
                    financial_year_id: financialYearId
                },
                success: function(response) {
                    installmentSelect.html('<option value="">Select Installment</option>');
                    response.forEach(function(installment) {
                        installmentSelect.append(
                            $('<option></option>')
                                .attr('value', installment.number)
                                .text('Installment #' + installment.number + ' (Amount: ' + parseFloat(installment.amount).toLocaleString() + ')')
                        );
                    });
                },
                error: function() {
                    installmentSelect.html('<option value="">Error loading installments</option>');
                }
            });
        });

        // Trigger change if financial year is pre-selected (from old input)
        <?php if(old('financial_year_id')): ?>
            $('#financial_year_id').trigger('change');
        <?php endif; ?>

        // Download template (no validation needed - just download empty template)
        $('#downloadTemplateBtn').on('click', function() {
            // Direct download - no parameters needed
            window.location.href = '<?php echo e(route("jazzcash-response.download-template")); ?>';
        });

        // Form submission loading state
        $('#uploadForm').on('submit', function() {
            $('#submitBtn').prop('disabled', true).html('<i class="ti-reload"></i> Processing...');
        });

        // Initialize DataTables for results table if it exists
        <?php if(session('allRecords') && count(session('allRecords')) > 0): ?>
            if ($.fn.DataTable.isDataTable('#resultsTable')) {
                $('#resultsTable').DataTable().destroy();
            }
            
            const resultsTable = $('#resultsTable').DataTable({
                responsive: false,
                pageLength: 25,
                order: [[0, 'asc']],
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="ti-files"></i> Copy',
                        className: 'btn btn-sm btn-secondary',
                        exportOptions: {
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
                        filename: 'jazzcash-processed-records-' + new Date().toISOString().split('T')[0],
                        exportOptions: {
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
                        filename: 'jazzcash-processed-records-' + new Date().toISOString().split('T')[0],
                        exportOptions: {
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
                        filename: 'jazzcash-processed-records-' + new Date().toISOString().split('T')[0],
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
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
                        extend: 'print',
                        text: '<i class="ti-printer"></i> Print',
                        className: 'btn btn-sm btn-info',
                        exportOptions: {
                            format: {
                                body: function(data, row, column, node) {
                                    var div = document.createElement('div');
                                    div.innerHTML = data;
                                    var text = div.textContent || div.innerText || '';
                                    return text.trim().replace(/\s+/g, ' ');
                                }
                            }
                        }
                    }
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries to show",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                }
            });

            // Apply filters
            $('#filterProcessingStatus').on('change', function() {
                const value = this.value;
                if (value === 'Success') {
                    resultsTable.column(11).search('✓').draw();
                } else if (value === 'Failed') {
                    resultsTable.column(11).search('✗').draw();
                } else {
                    resultsTable.column(11).search('').draw();
                }
            });

            $('#filterJazzCashStatus').on('change', function() {
                resultsTable.column(9).search(this.value).draw();
            });

            $('#filterLZC').on('change', function() {
                resultsTable.column(5).search(this.value).draw();
            });

            $('#filterSearch').on('keyup', function() {
                resultsTable.search(this.value).draw();
            });

            // Clear filters button
            $('#clearFilters').on('click', function() {
                $('#filterProcessingStatus').val('').trigger('change');
                $('#filterJazzCashStatus').val('').trigger('change');
                $('#filterLZC').val('').trigger('change');
                $('#filterSearch').val('');
                resultsTable.search('').columns().search('').draw();
            });
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/jazzcash-response/index.blade.php ENDPATH**/ ?>