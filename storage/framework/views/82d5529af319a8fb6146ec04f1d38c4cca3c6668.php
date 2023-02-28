<?php $__env->startSection('custom_head_include'); ?>

    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js')); ?>

    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js')); ?>

    <?php echo e(HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css')); ?>

    <?php echo e(HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css')); ?>

    <?php echo e(HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css')); ?>

    <?php echo e(HTML::style('cpassets/plugins/bootstrap-datepicker/css/datepicker.css')); ?>



    <?php echo e(HTML::style('cpassets/css/pqgrid.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/pqgrid.min.js')); ?>



    <?php if(!empty($customJS)): ?>
        <?php echo e(HTML::script($customJS)); ?>

    <?php endif; ?>


    <?php if($_adminLang == 'arabic'): ?>

        <?php echo e(HTML::style('cpassets/plugins/data-tables/DT_bootstrap_rtl.css')); ?>


    <?php else: ?>

        <?php echo e(HTML::style('cpassets/plugins/data-tables/DT_bootstrap.css')); ?>


    <?php endif; ?>

<?php $__env->stopSection(); ?>







<?php $__env->startSection('content'); ?>

    <?php if(isset($errors)): ?>
        <?php if($errors->count()>=1): ?>
            <div class="alert alert-block alert-danger fade in">
                <ul>
                    <?php $__currentLoopData = $errors->all('<li>:message</li>'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $message; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>



    <div class="container-fluid">

        <!-- BEGIN PAGE HEADER-->

        <div class="row-fluid">
            <form method="POST" action="/admin/discount/save" accept-charset="UTF-8" class="form-horizontal form-bordered form-row-stripped spaceForm" role="form">
                <?php echo e(csrf_field()); ?>

                <input name="id" type="hidden" value="<?php echo e($item->id); ?>">

                <div class="row">


                    <div class="control-group form-group  col-sm-6" id="username_holder">

                        <label for="username" class="control-label col-sm-4">Arabic Title</label>

                        <div class="controls col-sm-8">

                            <input class="form-control" name="titleAr"  type="text" id="titleAr" value="<?php echo e(optional($item)->titleAr); ?>">

                        </div>

                    </div>


                    <div class="control-group form-group  col-sm-6" id="username_holder">

                        <label for="username" class="control-label col-sm-4">English Title</label>

                        <div class="controls col-sm-8">

                            <input class="form-control" name="titleEn"  type="text" id="titleEn" value="<?php echo e(optional($item)->titleEn); ?>">

                        </div>

                    </div>



                    <div class="control-group form-group  col-sm-6" id="username_holder">
                        <label for="username" class="control-label col-sm-4">Discount Code</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="code"  type="text" id="code" value="<?php echo e(optional($item)->code); ?>">
                        </div>
                    </div>


                    <div class="control-group form-group  col-sm-6" id="username_holder">
                        <label for="username" class="control-label col-sm-4">Value</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="value"  type="text" id="value" value="<?php echo e(optional($item)->value); ?>">
                        </div>
                    </div>


                    <div class="control-group form-group  col-sm-6" id="username_holder">
                        <label for="username" class="control-label col-sm-4">All Limitation</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="count_limit"  type="text" id="count_limit" value="<?php echo e(optional($item)->count_limit); ?>">
                        </div>
                    </div>


                    <div class="control-group form-group  col-sm-6" id="username_holder">
                        <label for="username" class="control-label col-sm-4">User Limitation</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="count_limit_user"  type="text" id="count_limit_user" value="<?php echo e(optional($item)->count_limit_user); ?>">
                        </div>
                    </div>

                    <div class="control-group form-group  col-sm-6" id="country_id_holder">
                        <label for="country_id" class="control-label col-sm-4">Type</label>
                        <div class="controls col-sm-8">
                            <select name="type" id="type" class=" chosen-select" >
                                <option <?php if("Percent"==optional($item)->type): ?>  selected <?php endif; ?> value="Percent">Percent</option>
                                <option <?php if("KD"==optional($item)->type): ?>  selected <?php endif; ?> value="KD">KD</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group form-group col-sm-6" ></div>

                    <div class="control-group form-group col-sm-6" >

                        <?php echo e(Form::label('Starting Date','Starting Date' , array('class' => 'control-label col-sm-4'))); ?>


                        <div class="controls col-sm-8">

                            <div class="input-append">

                                <?php echo e(Form::text ('start',optional($item)->start, array('class' => 'form-control form_datetime','readonly' => ''))); ?>


                                <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                                <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                            </div>
                        </div>
                    </div>

                    <div class="control-group form-group col-sm-6" >

                        <?php echo e(Form::label('End Date','End Date' , array('class' => 'control-label col-sm-4'))); ?>


                        <div class="controls col-sm-8">

                            <div class="input-append">

                                <?php echo e(Form::text ('end',optional($item)->end, array('class' => 'form-control form_datetime','readonly' => ''))); ?>


                                <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                                <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                            </div>
                        </div>
                    </div>





                    <div class="control-group form-group  col-sm-6" id="active_holder">
                        <label for="active" class="control-label col-sm-4">Active</label>
                        <div class="controls col-sm-8">
                            <div class="switch" data-on="success" data-off="danger">

                                <?php echo e(Form::checkbox('active',1, optional($item)->active , array('class' => 'toggle'))); ?>


                            </div>

                        </div>


                    </div>



                </div>


                <div class="form-actions">
                    <button type="submit" name="save_next" value="1" class="btn col-md-3 green"><i class="fa fa-arrow-right"></i> Save And Next</button>
                    <button type="submit" class="btn col-md-3 yellow"><i class="fa fa-check"></i> Save</button>
                    <button type="submit" name="save_return" value="1" class="btn col-md-3 blue"><i class="fa fa-reply"></i> Save &amp; Return</button>
                    <button type="submit" name="save_new" value="1" class="btn col-md-3 green"><i class="fa fa-plus"></i> Save &amp; Ne</button>
                </div>


            </form>

        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_foot'); ?>

    ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##
    <?php echo e(HTML::script('js/moment.js')); ?>

    <?php echo e(HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')); ?>



    <script>

        CKEDITOR_BASEPATH  = "<?php echo e(url('cpassets/plugins/ckeditor')); ?>/";

        $(function () {
            $(".chosen-select").chosen();



            $(".form_datetime").datepicker({
                format: "yyyy-mm-dd",
                pickerPosition:"bottom-right" ,
                language: "en",
                todayBtn: true,
                todayHighlight: true
            }).on('changeDate', function(e){
                $(this).datepicker('hide');
            });

            // $("#new_package_id").on("change",function () {
            //     var packageId=$(this).val();
            //     $.ajax({
            //         type: "POST",
            //         url:"/admin/package/durations",
            //         data: {id:packageId,userDurationId:$("#user_duration").val()}
            //     }).done(function( msg ) {
            //         if(msg.result) {
            //             $("#new_package_duration_id").html(msg.view);
            //         }
            //     });
            // });

            // $('.form_datetime').on('changeDate', function(ev){
            //     $(this).datepicker('hide');
            // });

            $(".form_datetime").datepicker({
                format: "yyyy-mm-dd",
                pickerPosition:"bottom-right" ,
                language: "en",
                todayBtn: true,
                todayHighlight: true
            }).on('changeDate', function(e){
                $(this).datepicker('hide');
            });

        });

    </script>

    <?php echo e(HTML::script('/cpassets/plugins/ckeditor/ckeditor.js')); ?>


    <?php echo e(HTML::script('/cpassets/plugins/ckeditor/adapters/jquery.js')); ?>


    <?php echo e(HTML::script('/cpassets/plugins/ckeditor/own.js')); ?>






<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.forms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/discount/edit.blade.php ENDPATH**/ ?>