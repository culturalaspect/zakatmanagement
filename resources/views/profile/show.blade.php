@extends('layouts.app')

@section('title', config('app.name') . ' - My Profile')
@section('page_title', 'My Profile')
@section('breadcrumb', 'My Profile')

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
                        <h3 class="m-0">Profile Information</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" disabled>
                        </div>
                        @if($user->district)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">District</label>
                            <input type="text" class="form-control" value="{{ $user->district->name }}" disabled>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
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
                        <h3 class="m-0">Account Information</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="text-center mb-4">
                    @if($user->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->image))
                        <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="profile-avatar" style="width: 120px; height: 120px; margin: 0 auto; border-radius: 50%; object-fit: cover; border: 3px solid #667eea;">
                    @else
                        <div class="profile-avatar" style="width: 120px; height: 120px; margin: 0 auto; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 48px; color: white; font-weight: bold;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-0">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                    @if($user->district)
                    <p class="text-muted mb-0">{{ $user->district->name }}</p>
                    @endif
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label text-muted">Email</label>
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Member Since</label>
                    <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

