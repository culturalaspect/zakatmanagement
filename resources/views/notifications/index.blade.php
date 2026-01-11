@extends('layouts.app')

@section('title', config('app.name') . ' - Notifications')
@section('page_title', 'Notifications')
@section('breadcrumb', 'Notifications')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">My Notifications</h3>
                    </div>
                    <div class="header_more_tool">
                        @php
                            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="ti-check"></i> Mark All as Read
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                @if($notifications->count() > 0)
                <div class="table-responsive">
                    <table class="table" id="notificationsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Status</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                            <tr class="{{ !$notification->is_read ? 'table-warning' : '' }}">
                                <td data-read-status="{{ $notification->is_read ? 'read' : 'unread' }}">
                                    @if(!$notification->is_read)
                                        <span class="badge bg-danger" title="Unread">New</span>
                                    @else
                                        <span class="badge bg-secondary" title="Read">Read</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $notification->title }}</strong>
                                </td>
                                <td>
                                    <p class="mb-0">{{ \Illuminate\Support\Str::limit($notification->message, 100) }}</p>
                                </td>
                                <td>
                                    @php
                                        $typeLabels = [
                                            'phase_created' => 'Phase Created',
                                            'phase_updated' => 'Phase Updated',
                                            'phase_status_changed' => 'Phase Status Changed',
                                            'phase_deleted' => 'Phase Deleted',
                                            'beneficiary_approved' => 'Beneficiary Approved',
                                            'beneficiary_rejected' => 'Beneficiary Rejected',
                                        ];
                                        $typeLabel = $typeLabels[$notification->type] ?? ucfirst(str_replace('_', ' ', $notification->type));
                                    @endphp
                                    <span class="badge bg-info">{{ $typeLabel }}</span>
                                </td>
                                <td>
                                    {{ $notification->created_at->format('d M Y, H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="action_btns d-flex">
                                        @if($notification->notifiable_type && $notification->notifiable_id)
                                        <a href="{{ route('notifications.show', $notification) }}" class="action_btn mr_10" title="View Details">
                                            <i class="ti-eye"></i>
                                        </a>
                                        @endif
                                        @if(!$notification->is_read)
                                        <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="d-inline mr_10">
                                            @csrf
                                            <button type="submit" class="action_btn" title="Mark as Read">
                                                <i class="ti-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event, 'Are you sure you want to delete this notification?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action_btn" title="Delete">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
                @else
                <div class="alert alert-info">
                    <i class="ti-info-alt"></i> No notifications found.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
@endpush

@push('scripts')
<!-- DataTables Buttons JS -->
<script src="{{ asset('assets/vendors/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/jszip.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatable/js/vfs_fonts.js') }}"></script>

<script>
    $(document).ready(function() {
        var table = $('#notificationsTable').DataTable({
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
                    filename: 'notifications-' + new Date().toISOString().split('T')[0],
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
                    filename: 'notifications-' + new Date().toISOString().split('T')[0],
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
                    filename: 'notifications-' + new Date().toISOString().split('T')[0],
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
            order: [[4, 'desc']], // Sort by date descending
            paging: false, // Disable DataTables pagination since we're using Laravel pagination
            info: false,
            searching: true,
        });
    });
</script>
@endpush

