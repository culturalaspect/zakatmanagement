<?php
    // Helper function to format numbers with K, M, B suffixes
    function formatNumber($number) {
        if ($number >= 1000000000) {
            return number_format($number / 1000000000, 2) . 'B';
        } elseif ($number >= 1000000) {
            return number_format($number / 1000000, 2) . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K';
        }
        return number_format($number);
    }
    
    // Helper function to format currency with K, M, B suffixes
    function formatCurrency($amount) {
        if ($amount >= 1000000000) {
            return 'Rs. ' . number_format($amount / 1000000000, 2) . 'B';
        } elseif ($amount >= 1000000) {
            return 'Rs. ' . number_format($amount / 1000000, 2) . 'M';
        } elseif ($amount >= 1000) {
            return 'Rs. ' . number_format($amount / 1000, 1) . 'K';
        }
        return 'Rs. ' . number_format($amount);
    }
?>



<?php $__env->startSection('title', config('app.name') . ' - Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>
<?php $__env->startSection('breadcrumb', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<!-- Widget Cards Row 1 - Using Theme's widget-chart style -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-primary"></div>
                <i class="ti-user text-primary"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['total_beneficiaries'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Total Beneficiaries</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-success"></div>
                <i class="ti-check-box text-success"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['approved_beneficiaries'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Approved</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-info"></div>
                <i class="ti-money text-info"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['paid_beneficiaries'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Paid</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-warning"></div>
                <i class="ti-time text-warning"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['pending_beneficiaries'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Pending</div>
        </div>
    </div>
</div>

<!-- Widget Cards Row 2 -->
<div class="row">
    <?php if(auth()->user()->isSuperAdmin() || auth()->user()->isAdministratorHQ()): ?>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-focus"></div>
                <i class="ti-money text-focus"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatCurrency($data['total_funds'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Total Funds</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-success"></div>
                <i class="ti-credit-card text-success"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatCurrency($data['disbursed_funds'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Disbursed</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-primary"></div>
                <i class="ti-map text-primary"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['districts'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Districts</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-info"></div>
                <i class="ti-calendar text-info"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['active_phases'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Active Phases</div>
        </div>
    </div>
    <?php else: ?>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-info"></div>
                <i class="ti-upload text-info"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['submitted_beneficiaries'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Submitted</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-danger"></div>
                <i class="ti-close text-danger"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['rejected_beneficiaries'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Rejected</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-success"></div>
                <i class="ti-money text-success"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatCurrency($data['disbursed_funds'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Disbursed</div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if(auth()->user()->isSuperAdmin() || auth()->user()->isAdministratorHQ()): ?>
<!-- Widget Cards Row 3 -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-danger"></div>
                <i class="ti-close text-danger"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['rejected_beneficiaries'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Rejected</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-warning"></div>
                <i class="ti-alert text-warning"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['payment_failed_beneficiaries'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Payment Failed</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-primary"></div>
                <i class="ti-bookmark text-primary"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['schemes'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Schemes</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 widget-chart card-hover-shadow-2x">
            <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-info"></div>
                <i class="ti-id-badge text-info"></i>
            </div>
            <div class="widget-numbers"><span><?php echo e(formatNumber($data['committees'] ?? 0)); ?></span></div>
            <div class="widget-subheading">Committees</div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Charts Section - Exact Theme Structure -->
<div class="row">
    <?php if(auth()->user()->isSuperAdmin() || auth()->user()->isAdministratorHQ()): ?>
    <!-- Revenue Chart (Main Chart) - col-xl-8 -->
    <div class="col-xl-8">
        <div class="white_card mb_30 card_height_100">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Fund Disbursement Trends</h3>
                    </div>
                    <div class="float-lg-right float-none common_tab_btn2 justify-content-end">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-period="month">Month</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#" data-period="week">Week</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-period="day">Day</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div id="marketchart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
    
    <!-- Sales Card Wrapper - col-xl-4 (Red background with illustration) -->
    <div class="col-xl-4">
        <div class="white_card card_height_100 mb_30 sales_card_wrapper">
            <div class="white_card_header d-flex justify-content-end">
                <button class="export_btn"></button>
            </div>
            <div class="sales_card_body">
                <div class="single_sales">
                    <span>Paid Beneficiaries</span>
                    <h3><?php echo e(formatNumber($data['paid_beneficiaries'] ?? 0)); ?></h3>
                </div>
                <div class="single_sales">
                    <span>Total Beneficiaries</span>
                    <h3><?php echo e(formatNumber($data['total_beneficiaries'] ?? 0)); ?></h3>
                </div>
                <div class="single_sales">
                    <span>Disbursed Funds</span>
                    <h3><?php echo e(formatCurrency($data['disbursed_funds'] ?? 0)); ?></h3>
                </div>
                <div class="single_sales">
                    <span>Total Funds</span>
                    <h3><?php echo e(formatCurrency($data['total_funds'] ?? 0)); ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Social Media Card - col-xl-4 (Purple background with illustration) -->
    <div class="col-xl-4">
        <div class="white_card card_height_100 mb_30 social_media_card">
            <div class="white_card_header">
                <div class="main-title">
                    <h3 class="m-0">Scheme Statistics</h3>
                    <span>Distribution by Scheme</span>
                </div>
            </div>
            <div class="media_thumb ml_25">
                <!-- Illustration placeholder - theme uses img/media.svg -->
            </div>
            <div class="media_card_body">
                <div class="media_card_list">
                    <?php
                        $topSchemes = collect($data['top_schemes'] ?? [])->take(4);
                        $schemeColors = [
                            ['bg' => '#FFF4DE', 'span' => '#FFC881', 'h3' => '#FFAD44'],
                            ['bg' => '#E2FFF6', 'span' => '#69E2BD', 'h3' => '#25C997'],
                            ['bg' => '#FFE2E5', 'span' => '#FF8895', 'h3' => '#F64E60'],
                            ['bg' => '#E1F0FF', 'span' => '#76B5F5', 'h3' => '#3699FF']
                        ];
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $topSchemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $scheme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="single_media_card" style="background: <?php echo e($schemeColors[$index % 4]['bg']); ?>;">
                        <span style="color: <?php echo e($schemeColors[$index % 4]['span']); ?>;"><?php echo e($scheme->name); ?></span>
                        <h3 style="color: <?php echo e($schemeColors[$index % 4]['h3']); ?>;"><?php echo e(number_format($scheme->beneficiaries_count ?? 0)); ?></h3>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php for($i = 0; $i < 4; $i++): ?>
                    <div class="single_media_card" style="background: <?php echo e($schemeColors[$i]['bg']); ?>;">
                        <span style="color: <?php echo e($schemeColors[$i]['span']); ?>;">No Data</span>
                        <h3 style="color: <?php echo e($schemeColors[$i]['h3']); ?>;">0</h3>
                    </div>
                    <?php endfor; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Visitors by Browser Style - Top Districts - col-xl-4 -->
    <div class="col-xl-4">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Top Districts</h3>
                        <span><?php echo e(number_format($data['total_beneficiaries'] ?? 0)); ?> Beneficiaries</span>
                    </div>
                    <div class="float-lg-right float-none common_tab_btn justify-content-end">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">All</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div id="chart-currently" style="min-height: 200px;"></div>
                <div class="monthly_plan_wraper">
                    <?php $__empty_1 = true; $__currentLoopData = $data['top_districts']->take(3) ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="single_plan d-flex align-items-center justify-content-between">
                        <div class="plan_left d-flex align-items-center">
                            <div class="thumb">
                                <i class="ti-map" style="font-size: 24px; color: #3f6ad8; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"></i>
                            </div>
                            <div>
                                <h5><?php echo e($district->name); ?></h5>
                                <span>District</span>
                            </div>
                        </div>
                        <span class="brouser_btn"><?php echo e(number_format($district->beneficiaries_count ?? 0)); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="single_plan d-flex align-items-center justify-content-between">
                        <div class="plan_left d-flex align-items-center">
                            <div class="thumb">
                                <i class="ti-map" style="font-size: 24px; color: #3f6ad8; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"></i>
                            </div>
                            <div>
                                <h5>No Data</h5>
                                <span>District</span>
                            </div>
                        </div>
                        <span class="brouser_btn">0</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Visitors by Device Style - Payment Status - col-xl-4 -->
    <div class="col-xl-4">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="main-title">
                    <h3 class="m-0">Payment Status</h3>
                    <span><?php echo e(number_format($data['total_beneficiaries'] ?? 0)); ?> Total</span>
                </div>
            </div>
            <div class="white_card_body">
                <div class="card_container">
                    <div id="platform_type_dates_donut" style="height:280px"></div>
                </div>
                <div class="devices_btn text-center">
                    <a class="color_button color_button2" href="#">Paid</a>
                    <a class="color_button" href="#">Failed</a>
                    <a class="color_button color_button3" href="#">Pending</a>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- District User - Status Chart -->
    <div class="col-xl-6">
        <div class="white_card mb_30 card_height_100">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Beneficiary Status Distribution</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div id="statusChart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
    
    <!-- District User - Payment Status Chart -->
    <div class="col-xl-6">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="main-title">
                    <h3 class="m-0">Payment Status</h3>
                    <span><?php echo e(number_format($data['total_beneficiaries'] ?? 0)); ?> Total</span>
                </div>
            </div>
            <div class="white_card_body">
                <div class="card_container">
                    <div id="districtPaymentStatusDonut" style="height:280px"></div>
                </div>
                <div class="devices_btn text-center">
                    <a class="color_button color_button2" href="#">Paid</a>
                    <a class="color_button" href="#">Failed</a>
                    <a class="color_button color_button3" href="#">Pending</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if(auth()->user()->isSuperAdmin() || auth()->user()->isAdministratorHQ()): ?>
<!-- Running Campaign Style - Recent Beneficiaries Table -->
<div class="row">
    <div class="col-xl-12">
        <div class="white_card card_height_100 mb_30">
            <div class="row">
                <div class="col-lg-9">
                    <div class="white_card_header">
                        <div class="box_header m-0">
                            <div class="main-title">
                                <h3 class="m-0">Recent Beneficiaries</h3>
                                <span>Overview</span>
                            </div>
                        </div>
                    </div>
                    <div class="white_card_body QA_section">
                        <div class="QA_table">
                            <table class="table lms_table_active2 p-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Beneficiary</th>
                                        <th scope="col">Scheme</th>
                                        <th scope="col">District</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $data['recent_beneficiaries'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficiary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="customer d-flex align-items-center">
                                                <div class="social_media" style="width: 54px; height: 54px; line-height: 54px; font-size: 24px; flex: 54px 0 0;">
                                                    <i class="ti-user"></i>
                                                </div>
                                                <div class="ml_18">
                                                    <h3 class="f_s_16 f_w_900 mb-0"><?php echo e($beneficiary->full_name); ?></h3>
                                                    <span class="f_s_11 f_w_700 text_color_8"><?php echo e($beneficiary->cnic); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h3 class="f_s_16 f_w_800 mb-0"><?php echo e($beneficiary->scheme->name ?? 'N/A'); ?></h3>
                                                <span class="f_s_11 f_w_500 color_text_3"><?php echo e($beneficiary->schemeCategory->name ?? 'N/A'); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h3 class="f_s_16 f_w_800 mb-0"><?php echo e($beneficiary->phase->district->name ?? 'N/A'); ?></h3>
                                                <span class="f_s_11 f_w_500 color_text_3"><?php echo e($beneficiary->localZakatCommittee->name ?? 'N/A'); ?></span>
                                            </div>
                                        </td>
                                        <td class="f_s_13 f_w_400 color_text_3">
                                            <?php if($beneficiary->status == 'approved'): ?>
                                                <a href="#" class="badge_active">Approved</a>
                                            <?php elseif($beneficiary->status == 'paid'): ?>
                                                <a href="#" class="badge_active2">Paid</a>
                                            <?php elseif($beneficiary->status == 'pending'): ?>
                                                <a href="#" class="badge_active4">Pending</a>
                                            <?php elseif($beneficiary->status == 'rejected'): ?>
                                                <a href="#" class="badge_active3">Rejected</a>
                                            <?php else: ?>
                                                <a href="#" class="badge_active4">Submitted</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <h3 class="f_s_16 f_w_900 mb-0">Rs. <?php echo e(number_format($beneficiary->amount, 2)); ?></h3>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No beneficiaries found</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 white_card_body pt_25">
                        <div class="project_complete">
                        <div class="single_pro d-flex">
                            <div class="probox"></div>
                            <div class="box_content">
                                <h4><?php echo e(formatNumber($data['approved_beneficiaries'] ?? 0)); ?></h4>
                                <span>Approved</span>
                            </div>
                        </div>
                        <div class="single_pro d-flex">
                            <div class="probox blue_box"></div>
                            <div class="box_content">
                                <h4 class="bluish_text"><?php echo e(formatNumber($data['paid_beneficiaries'] ?? 0)); ?></h4>
                                <span class="grayis_text">Paid</span>
                            </div>
                        </div>
                        <div class="single_pro d-flex">
                            <div class="probox" style="background: #ffc107;"></div>
                            <div class="box_content">
                                <h4 style="color: #ffc107;"><?php echo e(formatNumber($data['pending_beneficiaries'] ?? 0)); ?></h4>
                                <span class="grayis_text">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Charts Row -->
<div class="row">
    <div class="col-xl-6">
        <div class="white_card mb_30 card_height_100">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">District-wise Beneficiaries</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div id="districtChart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="white_card mb_30 card_height_100">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Scheme-wise Distribution</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div id="schemeChart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Trends Chart -->
<div class="row">
    <div class="col-xl-12">
        <div class="white_card mb_30 card_height_100">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Monthly Trends (Last 12 Months)</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div id="trendsChart" style="min-height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<!-- District User Charts -->
<div class="row">
    <!-- Active Phases Chart (Top Districts Style) -->
    <div class="col-xl-4">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Active Phases</h3>
                        <span><?php echo e(number_format(collect($data['active_phases_list'] ?? [])->sum('beneficiaries_count'))); ?> Beneficiaries</span>
                    </div>
                    <div class="float-lg-right float-none common_tab_btn justify-content-end">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">All</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div id="activePhasesChart" style="min-height: 200px;"></div>
                <div class="monthly_plan_wraper">
                    <?php $__empty_1 = true; $__currentLoopData = $data['active_phases_list']->take(3) ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="single_plan d-flex align-items-center justify-content-between">
                        <div class="plan_left d-flex align-items-center">
                            <div class="thumb">
                                <i class="ti-calendar" style="font-size: 24px; color: #3f6ad8; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"></i>
                            </div>
                            <div>
                                <h5><?php echo e($phase->scheme->name ?? 'N/A'); ?></h5>
                                <span>Phase</span>
                            </div>
                        </div>
                        <span class="brouser_btn"><?php echo e(number_format($phase->beneficiaries_count ?? 0)); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="single_plan d-flex align-items-center justify-content-between">
                        <div class="plan_left d-flex align-items-center">
                            <div class="thumb">
                                <i class="ti-calendar" style="font-size: 24px; color: #3f6ad8; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"></i>
                            </div>
                            <div>
                                <h5>No Active Phases</h5>
                                <span>Phase</span>
                            </div>
                        </div>
                        <span class="brouser_btn">0</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scheme-wise Distribution Chart -->
    <div class="col-xl-8">
        <div class="white_card mb_30 card_height_100">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Scheme-wise Distribution</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div id="schemeChart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Trends Chart for District User -->
<div class="row">
    <div class="col-xl-12">
        <div class="white_card mb_30 card_height_100">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Monthly Trends (Last 12 Months)</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div id="districtTrendsChart" style="min-height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Modern Widget Styling */
    .widget-chart {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .widget-chart:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .card-hover-shadow-2x:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2) !important;
    }
    
    .widget-numbers {
        font-weight: 700 !important;
        color: #2E4765;
        letter-spacing: -0.5px;
    }
    
    .widget-numbers span {
        font-weight: 700 !important;
        color: #2E4765;
    }
    
    .widget-subheading {
        color: #7F8B9F;
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }
    
    .icon-wrapper {
        transition: transform 0.3s ease;
    }
    
    .widget-chart:hover .icon-wrapper {
        transform: scale(1.1);
    }
    
    .icon-wrapper-bg {
        border-radius: 50%;
    }
    
    /* Sales Card Styling */
    .sales_card_wrapper {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .sales_card_wrapper .sales_card_body {
        background: #fff;
        border-radius: 15px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        row-gap: 40px;
        padding: 20px 14px;
        position: relative;
        margin: 200px 15px 0px 15px;
        bottom: 15px;
    }
    
    .sales_card_body .single_sales h3 {
        font-weight: 700;
        color: #2E4765;
    }
    
    .sales_card_body .single_sales span {
        font-weight: 600;
        color: #7F8B9F;
    }
    
    /* Social Media Card Styling */
    .social_media_card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .single_media_card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .single_media_card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }
    
    /* Project Complete Styling */
    .project_complete .single_pro {
        margin-bottom: 1.5rem;
    }
    
    .project_complete .probox {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        margin-right: 15px;
        margin-top: 5px;
    }
    
    .project_complete .box_content h4 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .project_complete .box_content span {
        font-size: 0.875rem;
        color: #7F8B9F;
    }
    
    /* Recent Beneficiaries Table Styling */
    .lms_table_active2 .social_media {
        width: 54px !important;
        height: 54px !important;
        min-width: 54px;
        min-height: 54px;
        max-width: 54px;
        max-height: 54px;
        line-height: 54px !important;
        font-size: 24px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 54px 0 0 !important;
    }
    
    .lms_table_active2 .social_media i {
        font-size: 24px;
    }
    
    .lms_table_active2 .customer .ml_18 h3 {
        font-size: 16px !important;
    }
    
    .lms_table_active2 .customer .ml_18 span {
        font-size: 11px !important;
    }
    
    .lms_table_active2 td > div > h3 {
        font-size: 16px !important;
    }
    
    .lms_table_active2 td > div > span {
        font-size: 11px !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if(auth()->user()->isSuperAdmin() || auth()->user()->isAdministratorHQ()): ?>
    
    // Main Revenue Chart (Fund Disbursement Trends) - Bar Chart Style
    var trendsData = <?php echo json_encode($data['monthly_trends'] ?? [], 15, 512) ?>;
    var trendsLabels = trendsData.map(function(item) {
        return item.month;
    });
    var trendsAmounts = trendsData.map(function(item) {
        return parseFloat((item.total_amount || 0) / 1000000);
    });
    var trendsCounts = trendsData.map(function(item) {
        return item.count || 0;
    });
    
    // Create sample data for bar chart (matching theme's bar chart style)
    var marketChartOptions = {
        series: [
            {
                name: 'Disbursed',
                data: trendsAmounts
            },
            {
                name: 'Allocated',
                data: trendsAmounts.map(function(val) {
                    return val * 1.2; // Simulate allocated vs disbursed
                })
            }
        ],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: true
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: trendsLabels,
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    if (val >= 1000) {
                        return 'Rs. ' + (val / 1000).toFixed(2) + 'B';
                    } else if (val >= 1) {
                        return 'Rs. ' + val.toFixed(0) + 'M';
                    } else {
                        return 'Rs. ' + (val * 1000).toFixed(0) + 'K';
                    }
                },
                style: {
                    fontSize: '12px'
                }
            }
        },
        fill: {
            opacity: 1
        },
        colors: ['#F65365', '#E0E0E0'],
        tooltip: {
            y: {
                formatter: function(val) {
                    if (val >= 1000) {
                        return 'Rs. ' + (val / 1000).toFixed(2) + 'B';
                    } else if (val >= 1) {
                        return 'Rs. ' + val.toFixed(2) + 'M';
                    } else {
                        return 'Rs. ' + (val * 1000).toFixed(0) + 'K';
                    }
                }
            }
        }
    };
    var marketChart = new ApexCharts(document.querySelector("#marketchart"), marketChartOptions);
    marketChart.render();
    
    // Period Tabs Handler
    document.querySelectorAll('.common_tab_btn2 .nav-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.common_tab_btn2 .nav-link').forEach(function(l) {
                l.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
    
    // District Chart (Top Districts - Line Chart Style)
    var districtData = <?php echo json_encode($data['top_districts'] ?? [], 15, 512) ?>;
    var districtLabels = districtData.map(function(item) {
        return item.name;
    });
    var districtValues = districtData.map(function(item) {
        return item.beneficiaries_count || 0;
    });
    
    var chartCurrentlyOptions = {
        series: [{
            name: 'Beneficiaries',
            data: districtValues.slice(0, 6) // Top 6 for line chart
        }],
        chart: {
            type: 'line',
            height: 200,
            toolbar: {
                show: false
            },
            sparkline: {
                enabled: false
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#3f6ad8']
        },
        markers: {
            size: 4,
            colors: ['#3f6ad8']
        },
        xaxis: {
            categories: districtLabels.slice(0, 6),
            labels: {
                show: false
            }
        },
        yaxis: {
            labels: {
                show: false
            }
        },
        colors: ['#3f6ad8'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#5a7fe8'],
                inverseColors: false,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 100]
            }
        },
        tooltip: {
            enabled: true,
            y: {
                formatter: function(val) {
                    return val + ' beneficiaries';
                }
            }
        }
    };
    var chartCurrently = new ApexCharts(document.querySelector("#chart-currently"), chartCurrentlyOptions);
    chartCurrently.render();
    
    // Payment Status Donut Chart
    var paymentData = <?php echo json_encode($data['payment_status'] ?? [], 15, 512) ?>;
    var platformDonutOptions = {
        series: [paymentData.paid || 0, paymentData.payment_failed || 0, paymentData.pending_payment || 0],
        chart: {
            type: 'donut',
            height: 280
        },
        labels: ['Paid', 'Payment Failed', 'Pending Payment'],
        colors: ['#9AFDD2', '#8950FC', '#FFCA60'], // Light mint green, vibrant purple, soft orange - matching button colors
        legend: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                },
                expandOnClick: false
            }
        },
        stroke: {
            show: false
        },
        states: {
            hover: {
                filter: {
                    type: 'none'
                }
            }
        }
    };
    var platformDonut = new ApexCharts(document.querySelector("#platform_type_dates_donut"), platformDonutOptions);
    platformDonut.render();
    
    // District-wise Chart - Line Chart with two lines (blue and teal)
    // Use total beneficiaries and calculate approved based on overall approval rate
    var overallApprovalRate = <?php echo e($data['total_beneficiaries'] > 0 ? round(($data['approved_beneficiaries'] / $data['total_beneficiaries']) * 100, 0) : 0); ?>;
    var districtApprovedValues = districtValues.map(function(val) {
        return Math.round(val * (overallApprovalRate / 100));
    });
    
    var districtChartOptions = {
        series: [
            {
                name: 'Total Beneficiaries',
                data: districtValues
            },
            {
                name: 'Approved',
                data: districtApprovedValues
            }
        ],
        chart: {
            type: 'line',
            height: 350,
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        dataLabels: {
            enabled: false
        },
        markers: {
            size: 4,
            hover: {
                sizeOffset: 2
            }
        },
        xaxis: {
            categories: districtLabels
        },
        yaxis: {
            title: {
                text: 'Beneficiaries'
            }
        },
        colors: ['#3f6ad8', '#20c997'], // Blue and teal
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " beneficiaries"
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        }
    };
    var districtChart = new ApexCharts(document.querySelector("#districtChart"), districtChartOptions);
    districtChart.render();
    
    // Scheme-wise Chart
    var schemeData = <?php echo json_encode($data['scheme_stats'] ?? [], 15, 512) ?>;
    var schemeLabels = schemeData.map(function(item) {
        return item.name;
    });
    var schemeValues = schemeData.map(function(item) {
        return item.beneficiaries_count || 0;
    });
    
    // Scheme-wise Chart - Multiple Radial Bars
    // Calculate percentages for radial bars (max value as 100%)
    var maxSchemeValue = Math.max.apply(null, schemeValues);
    var schemePercentages = schemeValues.map(function(val) {
        return maxSchemeValue > 0 ? Math.round((val / maxSchemeValue) * 100) : 0;
    });
    
    var schemeChartOptions = {
        series: schemePercentages,
        chart: {
            type: 'radialBar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '70%'
                },
                dataLabels: {
                    show: true,
                    name: {
                        show: true,
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#666',
                        offsetY: -10
                    },
                    value: {
                        show: true,
                        fontSize: '16px',
                        fontWeight: 700,
                        color: '#333',
                        offsetY: 16,
                        formatter: function(val) {
                            var index = schemePercentages.indexOf(val);
                            return schemeValues[index] || 0;
                        }
                    },
                    total: {
                        show: true,
                        label: 'Total',
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#666',
                        formatter: function() {
                            return schemeValues.reduce(function(a, b) { return a + b; }, 0);
                        }
                    }
                }
            }
        },
        labels: schemeLabels,
        colors: ['#3f6ad8', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14'],
        stroke: {
            lineCap: 'round'
        },
        tooltip: {
            y: {
                formatter: function(val, opts) {
                    var index = opts.seriesIndex;
                    return schemeValues[index] + " beneficiaries (" + val + "%)";
                }
            }
        }
    };
    var schemeChart = new ApexCharts(document.querySelector("#schemeChart"), schemeChartOptions);
    schemeChart.render();
    
    // Monthly Trends Chart - Step Line Chart
    var trendsChartOptions = {
        series: [
            {
                name: 'Beneficiaries',
                data: trendsCounts
            },
            {
                name: 'Amount (Rs. M)',
                data: trendsAmounts
            }
        ],
        chart: {
            height: 400,
            type: 'line',
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: 'stepline',
            width: 3
        },
        dataLabels: {
            enabled: false
        },
        markers: {
            size: 4,
            hover: {
                sizeOffset: 4
            }
        },
        xaxis: {
            categories: trendsLabels
        },
        yaxis: {
            title: {
                text: 'Count / Amount (Rs. M)'
            }
        },
        colors: ['#3f6ad8', '#28a745'],
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (y, opts) {
                    if (typeof y !== "undefined") {
                        if (opts.seriesIndex === 0) {
                            return y.toFixed(0) + " beneficiaries";
                        } else {
                            return "Rs. " + y.toFixed(2) + "M";
                        }
                    }
                    return y;
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        }
    };
    var trendsChart = new ApexCharts(document.querySelector("#trendsChart"), trendsChartOptions);
    trendsChart.render();
    
    <?php else: ?>
    
    // District User - Status Distribution Chart (Donut Chart)
    var statusData = <?php echo json_encode($data['status_distribution'] ?? [], 15, 512) ?>;
    var statusLabels = Object.keys(statusData).map(function(key) {
        return key.charAt(0).toUpperCase() + key.slice(1);
    });
    var statusValues = Object.values(statusData);
    
    if (statusValues.length > 0) {
        var statusChartOptions = {
            series: statusValues,
            chart: {
                type: 'donut',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            labels: statusLabels,
            colors: ['#3f6ad8', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + '%';
                }
            },
            tooltip: {
                y: {
                    formatter: function(val, opts) {
                        var index = opts.seriesIndex;
                        return statusValues[index] + " beneficiaries (" + val.toFixed(1) + "%)";
                    }
                }
            }
        };
        var statusChart = new ApexCharts(document.querySelector("#statusChart"), statusChartOptions);
        statusChart.render();
    }
    
    // District User - Payment Status Donut Chart
    var districtPaymentData = {
        paid: <?php echo e($data['paid_beneficiaries'] ?? 0); ?>,
        payment_failed: <?php echo e($data['rejected_beneficiaries'] ?? 0); ?>,
        pending_payment: <?php echo e($data['approved_beneficiaries'] ?? 0); ?>

    };
    var districtPaymentDonutOptions = {
        series: [districtPaymentData.paid || 0, districtPaymentData.payment_failed || 0, districtPaymentData.pending_payment || 0],
        chart: {
            type: 'donut',
            height: 280
        },
        labels: ['Paid', 'Payment Failed', 'Pending Payment'],
        colors: ['#9AFDD2', '#8950FC', '#FFCA60'], // Light mint green, vibrant purple, soft orange - matching admin panel
        legend: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                },
                expandOnClick: false
            }
        },
        stroke: {
            show: false
        },
        states: {
            hover: {
                filter: {
                    type: 'none'
                }
            }
        }
    };
    var districtPaymentDonut = new ApexCharts(document.querySelector("#districtPaymentStatusDonut"), districtPaymentDonutOptions);
    districtPaymentDonut.render();
    
    // District User - Active Phases Chart (Line Chart - Top Districts Style)
    var activePhasesData = <?php echo json_encode($data['active_phases_list'] ?? [], 15, 512) ?>;
    var phaseLabels = activePhasesData.map(function(item) {
        return item.scheme ? item.scheme.name : 'N/A';
    });
    var phaseValues = activePhasesData.map(function(item) {
        return item.beneficiaries_count || 0;
    });
    
    if (phaseValues.length > 0) {
        var activePhasesChartOptions = {
            series: [{
                name: 'Beneficiaries',
                data: phaseValues.slice(0, 6) // Top 6 for line chart
            }],
            chart: {
                type: 'line',
                height: 200,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: false
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#3f6ad8']
            },
            markers: {
                size: 4,
                colors: ['#3f6ad8']
            },
            xaxis: {
                categories: phaseLabels.slice(0, 6),
                labels: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            colors: ['#3f6ad8'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#5a7fe8'],
                    inverseColors: false,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 100]
                }
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val + ' beneficiaries';
                    }
                }
            }
        };
        var activePhasesChart = new ApexCharts(document.querySelector("#activePhasesChart"), activePhasesChartOptions);
        activePhasesChart.render();
    }
    
    // District User - Scheme-wise Chart (Multiple Radial Bars)
    var schemeData = <?php echo json_encode($data['scheme_stats'] ?? [], 15, 512) ?>;
    var schemeLabels = schemeData.map(function(item) {
        return item.name;
    });
    var schemeValues = schemeData.map(function(item) {
        return item.beneficiaries_count || 0;
    });
    
    if (schemeValues.length > 0) {
        // Calculate percentages for radial bars (max value as 100%)
        var maxSchemeValue = Math.max.apply(null, schemeValues);
        var schemePercentages = schemeValues.map(function(val) {
            return maxSchemeValue > 0 ? Math.round((val / maxSchemeValue) * 100) : 0;
        });
        
        var schemeChartOptions = {
            series: schemePercentages,
            chart: {
                type: 'radialBar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '70%'
                    },
                    dataLabels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '14px',
                            fontWeight: 600,
                            color: '#666',
                            offsetY: -10
                        },
                        value: {
                            show: true,
                            fontSize: '16px',
                            fontWeight: 700,
                            color: '#333',
                            offsetY: 16,
                            formatter: function(val) {
                                var index = schemePercentages.indexOf(val);
                                return schemeValues[index] || 0;
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '14px',
                            fontWeight: 600,
                            color: '#666',
                            formatter: function() {
                                return schemeValues.reduce(function(a, b) { return a + b; }, 0);
                            }
                        }
                    }
                }
            },
            labels: schemeLabels,
            colors: ['#3f6ad8', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14'],
            stroke: {
                lineCap: 'round'
            },
            tooltip: {
                y: {
                    formatter: function(val, opts) {
                        var index = opts.seriesIndex;
                        return schemeValues[index] + " beneficiaries (" + val + "%)";
                    }
                }
            }
        };
        var schemeChart = new ApexCharts(document.querySelector("#schemeChart"), schemeChartOptions);
        schemeChart.render();
    }
    
    // District User - Monthly Trends Chart (Step Line)
    var districtTrendsData = <?php echo json_encode($data['monthly_trends'] ?? [], 15, 512) ?>;
    var districtTrendsLabels = districtTrendsData.map(function(item) {
        return item.month;
    });
    var districtTrendsAmounts = districtTrendsData.map(function(item) {
        return parseFloat((item.total_amount || 0) / 1000000);
    });
    var districtTrendsCounts = districtTrendsData.map(function(item) {
        return item.count || 0;
    });
    
    if (districtTrendsCounts.length > 0) {
        var districtTrendsChartOptions = {
            series: [
                {
                    name: 'Beneficiaries',
                    data: districtTrendsCounts
                },
                {
                    name: 'Amount (Rs. M)',
                    data: districtTrendsAmounts
                }
            ],
            chart: {
                height: 400,
                type: 'line',
                toolbar: {
                    show: false
                }
            },
            stroke: {
                curve: 'stepline',
                width: 3
            },
            dataLabels: {
                enabled: false
            },
            markers: {
                size: 4,
                hover: {
                    sizeOffset: 4
                }
            },
            xaxis: {
                categories: districtTrendsLabels
            },
            yaxis: {
                title: {
                    text: 'Count / Amount (Rs. M)'
                }
            },
            colors: ['#3f6ad8', '#28a745'],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y, opts) {
                        if (typeof y !== "undefined") {
                            if (opts.seriesIndex === 0) {
                                return y.toFixed(0) + " beneficiaries";
                            } else {
                                return "Rs. " + y.toFixed(2) + "M";
                            }
                        }
                        return y;
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        };
        var districtTrendsChart = new ApexCharts(document.querySelector("#districtTrendsChart"), districtTrendsChartOptions);
        districtTrendsChart.render();
    }
    
    <?php endif; ?>
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\php_projects_new\zakat_beneficiaries\laravel_project\resources\views/dashboard/index.blade.php ENDPATH**/ ?>