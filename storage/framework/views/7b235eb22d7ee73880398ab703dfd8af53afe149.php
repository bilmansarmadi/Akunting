<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Invoice Uniquip')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Invoice Uniquip')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create constant custom field')): ?>
                <a href="#" data-url="<?php echo e(route('InvoiceUniquip.create')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create Invoice Uniquip')); ?>" class="btn btn-sm btn-primary">
                    <i class="ti ti-plus"></i>
                </a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Nomor Invoice')); ?></th>
                                <th> <?php echo e(__('Nomor Referensi')); ?></th>
                                <th> <?php echo e(__('Tipe Invoice')); ?></th>
                                <th> <?php echo e(__('Tanggal Invoice')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $custom_fields['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                            <tr>
                                    <td class="Id">
                                        <a href="" class="btn btn-outline-primary"><?php echo e($field['nomor_invoice']); ?></a>
                                    </td>
                                    <td><?php echo e($field['no_referensi']); ?></td>
                                    <td><?php echo e($field['tipe_invoice']); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($field['tanggal_invoice'])->format('d/m/Y')); ?></td>
                                   
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u145602709/domains/lubirastudios.com/public_html/Project001/resources/views/InvoiceUniquip/index.blade.php ENDPATH**/ ?>