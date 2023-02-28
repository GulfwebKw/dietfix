
<?php $__env->startSection('custom_head_include'); ?>
    <?php echo e(HTML::style('assets/datepicker/css/datepicker.css')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_foot'); ?>
    <?php echo e(HTML::script('http://momentjs.com/downloads/moment.js')); ?>

    <?php echo e(HTML::script('assets/datepicker/js/bootstrap-datepicker.js')); ?>


    <script>
        var date = $('#date').datepicker({
            onRender: function(date) {
                return '';
                // return (date.valueOf() >= moment()) ? '' : 'disabled';

            },
            format: 'yyyy-mm-dd'
        }).on('changeDate', function(ev) {
            date.hide();
        }).data('datepicker');

    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
    <?php echo e(Form::open(array('url' => url('kitchen/get-countWithCities'), 'method' => 'get', 'class' => 'form-inline form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form'))); ?>


    <?php echo e(Form::label('date', trans('main.Date'))); ?>

    <div class="control-group form-group">
        <div class="controls">
            <?php echo e(Form::text('date', null, array('class' => 'datepicker form-control','id' => 'date'))); ?>

        </div>
    </div>


    <div class="control-group form-group">
        <?php echo e(Form::button('<i class="fa fa-search"></i>', array('class' => 'btn btn-primary','id' => 'submit','type' => 'submit'))); ?>

    </div>

    <?php echo e(Form::close()); ?>

    <div class="page">
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th colspan="1">#</th>
                    <th colspan="1">Area</th>
                    <th colspan="1">Count Reservation</th>
                </tr>
                <?php if(empty($orders)): ?>
                    <tr><td colspan="4"><?php echo e(trans('main.No Results')); ?></td></tr>
                <?php else: ?>
                    <?php  $i=0;  ?>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area=>$count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td colspan="1"><?php echo e(++$i); ?></td>
                            <td colspan="1"><?php echo e($area); ?></td>
                            <td colspan="1"><?php echo e($count); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <hr>
        <br>
    </div>

    <div class="page-break"></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/kitchen/areaReport.blade.php ENDPATH**/ ?>