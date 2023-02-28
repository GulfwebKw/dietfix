


<?php $__env->startSection('forms2'); ?>
    <?php echo e(HTML::style('cpassets/plugins/bootstrap-datepicker/css/datepicker.css')); ?>


    <input type="hidden" value="<?php echo e($user->id); ?>" name="user_id"  />
    <input type="hidden" value="<?php echo e($user->package_duration_id); ?>" id="user_duration">

    <div class="row">
        <div class="control-group form-group col-sm-12" >

            <?php echo e(Form::label('User Package    ','User Package' , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">
                <p> <?php if(isset($user->package)): ?> <?php echo e($user->package->titleEn); ?> <?php else: ?> ------ <?php endif; ?> </p>
            </div>



        </div>
        <div class="control-group form-group col-sm-12" >

            <?php echo e(Form::label('User Package Duration','Package Duration' , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">
                <p> <?php if(isset($user->packageDuration)): ?> <?php echo e($user->packageDuration->titleEn); ?> <?php else: ?> ------ <?php endif; ?> </p>

            </div>



        </div>
        <div class="control-group form-group col-sm-12" >

            <?php echo e(Form::label('Start Package','Start Package' , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">
                <p><?php if(isset($user->membership_start)): ?><?php echo e($user->membership_start); ?> <?php else: ?>  ------ <?php endif; ?></p>

            </div>

        </div>
        <div class="control-group form-group col-sm-12" >

            <?php echo e(Form::label('End Package','End Package' , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">
                <p><?php if(isset($user->membership_end)): ?> <?php echo e($user->membership_end); ?> <?php else: ?>  ------ <?php endif; ?></p>
            </div>



        </div>
        <div class="control-group form-group col-sm-12" >

            <?php echo e(Form::label(' Remaining Days',' Remaining Days' , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">
                <p> <?php echo e($remind_day); ?>  </p>

            </div>

        </div>
    </div>
    <br/><hr/><hr/>
    <div class="row">
        <div class="control-group form-group col-sm-12" >

            <?php echo e(Form::label('Package','Package' , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">
                    <select  id="new_package_id" name="package_id" class="form-control"  >
                        <option selected value="0">None</option>
                        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if($user->package_id==$item->id): ?>  selected <?php endif; ?> value="<?php echo e($item->id); ?>"><?php echo e($item->titleEn); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
            </div>
        </div>
        <div class="control-group form-group col-sm-12" >

            <?php echo e(Form::label('Package Duration','Package Duration' , array('class' => 'control-label col-sm-4'))); ?>

            <div class="controls col-sm-4">
                <select  id="new_package_duration_id" name="package_duration_id"   class="form-control"  >
                      <option  selected value="none" >None</option>
                        <?php $__currentLoopData = $packageDuration; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $duration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option <?php if($user->package_duration_id==$duration->id): ?>  selected <?php endif; ?> value="<?php echo e($duration->id); ?>"><?php echo e($duration->titleEn); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="controls col-sm-4">
                <?php echo e(Form::label('Attach Day','Attach Day' , array('class' => 'control-label col-sm-2'))); ?>

                <div class="controls col-sm-10">
                   <input type="checkbox" checked name="attach_day" value="1" class="form-control ">
                </div>

            </div>
        </div>
        <div class="control-group form-group col-sm-12" >

            <?php echo e(Form::label('Starting Date','Starting Date' , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <div class="input-append">

                    <?php echo e(Form::text ('starting_date','', array('class' => 'form-control form_datetime','readonly' => ''))); ?>


                    <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                    <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                </div>
            </div>
        </div>
    </div>

    <?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>
    ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##
    <?php echo e(HTML::script('js/moment.js')); ?>

    <?php echo e(HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')); ?>



    <script>
        jQuery(document).ready(function($) {
            $(".blue").remove();
            $(".green").remove();


            if($("#new_package_id").val()!=0){
                $.ajax({
                    type: "POST",
                    url:"/admin/package/durations",
                    data: {id:$("#new_package_id").val(),userDurationId:$("#user_duration").val()}
                }).done(function( msg ) {
                    if(msg.result) {
                        $("#new_package_duration_id").html(msg.view);
                    }
                });
            }

            $(".form_datetime").datepicker({
                format: "yyyy-mm-dd",
                pickerPosition:"bottom-right" ,
                language: "en",
                todayBtn: true,
                todayHighlight: true
            }).on('changeDate', function(e){
                $(this).datepicker('hide');
            });

            $("#new_package_id").on("change",function () {
                var packageId=$(this).val();
                $.ajax({
                    type: "POST",
                    url:"/admin/package/durations",
                    data: {id:packageId,userDurationId:$("#user_duration").val()}
                }).done(function( msg ) {
                    if(msg.result) {
                      $("#new_package_duration_id").html(msg.view);
                    }
                });
            });

            $('.form_datetime').on('changeDate', function(ev){
                $(this).datepicker('hide');
            });
        });


      function changePackage(){
           // alert('sdsd');
        }
    </script>


     <script>

        function checkboxes () {

            $('#days-table').delegate('.check-date','click',function(ee) {
                var last_row = $('#days-table tbody tr').last();
                var last_row_date = last_row.find('input.check-date').val();
                if($(this).is(':checked')) {
                    var current_row_date = moment(last_row_date,'YYYY-MM-DD').subtract(1,'days').format("YYYY-MM-DD");
                    $('#membership_end').val(current_row_date);
                    last_row.remove();
                } else {
                    var current_row_date = moment(last_row_date,'YYYY-MM-DD').add(1,'days').format("YYYY-MM-DD");
                    var current_row_date_with_week = moment(last_row_date,'YYYY-MM-DD').add(1,'days').format("dddd YYYY-MM-DD");
                    $('#membership_end').val(current_row_date);
                    var row = '<tr><td><input type="checkbox" checked="checked" class="check-date" name="dates[]" value="' + current_row_date + '" id="dates' + current_row_date + '"></td><td>' + current_row_date_with_week + '</td></tr>'
                    $('#days-table tbody').append(row);
                }
            });
        }
        jQuery(document).ready(function($) {
            $('#monthes').change(function () {

                // Add Expire Date
                var number = $(this).val();
                var membership_start = $('#membership_start').val();
                // var days = 28 * parseInt(number);
                var days = number;
                var membership_end = moment(membership_start, "YYYY-MM-DD").add(days,'days').format("YYYY-MM-DD");
                $('#membership_end').val(membership_end);


                // Get Date Diff
                var moment_start = moment(membership_start,'YYYY-MM-DD');
                var moment_end = moment(membership_end,'YYYY-MM-DD');
                var days_between = moment_end.diff(moment_start, 'days');

                $('#days-table tbody').html('');
                for (var i = 0; i < days_between; i++) {
                    var current_row_date = moment(membership_start,'YYYY-MM-DD').add(i,'days').format("YYYY-MM-DD");
                    var current_row_date_with_week = moment(membership_start,'YYYY-MM-DD').add(i,'days').format("dddd YYYY-MM-DD");
                    var row = '<tr><td><input type="checkbox" checked="checked" class="check-date" name="dates[]" value="' + current_row_date + '" id="dates' + current_row_date + '"></td><td>' + current_row_date_with_week + '</td></tr>'
                    $('#days-table tbody').append(row);
                };
            });
            checkboxes();
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.forms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/user/renewOrAddMemberShip.blade.php ENDPATH**/ ?>