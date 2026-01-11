@extends('layouts.app')

@section('title', config('app.name') . ' - Settings')
@section('page_title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Change Password</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('settings.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" required>
                            @error('current_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Security Tips</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="ti-lock text-primary me-2"></i>
                        <strong>Use a strong password</strong>
                        <p class="text-muted small mb-0">Include uppercase, lowercase, numbers, and special characters</p>
                    </li>
                    <li class="mb-3">
                        <i class="ti-reload text-primary me-2"></i>
                        <strong>Change regularly</strong>
                        <p class="text-muted small mb-0">Update your password every 90 days for better security</p>
                    </li>
                    <li class="mb-3">
                        <i class="ti-shield text-primary me-2"></i>
                        <strong>Keep it private</strong>
                        <p class="text-muted small mb-0">Never share your password with anyone</p>
                    </li>
                    <li>
                        <i class="ti-key text-primary me-2"></i>
                        <strong>Unique password</strong>
                        <p class="text-muted small mb-0">Don't reuse passwords from other accounts</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

