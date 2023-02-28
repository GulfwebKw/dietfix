<?php $__env->startSection('otherheads'); ?>
    <link href='/packages/core/main.css' rel='stylesheet' />
    <link href='/packages/daygrid/main.css' rel='stylesheet' />
    <link href='/packages/timegrid/main.css' rel='stylesheet' />
    <style>
        .fc-highlight {
            background: green !important;
        }
        .fc-day-grid-event .fc-content {
            white-space: normal;
        }

        .loader {
            border: 4px solid #f3f3f3; /* Light grey */
            border-top: 4px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
        }

        @keyframes  spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contents'); ?>

        <div class="row">

            <div class="col-md-4">

            </div>
            <div class="col-md-2" align="center">
                <button style="background:#28a745;border-color:#28a745;color: white; height:30px;width: 30px" ></button><p style="margin-bottom: 10px"> Selected </p>
            </div>
            <div class="col-md-2" align="center">
                <button style="background:#17a2b8;border-color:#17a2b8;color: white; height:30px;width: 30px"></button><p style="margin-bottom: 10px">Not Selected</p>
            </div>

            <div class="col-md-4">

            </div>

        </div>
     

    <?php $__currentLoopData = $userDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="row">
            <?php $__currentLoopData = $collect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-sm-3 "  align="center" >
                    <h3>
                        <button   <?php if($day->isMealSelected==1): ?> style="background:#28a745;color: white" <?php else: ?> style="background-color:#17a2b8; color: white" <?php endif; ?>   data-day="<?php echo e($day->id); ?>" data-date="<?php echo e($day->date); ?>"  type="button" class="btn   showdate"><?php echo e(date('l', strtotime( $day->date))); ?> - <?php echo e($day->date); ?> </button>
                    </h3>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div class="modal fade" style=" overflow: auto !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div id="content-model" class="modal-body">

            </div>



        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_foot'); ?>
    <?php echo e(HTML::script('assets/js/jquery.validate.min.js')); ?>

    <script>

        $(function () {
            $(".showdate").click(function  (e) {
                e.preventDefault();
                var d = $(this).attr('data-day');

                $('#content-model').html("<div align='center'><div class=\"loader\"></div></div>");
                $('.modal').modal();

                $.get('/menu/order/listHtml/'+d, null, function(json, textStatus) {
                    $('#content-model').html(json);
                    $('.modal').modal();
                    $('.item-radio').click(function(){
                        $(this).parent().parent().find('.item-checks').prop('checked', false);
                    });
                });
            });


        });

    </script>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/menu/listDays.blade.php ENDPATH**/ ?>