


<?php $__env->startSection('forms2'); ?>
    <?php echo e(HTML::style('cpassets/plugins/bootstrap-datepicker/css/datepicker.css')); ?>


    <input type="hidden" value="<?php echo e($user->id); ?>" name="user_id"  />
    <div class="row">
        <div class="col-sm-12">
            <label for="" class="control-label col-sm-3">Count Freeze Days: </label>
            <div class="controls col-sm-8">
                <div class="input-append">
                    <label for="" class="control-label col-sm-3"> <?php echo e($countFreeze); ?></label>
                </div>
            </div>
        </div>


        <div class="control-group form-group col-sm-12" >
            <?php echo e(Form::label('From Date','From Date' , array('class' => 'control-label col-sm-4'))); ?>

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
    <?php echo e(HTML::script('http://momentjs.com/downloads/moment.js')); ?>

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

<?php echo $__env->make('admin.forms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/user/unFreezeDay.blade.php ENDPATH**/ ?>