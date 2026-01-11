<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Zakat Beneficiaries'))</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap1.min.css') }}" />
    <!-- themefy CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/themefy_icon/themify-icons.css') }}" />
    <!-- select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/niceselect/css/nice-select.css') }}" />
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/font_awesome/css/all.min.css') }}" />
    <!-- date picker -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/datepicker/date-picker.css') }}" />
    <!-- scrollabe  -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/scroll/scrollable.css') }}" />
    <!-- datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatable/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatable/css/responsive.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatable/css/buttons.dataTables.min.css') }}" />
    <!-- menu css  -->
    <link rel="stylesheet" href="{{ asset('assets/css/metisMenu.css') }}">
    <!-- style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style1.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/colors/default.css') }}" id="colorSkinCSS">
    
    <style>
        /* Enhanced Profile Menu Hover Effects */
        .profile_info .profile_info_details a {
            position: relative;
            transition: all 0.3s ease;
            border-radius: 5px;
            margin: 2px 0;
        }
        
        .profile_info .profile_info_details a:hover {
            background-color: #f0f4f8;
            color: #567AED !important;
            padding-left: 15px;
            transform: translateX(3px);
        }
        
        .profile_info .profile_info_details a:active {
            background-color: #e8edf3;
        }
        
        /* Add a subtle left border on hover */
        .profile_info .profile_info_details a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 60%;
            background-color: #567AED;
            border-radius: 0 3px 3px 0;
            transition: width 0.3s ease;
        }
        
        .profile_info .profile_info_details a:hover::before {
            width: 3px;
        }
        
        /* Icon hover effect */
        .profile_info .profile_info_details a:hover i {
            color: #567AED !important;
            transform: translateX(3px);
        }
        
        /* Logout button special styling */
        .profile_info .profile_info_details a:last-child:hover {
            background-color: #fee;
            color: #dc3545 !important;
        }
        
        .profile_info .profile_info_details a:last-child:hover::before {
            background-color: #dc3545;
        }
        
        /* SweetAlert2 Custom Styling */
        .swal2-cancel {
            background-color: #000000 !important;
            color: #ffffff !important;
        }
        
        .swal2-cancel:hover {
            background-color: #333333 !important;
            color: #ffffff !important;
        }
        
        /* Toast Notification Styling - Top Right Corner */
        .swal2-toast-container {
            top: 20px !important;
            right: 20px !important;
            width: auto !important;
            max-width: 400px !important;
        }
        
        .swal2-toast {
            padding: 15px 20px !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            font-family: 'Mulish', sans-serif !important;
        }
        
        /* Notification Dropdown Styling */
        .unread-notification {
            background-color: #fff3cd !important;
            border-left: 3px solid #ffc107;
        }
        
        .single_notify {
            transition: background-color 0.2s ease;
        }
        
        .single_notify:hover {
            background-color: #f8f9fa !important;
        }
        
        .unread-notification:hover {
            background-color: #ffe69c !important;
            font-size: 14px !important;
            border-left: 4px solid !important;
        }
        
        .swal2-toast.swal2-success {
            background-color: #d4edda !important;
            color: #155724 !important;
            border-left-color: #28a745 !important;
        }
        
        .swal2-toast.swal2-error {
            background-color: #f8d7da !important;
            color: #721c24 !important;
            border-left-color: #dc3545 !important;
        }
        
        .swal2-toast.swal2-warning {
            background-color: #fff3cd !important;
            color: #856404 !important;
            border-left-color: #ffc107 !important;
        }
        
        .swal2-toast.swal2-info {
            background-color: #d1ecf1 !important;
            color: #0c5460 !important;
            border-left-color: #17a2b8 !important;
        }
        
        .swal2-toast .swal2-title {
            font-size: 16px !important;
            font-weight: 600 !important;
            margin-bottom: 5px !important;
        }
        
        .swal2-toast .swal2-content {
            font-size: 14px !important;
            margin: 0 !important;
        }
        
        .swal2-toast .swal2-icon {
            width: 32px !important;
            height: 32px !important;
            margin: 0 12px 0 0 !important;
        }
        
        .swal2-toast .swal2-icon.swal2-success {
            border-color: #28a745 !important;
            color: #28a745 !important;
        }
        
        .swal2-toast .swal2-icon.swal2-error {
            border-color: #dc3545 !important;
            color: #dc3545 !important;
        }
        
        /* Active Navigation Item Styling */
        .sidebar #sidebar_menu > li.active > a,
        .sidebar #sidebar_menu > li > a.active {
            border-left: 4px solid #F65365 !important;
            border-radius: 0 !important;
            background-color: transparent !important;
            color: #F65365 !important;
            font-weight: 600 !important;
        }
        
        .sidebar #sidebar_menu > li.active > a .nav_icon_small i,
        .sidebar #sidebar_menu > li.active > a .nav_icon_small img,
        .sidebar #sidebar_menu > li > a.active .nav_icon_small i,
        .sidebar #sidebar_menu > li > a.active .nav_icon_small img {
            filter: none !important;
            color: #F65365 !important;
        }
        
        .sidebar #sidebar_menu > li.active > a .nav_title span,
        .sidebar #sidebar_menu > li > a.active .nav_title span {
            color: #F65365 !important;
            font-weight: 600 !important;
        }
        
        .sidebar #sidebar_menu > li.active > a:hover,
        .sidebar #sidebar_menu > li > a.active:hover {
            border-left: 4px solid #F65365 !important;
            background-color: #F8FAFC !important;
            color: #F65365 !important;
        }
        
        .swal2-toast .swal2-icon.swal2-warning {
            border-color: #ffc107 !important;
            color: #ffc107 !important;
        }
        
        .swal2-toast .swal2-icon.swal2-info {
            border-color: #17a2b8 !important;
            color: #17a2b8 !important;
        }
        
        /* Fix delete button styling to match view/edit buttons */
        .action_btn {
            border: none !important;
            outline: none !important;
            padding: 0 !important;
            margin: 0 !important;
            cursor: pointer;
        }
        
        .action_btn:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        
        .action_btn.text-danger {
            color: #8950FC !important;
        }
        
        .action_btn.text-danger:hover {
            background: #8950FC !important;
            color: #fff !important;
        }
        
        /* Table Row Hover Effects */
        .table tbody tr {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }
        
        /* DataTable specific hover effects */
        table.dataTable tbody tr {
            transition: all 0.3s ease;
        }
        
        table.dataTable tbody tr:hover {
            background-color: #f8f9fa !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        /* Ensure hover works even with DataTable styling */
        .white_card_body .table tbody tr:hover {
            background-color: #f8f9fa !important;
        }
        
        /* Darker text color for datatable rows throughout the system */
        table.dataTable tbody td,
        table.dataTable tbody tr td,
        .dataTable tbody td,
        .dataTable tbody tr td {
            color: #2d3748 !important;
            font-weight: 400;
        }
        
        /* Ensure table cells have darker text */
        .table tbody td,
        .table tbody tr td,
        .white_card_body .table tbody td,
        .white_card_body .table tbody tr td {
            color: #2d3748 !important;
        }
        
        /* Darker text for table headers as well */
        table.dataTable thead th,
        .dataTable thead th,
        .table thead th {
            color: #1a202c !important;
            font-weight: 600;
        }
        
        /* Custom Pagination Styling - Matching Theme */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 30px 0;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .pagination .page-item {
            margin: 0;
        }
        
        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
            height: 45px;
            padding: 0 18px;
            text-align: center;
            background: #fff;
            color: #884FFB;
            border: 1px solid #E0E2E8;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 400;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
            box-sizing: border-box;
            line-height: 1;
            gap: 6px;
        }
        
        .pagination .page-link i {
            font-size: 14px;
            line-height: 1;
        }
        
        .pagination .page-link:hover {
            background: #884FFB;
            color: #fff;
            border-color: #884FFB;
            box-shadow: 0 4px 12px rgba(136, 79, 251, 0.3);
            transform: translateY(-2px);
        }
        
        .pagination .page-item.active .page-link {
            background: #884FFB;
            color: #fff;
            border-color: #884FFB;
            box-shadow: 0 2px 8px rgba(136, 79, 251, 0.2);
        }
        
        .pagination .page-item.active .page-link:hover {
            background: #884FFB;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(136, 79, 251, 0.3);
        }
        
        .pagination .page-item.disabled .page-link {
            background: #f5f5f5;
            color: #ccc;
            border-color: #E0E2E8;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .pagination .page-item.disabled .page-link:hover {
            background: #f5f5f5;
            color: #ccc;
            transform: none;
            box-shadow: none;
        }
        
        /* Ensure text doesn't overflow */
        .pagination .page-link:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(136, 79, 251, 0.15);
        }
        
        /* Previous/Next buttons with icons only - same size as number buttons */
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            min-width: 45px;
            padding: 0;
            font-weight: 400;
        }
        
        /* Ensure icons are properly centered */
        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .pagination .page-link i {
            font-size: 16px;
            line-height: 1;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .pagination .page-link {
                min-width: 40px;
                height: 40px;
                padding: 0;
            }
            
            .pagination .page-link i {
                font-size: 14px;
            }
        }
        
        /* DataTables Pagination Styling - Matching Theme */
        .dataTables_wrapper .dataTables_paginate {
            text-align: right !important;
            float: right !important;
            margin-top: 20px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            box-sizing: border-box !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 35px !important;
            width: 35px !important;
            height: 35px !important;
            padding: 0 !important;
            margin: 0 3px !important;
            text-align: center !important;
            text-decoration: none !important;
            cursor: pointer;
            color: #884FFB !important;
            background: #fff !important;
            border: 1px solid #E0E2E8 !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            font-weight: 400 !important;
            transition: all 0.3s ease !important;
            line-height: 35px !important;
            vertical-align: middle !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #884FFB !important;
            color: #fff !important;
            border-color: #884FFB !important;
            box-shadow: 0 4px 12px rgba(136, 79, 251, 0.3) !important;
            transform: translateY(-2px);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #884FFB !important;
            color: #fff !important;
            border-color: #884FFB !important;
            box-shadow: 0 2px 8px rgba(136, 79, 251, 0.2) !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            cursor: not-allowed !important;
            color: #ccc !important;
            background: #f5f5f5 !important;
            border-color: #E0E2E8 !important;
            opacity: 0.6;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:active {
            outline: none;
            transform: translateY(0);
        }
        
        .dataTables_wrapper .dataTables_paginate .ellipsis {
            padding: 0 10px;
            color: #884FFB;
        }
        
        /* Style icons in pagination buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button i {
            font-size: 16px;
            line-height: 1;
            display: inline-block;
        }
        
        /* Ensure Previous/Next buttons are same size as number buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            min-width: 35px !important;
            width: 35px !important;
            height: 35px !important;
            position: relative !important;
        }
        
        /* Style icons in Previous/Next buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous i,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next i {
            font-size: 14px !important;
            line-height: 35px !important;
            display: inline-block !important;
        }
        
        /* Hide text and show icons for Previous/Next buttons using CSS */
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous {
            text-indent: -9999px !important;
            overflow: hidden !important;
            position: relative !important;
            font-size: 0 !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous::before {
            content: '\e64a' !important;
            font-family: 'themify' !important;
            font-size: 14px !important;
            line-height: 35px !important;
            position: absolute !important;
            left: 0 !important;
            right: 0 !important;
            top: 0 !important;
            text-indent: 0 !important;
            text-align: center !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            text-indent: -9999px !important;
            overflow: hidden !important;
            position: relative !important;
            font-size: 0 !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.next::after {
            content: '\e649' !important;
            font-family: 'themify' !important;
            font-size: 14px !important;
            line-height: 35px !important;
            position: absolute !important;
            left: 0 !important;
            right: 0 !important;
            top: 0 !important;
            text-indent: 0 !important;
            text-align: center !important;
        }
        
        /* When JavaScript adds icons, show them instead of ::before/::after */
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous i {
            position: absolute !important;
            left: 50% !important;
            top: 0 !important;
            transform: translateX(-50%) !important;
            text-indent: 0 !important;
            font-size: 14px !important;
            line-height: 35px !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.next i {
            position: absolute !important;
            left: 50% !important;
            top: 0 !important;
            transform: translateX(-50%) !important;
            text-indent: 0 !important;
            font-size: 14px !important;
            line-height: 35px !important;
        }
        
        /* Hide ::before/::after when icon is present (for browsers that support :has) */
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous:has(i)::before,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next:has(i)::after {
            display: none !important;
        }
        
        /* Ensure all pagination buttons are properly aligned */
        .dataTables_wrapper .dataTables_paginate {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 3px !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            flex-shrink: 0 !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="crm_body_bg">
    <!-- sidebar  -->
    <nav class="sidebar">
        <div class="logo d-flex justify-content-between">
            <a style="margin-left:auto;margin-right:auto;" class="large_logo" href="{{ route('dashboard') }}"><img style="max-width:60px;" src="{{ asset('img/logo.png') }}" alt=""></a>
            {{-- <a class="small_logo" href="{{ route('dashboard') }}"><img src="{{ asset('img/gbdoelogo.png') }}" alt=""></a> --}}
            <div class="sidebar_close_icon d-lg-none">
                <i class="ti-close"></i>
            </div>
        </div>
        <ul id="sidebar_menu">
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <img src="{{ asset('assets/img/menu-icon/dashboard.svg') }}" alt="">
                    </div>
                    <div class="nav_title">
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdministratorHQ())
            <h4 class="menu-text"><span>MASTER DATA</span> <i class="fas fa-ellipsis-h"></i> </h4>
            <li class="{{ request()->routeIs('zakat-council-members.*') ? 'active' : '' }}">
                <a href="{{ route('zakat-council-members.index') }}" class="{{ request()->routeIs('zakat-council-members.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-user"></i>
                    </div>
                    <div class="nav_title">
                        <span>Zakat Council</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('districts.*') ? 'active' : '' }}">
                <a href="{{ route('districts.index') }}" class="{{ request()->routeIs('districts.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-map"></i>
                    </div>
                    <div class="nav_title">
                        <span>Districts</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('tehsils.*') ? 'active' : '' }}">
                <a href="{{ route('tehsils.index') }}" class="{{ request()->routeIs('tehsils.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-map-alt"></i>
                    </div>
                    <div class="nav_title">
                        <span>Tehsils</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('union-councils.*') ? 'active' : '' }}">
                <a href="{{ route('union-councils.index') }}" class="{{ request()->routeIs('union-councils.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-location-pin"></i>
                    </div>
                    <div class="nav_title">
                        <span>Union Councils</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('villages.*') ? 'active' : '' }}">
                <a href="{{ route('villages.index') }}" class="{{ request()->routeIs('villages.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-home"></i>
                    </div>
                    <div class="nav_title">
                        <span>Villages</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('mohallas.*') ? 'active' : '' }}">
                <a href="{{ route('mohallas.index') }}" class="{{ request()->routeIs('mohallas.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-pin"></i>
                    </div>
                    <div class="nav_title">
                        <span>Mohallas</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('schemes.*') ? 'active' : '' }}">
                <a href="{{ route('schemes.index') }}" class="{{ request()->routeIs('schemes.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-list"></i>
                    </div>
                    <div class="nav_title">
                        <span>Schemes</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('financial-years.*') ? 'active' : '' }}">
                <a href="{{ route('financial-years.index') }}" class="{{ request()->routeIs('financial-years.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-calendar"></i>
                    </div>
                    <div class="nav_title">
                        <span>Financial Years</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('fund-allocations.*') ? 'active' : '' }}">
                <a href="{{ route('fund-allocations.index') }}" class="{{ request()->routeIs('fund-allocations.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-money"></i>
                    </div>
                    <div class="nav_title">
                        <span>Fund Allocations</span>
                    </div>
                </a>
            </li>
            @endif
            
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdministratorHQ())
            <h4 class="menu-text"><span>SYSTEM</span> <i class="fas fa-ellipsis-h"></i> </h4>
            <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-user"></i>
                    </div>
                    <div class="nav_title">
                        <span>Users</span>
                    </div>
                </a>
            </li>
            @endif
            
            @if(!auth()->user()->isDistrictUser())
            <h4 class="menu-text"><span>COMMITTEES</span> <i class="fas fa-ellipsis-h"></i> </h4>
            <li class="{{ request()->routeIs('local-zakat-committees.*') ? 'active' : '' }}">
                <a href="{{ route('local-zakat-committees.index') }}" class="{{ request()->routeIs('local-zakat-committees.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-layout-grid2"></i>
                    </div>
                    <div class="nav_title">
                        <span>Local Zakat Committees</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('lzc-members.*') ? 'active' : '' }}">
                <a href="{{ route('lzc-members.index') }}" class="{{ request()->routeIs('lzc-members.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-user"></i>
                    </div>
                    <div class="nav_title">
                        <span>LZC Members</span>
                    </div>
                </a>
            </li>
            @endif
            
            <h4 class="menu-text"><span>DISBURSEMENT</span> <i class="fas fa-ellipsis-h"></i> </h4>
            @if(!auth()->user()->isDistrictUser())
            <li class="{{ request()->routeIs('phases.*') ? 'active' : '' }}">
                <a href="{{ route('phases.index') }}" class="{{ request()->routeIs('phases.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-calendar"></i>
                    </div>
                    <div class="nav_title">
                        <span>Phases</span>
                    </div>
                </a>
            </li>
            @endif
            <li class="{{ request()->routeIs('beneficiary-applications.*') ? 'active' : '' }}">
                <a href="{{ route('beneficiary-applications.index') }}" class="{{ request()->routeIs('beneficiary-applications.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-file"></i>
                    </div>
                    <div class="nav_title">
                        <span>Beneficiary Applications</span>
                    </div>
                </a>
            </li>
            <li class="{{ request()->routeIs('beneficiaries.*') ? 'active' : '' }}">
                <a href="{{ route('beneficiaries.index') }}" class="{{ request()->routeIs('beneficiaries.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-id-badge"></i>
                    </div>
                    <div class="nav_title">
                        <span>Beneficiaries</span>
                    </div>
                </a>
            </li>
            
            @if(auth()->user()->isAdministratorHQ() || auth()->user()->isSuperAdmin())
            <li class="{{ request()->routeIs('jazzcash-response.*') ? 'active' : '' }}">
                <a href="{{ route('jazzcash-response.index') }}" class="{{ request()->routeIs('jazzcash-response.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-upload"></i>
                    </div>
                    <div class="nav_title">
                        <span>JazzCash Response</span>
                    </div>
                </a>
            </li>
            @endif
            
            @if(auth()->user()->isAdministratorHQ() || auth()->user()->isSuperAdmin() || auth()->user()->isDistrictUser())
            <li class="{{ request()->routeIs('admin-hq.all-cases') || request()->routeIs('admin-hq.pending') || request()->routeIs('admin-hq.approved-cases') || request()->routeIs('admin-hq.rejected-cases') || request()->routeIs('admin-hq.show-*') ? 'active' : '' }}">
                <a href="{{ route('admin-hq.all-cases') }}" class="{{ request()->routeIs('admin-hq.all-cases') || request()->routeIs('admin-hq.pending') || request()->routeIs('admin-hq.approved-cases') || request()->routeIs('admin-hq.rejected-cases') || request()->routeIs('admin-hq.show-*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-files"></i>
                    </div>
                    <div class="nav_title">
                        <span>Cases</span>
                    </div>
                </a>
            </li>
            @endif
            
            <h4 class="menu-text"><span>REPORTS</span> <i class="fas fa-ellipsis-h"></i> </h4>
            <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="ti-bar-chart"></i>
                    </div>
                    <div class="nav_title">
                        <span>Reports</span>
                    </div>
                </a>
            </li>
        </ul>
    </nav>
    <!--/ sidebar  -->
    
    <section class="main_content dashboard_part large_header_bg">
        <!-- menu  -->
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="header_iner d-flex justify-content-between align-items-center">
                        <div class="sidebar_icon d-lg-none">
                            <i class="ti-menu"></i>
                        </div>
                        <label class="form-label switch_toggle d-none d-lg-block" for="checkbox">
                            <input type="checkbox" id="checkbox">
                            <div class="slider round open_miniSide"></div>
                        </label>

                        <div class="header_right d-flex justify-content-between align-items-center">
                            <div class="header_notification_warp d-flex align-items-center">
                                <li>
                                    <div class="serach_button">
                                        <i class="ti-search"></i>
                                        <div class="serach_field-area d-flex align-items-center">
                                            <div class="search_inner">
                                                <form action="#">
                                                    <div class="search_field">
                                                        <input type="text" placeholder="Search here...">
                                                    </div>
                                                    <button class="close_search"> <i class="ti-search"></i> </button>
                                                </form>
                                            </div>
                                            <span class="f_s_14 f_w_400 ml_25 white_text text_white">Apps</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a class="bell_notification_clicker" href="#">
                                        <img src="{{ asset('assets/img/icon/bell.svg') }}" alt="">
                                        @php
                                            $unreadCount = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count() : 0;
                                        @endphp
                                        @if($unreadCount > 0)
                                        <span>{{ $unreadCount }}</span>
                                        @endif
                                    </a>
                                    <!-- Menu_NOtification_Wrap  -->
                                    <div class="Menu_NOtification_Wrap">
                                        <div class="notification_Header">
                                            <h4>Notifications</h4>
                                        </div>
                                        <div class="Notification_body">
                                            @if(auth()->check())
                                                @php
                                                    $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                                        ->orderBy('created_at', 'desc')
                                                        ->limit(6)
                                                        ->get();
                                                @endphp
                                                @forelse($notifications as $notification)
                                                <div class="single_notify d-flex align-items-center {{ !$notification->is_read ? 'unread-notification' : '' }}" style="cursor: pointer; {{ !$notification->is_read ? 'background-color: #fff3cd;' : '' }}" onclick="window.location.href='{{ route('notifications.show', $notification) }}'">
                                                    <div class="notify_thumb">
                                                        <a href="{{ route('notifications.show', $notification) }}" onclick="event.stopPropagation();"><img src="{{ asset('assets/img/staf/2.png') }}" alt=""></a>
                                                    </div>
                                                    <div class="notify_content">
                                                        <a href="{{ route('notifications.show', $notification) }}" onclick="event.stopPropagation();"><h5>{{ $notification->title }} @if(!$notification->is_read)<span class="badge bg-danger" style="font-size: 0.7em;">New</span>@endif</h5></a>
                                                        <p>{{ \Illuminate\Support\Str::limit($notification->message, 60) }}</p>
                                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                                @empty
                                                <div class="single_notify d-flex align-items-center">
                                                    <div class="notify_content">
                                                        <p class="text-center w-100">No notifications</p>
                                                    </div>
                                                </div>
                                                @endforelse
                                            @else
                                                <div class="single_notify d-flex align-items-center">
                                                    <div class="notify_content">
                                                        <p class="text-center w-100">No notifications</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="nofity_footer">
                                            <div class="submit_button text-center pt_20">
                                                <a href="{{ route('notifications.index') }}" class="btn_1">See More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Menu_NOtification_Wrap  -->
                                </li>
                            </div>
                            <div class="profile_info">
                                @php
                                    $user = auth()->user();
                                    $hasImage = $user->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->image);
                                @endphp
                                @if($hasImage)
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; font-weight: bold;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="profile_info_iner">
                                    <div class="profile_author_name">
                                        <p>{{ auth()->user()->role == 'super_admin' ? 'Super Admin' : (auth()->user()->role == 'administrator_hq' ? 'Administrator HQ' : 'District User') }}</p>
                                        <h5>{{ auth()->user()->name }}</h5>
                                    </div>
                                    <div class="profile_info_details">
                                        <a href="{{ route('profile.show') }}">My Profile</a>
                                        <a href="{{ route('settings.show') }}">Settings</a>
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ menu  -->
        
        <div class="main_content_iner">
            <div class="container-fluid p-0">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="dashboard_header mb_50">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="dashboard_header_title">
                                        <h3>@yield('page_title', 'Dashboard')</h3>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="dashboard_breadcam text-end">
                                        <p><a href="{{ route('dashboard') }}">Home</a> <i class="fas fa-caret-right"></i> @yield('breadcrumb', 'Dashboard')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        {{-- Bootstrap alerts hidden - using toast notifications instead --}}
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-none" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-none" role="alert" id="error-alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show d-none" role="alert" id="validation-errors-alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                    </div>
                </div>
                
                @yield('content')
            </div>
        </div>
    </div>
