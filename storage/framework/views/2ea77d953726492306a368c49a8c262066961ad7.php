<?php echo e(Form::open(array('route' => array('store.language')))); ?>

<div class="modal-body">

    <div class="row">
    <div class="form-group col-md-12">
        <?php echo e(Form::label('code', __('Language Code'),['class'=>'form-label'])); ?>


        <?php echo e(Form::text('code', '', array('class' => 'form-control','required'=>'required'))); ?>

        <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="invalid-code" role="alert">
            <strong class="text-danger"><?php echo e($message); ?></strong>
        </span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/u145602709/domains/hanfellas.com/public_html/PROJECT001/resources/views/lang/create.blade.php ENDPATH**/ ?>