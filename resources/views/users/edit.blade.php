@extends('layouts.app')

@section('title', config('app.name') . ' - Edit User')
@section('page_title', 'Edit User')
@section('breadcrumb', 'Edit User')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit User</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{ route('users.update', $user) }}" method="POST">
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
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" minlength="8">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave blank to keep current password. Minimum 8 characters if changing.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" minlength="8">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control" required {{ auth()->user()->isAdministratorHQ() && !auth()->user()->isSuperAdmin() && $user->role === 'super_admin' ? 'disabled' : '' }}>
                                <option value="">Select Role</option>
                                @if(auth()->user()->isSuperAdmin())
                                    <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="administrator_hq" {{ old('role', $user->role) === 'administrator_hq' ? 'selected' : '' }}>Administrator HQ</option>
                                @endif
                                <option value="district_user" {{ old('role', $user->role) === 'district_user' ? 'selected' : '' }}>District User</option>
                            </select>
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @if(auth()->user()->isAdministratorHQ() && !auth()->user()->isSuperAdmin())
                                <small class="text-muted">You can only assign district user role.</small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3" id="districtField" style="display: none;">
                            <label class="form-label">District <span class="text-danger">*</span></label>
                            <select name="district_id" id="district_id" class="form-control">
                                <option value="">Select District</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id', $user->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('district_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#role').on('change', function() {
            if ($(this).val() === 'district_user') {
                $('#districtField').show();
                $('#district_id').prop('required', true);
            } else {
                $('#districtField').hide();
                $('#district_id').prop('required', false);
                $('#district_id').val('');
            }
        });

        // Trigger on page load if current role is district_user
        if ($('#role').val() === 'district_user') {
            $('#districtField').show();
            $('#district_id').prop('required', true);
        }
    });
</script>
@endpush
@endsection


