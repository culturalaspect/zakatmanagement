

<?php $__env->startSection('title', config('app.name') . ' - Notifications'); ?>
<?php $__env->startSection('page_title', 'Notifications'); ?>
<?php $__env->startSection('breadcrumb', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="white_card mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">My Notifications</h3>
                    </div>
                    <div class="header_more_tool">
                        <?php
                            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                        ?>
                        <?php if($unreadCount > 0): ?>
                        <form action="<?php echo e(route('notifications.mark-all-read')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti-check"></i> Mark All as Read
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <?php if($notifications->count() > 0): ?>
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
                            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e(!$notification->is_read ? 'table-warning' : ''); ?>">
                                <td>
                                    <?php if(!$notification->is_read): ?>
                                        <span class="badge bg-danger" title="Unread">New</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary" title="Read">Read</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($notification->title); ?></strong>
                                </td>
                                <td>
                                    <p class="mb-0"><?php echo e(\Illuminate\Support\Str::limit($notification->message, 100)); ?></p>
                                </td>
                                <td>
                                    <?php
                                        $typeLabels = [
                                            'phase_created' => 'Phase Created',
                                            'phase_updated' => 'Phase Updated',
                                            'phase_status_changed' => 'Phase Status Changed',
                                            'phase_deleted' => 'Phase Deleted',
                                            'beneficiary_approved' => 'Beneficiary Approved',
                                            'beneficiary_rejected' => 'Beneficiary Rejected',
                                        ];
                                        $typeLabel = $typeLabels[$notification->type] ?? ucfirst(str_replace('_', ' ', $notification->type));
                                    ?>
                                    <span class="badge bg-info"><?php echo e($typeLabel); ?></span>
                                </td>
                                <td>
                                    <?php echo e($notification->created_at->format('d M Y, H:i')); ?>

                                    <br>
                                    <small class="text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                                </td>
                                <td>
                                    <div class="action_btns d-flex">
                                        <?php if($notification->notifiable_type && $notification->notifiable_id): ?>
                                        <a href="<?php echo e(route('notifications.show', $notification)); ?>" class="action_btn mr_10" title="View Details">
                                            <i class="ti-eye"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if(!$notification->is_read): ?>
                                        <form action="<?php echo e(route('notifications.mark-read', $notification)); ?>" method="POST" class="d-inline mr_10">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="action_btn" title="Mark as Read">
                                                <i class="ti-check"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        <form action="<?php echo e(route('notifications.destroy', $notification)); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete(event, 'Are you sure you want to delete this notification?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="action_btn" title="Delete">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <?php echo e($notifications->links()); ?>

                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    <i class="ti-info-alt"></i> No notifications found.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#notificationsTable').DataTable({
            responsive: true,
            order: [[4, 'desc']], // Sort by date descending
            paging: false, // Disable DataTables pagination since we're using Laravel pagination
            info: false,
            searching: true,
        });
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\wheat\wheat\wheat_distribution\zakat_beneficiaries\laravel_project\resources\views/notifications/index.blade.php ENDPATH**/ ?>