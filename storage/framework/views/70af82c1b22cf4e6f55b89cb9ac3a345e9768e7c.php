
<?php $__env->startSection('forms2'); ?>
<style>
    .modal { overflow: auto !important; }
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
    <input type="hidden" value="<?php echo e($user->id); ?>" name="user_id"  />

    <?php if($userDate->count()>=1): ?>
        <div class="row" >
            <div class="col-md-2" style="margin-top:15px;">
                <div class="control-group form-group col-sm-3 center-block "  align="center" >
                    <button style="background-color:#ff422b; color: white" data-package="<?php echo e($user->package_id); ?>" data-user="<?php echo e($user->id); ?>" type="button" class="btn   cancel_day">Cancel All Day Package </button>

                </div>
            </div>
            <div class="col-md-2" style="margin-top:15px;">
                <div class="control-group form-group col-sm-3 center-block"  style="margin-right:10px;" align="center" >
                    <button style="background-color:#ff675d; color: white" data-package="<?php echo e($user->package_id); ?>" data-user="<?php echo e($user->id); ?>" type="button" class="btn   cancel_active_day">Cancel All Active Day Package </button>
                </div>

            </div>
            <div class="col-md-8">
                <div class="control-group form-group password-strength  " id="password_holder">
                    <label for="count_day" class="control-label col-sm-4">CountDay:</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="count_day" type="number" max="365" min="1" value="1" id="count_day">
                        <button type="button" data-package="<?php echo e($user->package_id); ?>" data-user="<?php echo e($user->id); ?>" id="add_day" name="save_new" style="background:#28a745" value="1" class="btn col-md-4 red"><i class="fa fa-plus"></i> Add</button>
                    </div>

                </div>
            </div>
        </div>
        <br/>
        <hr/>
    <?php endif; ?>


    <?php $__currentLoopData = $userDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="row">
            <?php $__currentLoopData = $collect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="control-group form-group col-sm-3 center-block"  align="center" >

                        <h3>
                            <button  <?php if($day->date<$firstValidDay): ?> disabled  style="background: #9d261d;color: white"  <?php endif; ?>  <?php if($day->isMealSelected==1): ?> style="background:#28a745;color: white" <?php else: ?> style="background-color:#17a2b8; color: white" <?php endif; ?>   data-day="<?php echo e($day->id); ?>" data-date="<?php echo e($day->date); ?>" data-package="<?php echo e($user->package_id); ?>" data-user="<?php echo e($user->id); ?>" type="button" class="btn   showdate"><?php echo e(date('l', strtotime( $day->date))); ?> - <?php echo e($day->date); ?> </button>
                        </h3>
                       <a href="#"><i class="fa fa-remove remove_day" data-date="<?php echo e($day->date); ?>" data-user="<?php echo e($user->id); ?>"  style="color: red">Delete</i></a>

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
    ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

    <script>
        jQuery(document).ready(function($) {
            $(".blue").remove();
            $(".green").remove();
            $(".yellow").remove();


            $(".showdate").click(function  (e) {
                e.preventDefault();
                var p = $(this).attr('data-package');
                var d = $(this).attr('data-day');
                var u = $(this).attr('data-user');
                $('#content-model').html("<div align='center'><div class=\"loader\"></div></div>");
                $('.modal').modal();

                $.get('/admin/users/order/'+p+'/'+d+'/'+u, null, function(json, textStatus) {
                    $('#content-model').html(json);
                     $('.modal').modal();
                    $('.item-radio').click(function(){
                        $(this).parent().parent().find('.item-checks').prop('checked', false);
                    });
                });
            });

        });
        $(function () {
            $(".cancel_day").on("click",function () {
                var r = confirm("Are you sure?");
                if (r == true) {
                    $.ajax({
                        type: "GET",
                        url:  '<?php echo e(url($menuUrl.'/cancelDays')); ?>'+"/"+$(this).attr('data-user'),
                        data: "",
                        dataType: "json",
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(msg){
                            location.reload();
                        },
                        error: function(msg){
                            alert("Error found !!!");
                        }
                    });
                }
            });

            $(".cancel_active_day").on("click",function () {
                var r = confirm("Are you sure?");
                if (r == true) {
                    $.ajax({
                        type: "GET",
                        url:  '<?php echo e(url($menuUrl.'/cancelActiveDays')); ?>'+"/"+$(this).attr('data-user'),
                        data: "",
                        dataType: "json",
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(msg){
                            location.reload();
                        },
                        error: function(msg){
                            alert("Error found !!!");
                        }
                    });
                }
            });
            $(".remove_day").on("click",function () {
                var r = confirm("Are you sure?");
                if (r == true) {
                    $.ajax({
                        type: "GET",
                        url:  '<?php echo e(url($menuUrl.'/cancelSingleDays')); ?>'+"/"+$(this).attr('data-user')+"/"+$(this).attr("data-date"),
                        data: "",
                        dataType: "json",
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(msg){
                            location.reload();
                        },
                        error: function(msg){
                            alert("Error found !!!");
                        }
                    });
                }
            });
            $("#add_day").on("click",function () {
                var i= $("#count_day").val();
                if(i >= 1){
                    var packId=$(this).attr("data-package");
                    var user=$(this).attr("data-user");
                   if(packId>=1){
                       $.ajax({
                           type: "POST",
                           url:"/admin/users/addDay",
                           data: {countDay:i,userId:user}
                       }).done(function( msg ) {
                           if(msg.result) {
                               window.location.href="/admin/users/orders/"+user;
                           }
                       });

                   }else{
                       alert("Please first Add Membership");
                   }

                }else{
                    alert("Please enter a number greater than zero ")
                }

            });

        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.forms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/user/orders.blade.php ENDPATH**/ ?>