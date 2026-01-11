@extends('layouts.app')

@section('title', config('app.name') . ' - User Details')
@section('page_title', 'User Details')
@section('breadcrumb', 'User Details')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">User Details</h3>
                    </div>
                    <div class="header_more_tool">
                        @if(!(auth()->user()->isAdministratorHQ() && !auth()->user()->isSuperAdmin() && $user->role === 'super_admin'))
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                                <i class="ti-pencil"></i> Edit
                            </a>
                        @endif
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p class="mb-0">{{ $user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Role:</strong>
                        <p class="mb-0">
                            @if($user->role === 'super_admin')
                                <span class="badge bg-danger">Super Admin</span>
                            @elseif($user->role === 'administrator_hq')
                                <span class="badge bg-warning">Administrator HQ</span>
                            @elseif($user->role === 'district_user')
                                <span class="badge bg-info">District User</span>
                            @else
                                <span class="badge bg-secondary">{{ $user->role }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>District:</strong>
                        <p class="mb-0">{{ $user->district ? $user->district->name : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Created At:</strong>
                        <p class="mb-0">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mb-0">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