</section>
        
<!-- footer part -->
<div class="footer_part">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                <div class="footer_iner text-center">
                            <p>Powered By <a href="https://gbit.gov.pk" target="_blank">Information Technology Department GB</a> © {{ date('Y') }} All Rights Reserved</p>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery1-3.4.1.min.js') }}"></script>
    <!-- popper JS -->
    <script src="{{ asset('assets/js/popper1.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap1.min.js') }}"></script>
    <!-- metismenu JS -->
    <script src="{{ asset('assets/js/metisMenu.js') }}"></script>
    <!-- nice select JS -->
    <script src="{{ asset('assets/vendors/niceselect/js/jquery.nice-select.min.js') }}"></script>
    <!-- datepicker JS -->
    <script src="{{ asset('assets/vendors/datepicker/datepicker.js') }}"></script>
    <!-- datatable JS -->
    <script src="{{ asset('assets/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatable/js/dataTables.responsive.min.js') }}"></script>
    <!-- scrollabe  -->
    <script src="{{ asset('assets/vendors/scroll/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/scroll/scrollable-custom.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- custom JS -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    
    <script>
        // Global function for delete confirmation with SweetAlert
        function confirmDelete(event, message = 'Are you sure you want to delete this item?') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonColor: '#000000',
                cancelButtonText: 'Cancel',
                cancelButtonClass: 'swal2-cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            
            return false;
        }
        
        // Global function to show toast notifications
        function showToast(icon, title, message, timer = 4000) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: title,
                text: message,
                showConfirmButton: false,
                timer: timer,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        }
        
        // Convenience functions for common toast types
        function showSuccessToast(message, title = 'Success!') {
            showToast('success', title, message, 4000);
        }
        
        function showErrorToast(message, title = 'Error!') {
            showToast('error', title, message, 5000);
        }
        
        function showWarningToast(message, title = 'Warning!') {
            showToast('warning', title, message, 4000);
        }
        
        function showInfoToast(message, title = 'Info') {
            showToast('info', title, message, 4000);
        }
        
        // Show toast notifications from session messages
        $(document).ready(function() {
            // Success message toast
            @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            @endif
            
            // Error message toast
            @if(session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            @endif
            
            // Validation errors toast
            @if ($errors->any())
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Validation Error!',
                html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                showConfirmButton: false,
                timer: 6000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            @endif
            
            // Sidebar toggle - handle checkbox change
            $('#checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $(".sidebar").addClass("mini_sidebar");
                    $(".main_content").addClass("full_main_content");
                    $(".footer_part").addClass("full_footer");
                } else {
                    $(".sidebar").removeClass("mini_sidebar");
                    $(".main_content").removeClass("full_main_content");
                    $(".footer_part").removeClass("full_footer");
                }
            });
            
            // Notification toggle - ensure it works
            $('.bell_notification_clicker').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('.Menu_NOtification_Wrap').toggleClass('active');
            });
            
            // Close notification when clicking outside
            $(document).off('click.notification').on('click.notification', function(event) {
                if (!$(event.target).closest(".bell_notification_clicker, .Menu_NOtification_Wrap").length) {
                    $(".Menu_NOtification_Wrap").removeClass("active");
                }
            });
            
            // Search toggle
            $('.serach_button').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('.serach_field-area').addClass('active');
            });
            
            // Close search button
            $('.close_search').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('.serach_field-area').removeClass('active');
            });
            
            // Close search when clicking outside
            $(document).off('click.search').on('click.search', function(event) {
                if (!$(event.target).closest(".serach_button, .serach_field-area").length) {
                    $(".serach_field-area").removeClass("active");
                }
            });
            
            // Function to replace DataTables Previous/Next text with icons
            function updateDataTablesPagination() {
                // Replace Previous button
                $('.dataTables_wrapper .dataTables_paginate .paginate_button.previous').each(function() {
                    var $btn = $(this);
                    // Always replace with icon, regardless of current content
                    if (!$btn.find('i.ti-angle-left').length) {
                        $btn.html('<i class="ti-angle-left"></i>');
                    }
                });
                
                // Replace Next button
                $('.dataTables_wrapper .dataTables_paginate .paginate_button.next').each(function() {
                    var $btn = $(this);
                    // Always replace with icon, regardless of current content
                    if (!$btn.find('i.ti-angle-right').length) {
                        $btn.html('<i class="ti-angle-right"></i>');
                    }
                });
            }
            
            // Update pagination when DataTables are drawn
            $(document).on('draw.dt', function() {
                setTimeout(updateDataTablesPagination, 10);
            });
            
            // Also update after delays to catch initial render
            setTimeout(updateDataTablesPagination, 50);
            setTimeout(updateDataTablesPagination, 200);
            setTimeout(updateDataTablesPagination, 500);
            setTimeout(updateDataTablesPagination, 1000);
            
            // Use MutationObserver to watch for pagination changes
            if (typeof MutationObserver !== 'undefined') {
                var observer = new MutationObserver(function(mutations) {
                    var shouldUpdate = false;
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length > 0) {
                            for (var i = 0; i < mutation.addedNodes.length; i++) {
                                var node = mutation.addedNodes[i];
                                if (node.nodeType === 1) { // Element node
                                    if ($(node).hasClass('dataTables_paginate') || 
                                        $(node).find('.dataTables_paginate').length ||
                                        $(node).closest('.dataTables_paginate').length) {
                                        shouldUpdate = true;
                                        break;
                                    }
                                }
                            }
                        }
                    });
                    if (shouldUpdate) {
                        setTimeout(updateDataTablesPagination, 10);
                    }
                });
                
                // Observe changes to the document body
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
