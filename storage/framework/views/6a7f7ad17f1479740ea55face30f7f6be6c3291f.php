



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
            <form method="POST" action="/admin/users/save" accept-charset="UTF-8" class="form-horizontal form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data">
                <?php echo e(csrf_field()); ?>


                <div class="row">
                    <div class="control-group form-group  col-lg-6" id="username_holder">

                        <label for="username" class="control-label col-sm-6">User Name</label>

                        <div class="controls col-sm-6">

                            <input class="form-control" name="username"  type="text" id="username" value="<?php echo e(old('username')); ?>">

                        </div>

                    </div>

                    <div class="control-group form-group  col-lg-6" id="email_holder">
                        <label for="email" class="control-label col-sm-6">Email Address</label>

                        <div class="controls col-sm-6">
                            <input class="form-control" name="email" type="email"  id="email"  value="<?php echo e(old('email')); ?>">

                        </div>

                    </div>
                    <!--
                    <div class="clearfix"></div>

                    <div class="control-group form-group  col-lg-6" id="phone_holder">

                        <label for="phone" class="control-label col-sm-6">Phone No.</label>

                        <div class="controls col-sm-6">

                            <input class="form-control" name="phone" type="text"  id="phone" value="<?php echo e(old('phone')); ?>">

                        </div>

                    </div>
                    -->
                    <div class="control-group form-group  col-lg-6" id="mobile_number_holder">



                        <label for="mobile_number" class="control-label col-sm-6">Mobile</label>

                        <div class="controls col-sm-6">

                            <input class="form-control" name="mobile_number" type="text"  id="mobile_number" value="<?php echo e(old('mobile_number')); ?>">

                        </div>

                    </div>
                    
                    <!--<div class="clearfix"></div>-->


                    <div class="control-group form-group password-strength  col-lg-6" id="password_holder">

                        <label for="password" class="control-label col-sm-6">Password</label>

                        <div class="controls col-sm-6">

                            <input class="form-control" name="password" type="password" value="<?php echo e(old('password')); ?>" id="password">

                        </div>

                    </div>
                    <div class="control-group form-group password-strength  col-lg-6" id="password_holder">
                    </div>

                </div>


                <hr/>
                <br/>
                <div class="row">
                    <label for="province_id" class="control-label col-lg-12"> Weekdays address</label>
                </div>
                <hr/>
                <br/>






                <div class="row">
                    <div class="control-group form-group  col-lg-6" id="country_id_holder">
                        <label for="country_id" class="control-label col-sm-4">Country</label>
                        <div class="controls col-sm-8">
                            <select name="country_id" id="country_id" class=" chosen-select" >
                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($cou->id==old('country_id')): ?>  selected <?php endif; ?> value="<?php echo e($cou->id); ?>"><?php echo e($cou->titleEn); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group form-group  col-lg-6" id="province_id_holder">
                        <label for="province_id" class="control-label col-sm-4">Province</label>
                        <div class="controls col-sm-8">
                            <select name="province_id" id="province_id" class="nochosen">
                                <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($prov->id==old('province_id')): ?>  selected <?php endif; ?> value="<?php echo e($prov->id); ?>" ><?php echo e($prov->titleEn); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="control-group form-group  col-lg-6" id="area_id_holder">
                        <label for="area_id" class="control-label col-sm-4">Area</label>
                        <div class="controls col-sm-8">
                            <select name="area_id" id="area_id" class="chosen-select">
                                <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($area->id==old('area_id')): ?>  selected <?php endif; ?> value="<?php echo e($area->id); ?>" ><?php echo e($area->titleEn); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group form-group  col-lg-6" id="block_holder">
                        <label for="block" class="control-label col-sm-4">Block</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="block" type="text"  id="block"  value="<?php echo e(old('block')); ?>">
                        </div>
                    </div>
                </div>

              <div class="row">
                <div class="control-group form-group  col-lg-6" id="Street_holder">
                    <label for="block" class="control-label col-sm-4">Street</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="street" type="text"  id="street"  value="<?php echo e(old('street')); ?>">
                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="avenue_holder">
                    <label for="avenue" class="control-label col-sm-4">Avenue</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="avenue" type="text" id="avenue"  value="<?php echo e(old('avenue')); ?>">

                    </div>
                </div>
                </div>
                <div class="row">
                <div class="control-group form-group  col-lg-6" id="house_number_holder">
                    <label for="house_number" class="control-label col-sm-4">House Number</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="house_number" type="text"  id="house_number"  value="<?php echo e(old('house_number')); ?>">
                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="house_number_holder"></div>
                </div>
                <div class="row">
                <div class="control-group form-group  col-sm-6" id="address_holder">
                    <label for="address" class="control-label col-sm-2">Address</label>
                    <div class="controls col-sm-10">
                        <textarea class="form-control" name="address" cols="50" rows="10" id="address"><?php echo e(old('address')); ?></textarea>
                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" >
                    <label for="house_number_work" class="control-label  col-lg-6">Is Weekend Address Same</label>
                    <div class="controls col-lg-6">
                        <div class="switch" data-on="success" data-off="danger">
                            <?php echo e(Form::checkbox('is_weekend_address_same',1, null , array('class' => 'toggle','disabled'=>false))); ?>

                        </div>
                    </div>
                </div>
                </div>
           
                <hr/>
                <br/>

                <div class="row">
                    <label for="province_id" class="control-label col-lg-6">Weekend Address</label>
                </div>

                <hr/>
                <br/>

             <div class="row">
                <div class="control-group form-group  col-lg-6" id="country_work_id_holder">
                    <label for="country_work_id" class="control-label col-sm-4">Country</label>
                    <div class="controls col-sm-8">
                        <select name="country_work_id" id="country_work_id" class="chosen-select" style="display: none;">
                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($cou->id==old('country_work_id')): ?>  selected <?php endif; ?>  value="<?php echo e($cou->id); ?>"><?php echo e($cou->titleEn); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="province_work_id_holder">
                    <label for="province_work_id" class="control-label col-sm-4">Province</label>
                    <div class="controls col-sm-8">
                        <select name="province_work_id" id="province_work_id" class="nochosen">
                            <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($prov->id==old('province_work_id')): ?>  selected <?php endif; ?> value="<?php echo e($prov->id); ?>" ><?php echo e($prov->titleEn); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
              </div>
              <div class="row">  
                <div class="control-group form-group  col-lg-6" id="area_work_id_holder">
                    <label for="area_work_id" class="control-label col-sm-4">Area </label>
                    <div class="controls col-sm-8">
                        <select name="area_work_id" id="area_work_id" class="nochosen chosen-select">
                            <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($area->id==old('area_work_id')): ?>  selected <?php endif; ?> value="<?php echo e($area->id); ?>"  ><?php echo e($area->titleEn); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="block_work_holder">
                    <label for="block_work" class="control-label col-sm-4">Block</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="block_work" type="text"  id="block_work" value="<?php echo e(old('block_work')); ?>">
                    </div>
                </div>
                </div>

                <div class="control-group form-group  col-lg-6" id="Street_holder">
                    <label for="block" class="control-label col-sm-4">Street</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="street_work" type="text"  id="street" value="<?php echo e(old('street_work')); ?>">
                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="avenue_work_holder">
                    <label for="avenue_work" class="control-label col-sm-4">Avenue</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="avenue_work" type="text"  id="avenue_work" value="<?php echo e(old('avenue_work')); ?>">
                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="house_number_work_holder">

                    <label for="house_number_work" class="control-label col-sm-4">House Number</label>

                    <div class="controls col-sm-8">
                        <input class="form-control" name="house_number_work" type="text"  id="house_number_work" value="<?php echo e(old('house_number_work')); ?>">
                    </div>

                </div>

               <div class="control-group form-group  col-sm-6"></div>
                <div class="control-group form-group  col-sm-6" id="address_work_holder">


                    <label for="address_work" class="control-label col-sm-2">Address </label>

                    <div class="controls col-sm-10">
                        <textarea class="form-control" name="address_work" cols="50" rows="10" id="address_work"><?php echo e(old('address_work')); ?></textarea>
                    </div>

                </div>
<br clear="both"/>
                 <hr/>

                <div class="col-sm-12" align="center">
                    <b>Other</b>
                </div>
<br clear="both"/>
                <hr/>
                <br/>
                <input name="role_id" type="hidden" value="1" >


                <div class="control-group form-group  col-lg-6" id="bm_holder">
                    <label for="bm" class="control-label col-sm-4">BM</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="bm"  type="text" id="bm" value="<?php echo e(old('bm')); ?>">
                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="clinic_id_holder">

                    <label for="clinic_id" class="control-label col-sm-4">Clinic</label>

                    <div class="controls col-sm-8">



                        <select name="clinic_id" id="clinic_id" class="chosen-select" style="display: none;">
                            <?php $__currentLoopData = $clinics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clinic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($clinic->id==old('clinic_id')): ?>  selected <?php endif; ?>  value="<?php echo e($clinic->id); ?>"><?php echo e($clinic->titleEn); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>


                    </div>


                </div>
                <div class="control-group form-group  col-lg-6" id="doctor_id_holder">
                    <label for="doctor_id" class="control-label col-sm-4">Doctor</label>
                    <div class="controls col-sm-8">
                        <select name="doctor_id" id="doctor_id" class="chosen-select" style="display: none;">
                            <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($doctor->id==old('doctor_id')): ?>  selected <?php endif; ?> value="<?php echo e($doctor->id); ?>"  ><?php echo e($doctor->username); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>


                    </div>

                </div>
                <div class="control-group form-group  col-lg-6" id="sex_holder">
                    <label for="sex" class="control-label col-sm-4">Sex</label>
                    <div class="controls col-sm-8">
                        <select class="chosen-select" id="sex" name="sex" style="display: none;">
                            <?php $__currentLoopData = $sex; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($k==old('sex')): ?>  selected <?php endif; ?> value="<?php echo e($k); ?>" > <?php echo e($val); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>
                    </div>


                </div>
                <div class="control-group form-group  col-lg-6" id="salt_holder">
                    <label for="salt" class="control-label col-sm-4">Salt</label>

                    <div class="controls col-sm-8">
                        <select class="chosen-select" id="salt" name="salt" style="display: none;">
                            <?php $__currentLoopData = $salt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($k==old('salt')  || $k=="Medium Salt"): ?>  selected <?php endif; ?> value="<?php echo e($k); ?>"  > <?php echo e($val); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>





                </div>
                <div class="control-group form-group  col-lg-6" id="delivery_holder">
                    <label for="delivery" class="control-label col-sm-4">Delivery</label>
                    <div class="controls col-sm-8">
                        <select class="chosen-select" id="delivery" name="delivery" style="display: none;">
                            <?php $__currentLoopData = $delivery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($k==old('delivery')): ?>  selected <?php endif; ?> value="<?php echo e($k); ?>"   > <?php echo e($val); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>


                <div class="control-group form-group  col-lg-6" id="height_holder">

                    <label for="height" class="control-label col-sm-4">Height</label>

                    <div class="controls col-sm-8">

                        <input class="form-control" name="height" type="text"  id="height"  value="<?php echo e(old('height')); ?>">

                    </div>

                </div>
                <div class="control-group form-group  col-lg-6" id="weight_holder">
                    <label for="weight" class="control-label col-sm-4">Weight</label>
                    <div class="controls col-sm-8">

                        <input class="form-control" name="weight" type="text"  id="weight"  value="<?php echo e(old('weight')); ?>">

                    </div>

                </div>

                <div class="control-group form-group  col-lg-6" id="bmi_holder">

                    <label for="bmi" class="control-label col-sm-4">BMI</label>

                    <div class="controls col-sm-8">

                        <input class="form-control" name="bmi"  type="text" id="bmi"  value="<?php echo e(old('bmi')); ?>">

                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="date_t_holder">
                    <label for="date_t" class="control-label col-sm-4">Date of Birth</label>
                    <div class="controls col-sm-8">

                        <input class="form-control" name="date_t" type="date" id="date_t"  value="<?php echo e(old('date_t')); ?>">

                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="loss_holder">

                </div>
                <div class="control-group form-group  col-lg-6" id="created_at_holder">


                    <label for="created_at" class="control-label col-sm-4">Created Date</label>

                    <div class="controls col-sm-8">

                        <div class="form-control"></div>

                    </div>
                </div>
                <div class="control-group form-group  col-lg-6" id="updated_at_holder">

                    <label for="updated_at" class="control-label col-sm-4">Updated Date</label>
                    <div class="controls col-sm-8">
                        <div class="form-control"></div>
                    </div>

                </div>
                <div class="control-group form-group  col-lg-6" id="standard_menu_id_holder">
                    <label for="standard_menu_id" class="control-label col-sm-4">Standard Menu</label>
                    <div class="controls col-sm-8">

                        <select name="standard_menu_id" id="standard_menu_id" class="chosen-select" style="display: none;">
                            <?php $__currentLoopData = $standard_menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($val->id==old('standard_menu_id')): ?>  selected <?php endif; ?> value="<?php echo e($val->id); ?>"   > <?php echo e($val->titleEn); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                    </div>


                </div>
                <div class="control-group form-group  col-lg-6" id="active_holder">
                    <label for="active" class="control-label col-sm-4">Active</label>
                    <div class="controls col-sm-8">
                        <div class="switch" data-on="success" data-off="danger">

                            <?php echo e(Form::checkbox('active',1, null , array('class' => 'toggle'))); ?>


                        </div>

                    </div>


                </div>

                <div class="control-group form-group  col-lg-6" id="demo_holder">
                    <label for="demo" class="control-label col-sm-4">Is Demo</label>
                    <div class="controls col-sm-8">
                        <div class="switch" data-on="success" data-off="danger">
                            <?php echo e(Form::checkbox('is_demo',1, null , array('class' => 'toggle'))); ?>

                        </div>
                    </div>
                </div>


                <div class="control-group form-group  col-sm-12" id="note_holder">
                    <label for="note" class="control-label col-sm-2">Note</label>

                    <div class="controls col-sm-10">

                        <textarea class="form-control" name="note" cols="50" rows="10" id="note"> <?php echo e(old('note')); ?></textarea>

                    </div>

                </div>
                <input name="id" type="hidden" >

                <hr>
                <br>
                <div class="row">
                    <label for="province_id" class="control-label col-lg-6"> Renew/Add Membership</label>
                </div>
                <hr/>
                <br/>

                <div class="row">
                    <div class="control-group form-group col-sm-12" >

                        <?php echo e(Form::label('Package','Package' , array('class' => 'control-label col-sm-4'))); ?>


                        <div class="controls col-sm-4">
                            <select  id="new_package_id" name="package_id" class="form-control"  >
                                <option selected value="0">None</option>
                                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pack): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($pack->id==old('package_id')): ?>  selected <?php endif; ?> value="<?php echo e($pack->id); ?>" ><?php echo e($pack->titleEn); ?></option>
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
                                    <option <?php if($duration->id==old('package_duration_id')): ?>  selected <?php endif; ?>  value="<?php echo e($duration->id); ?>"><?php echo e($duration->titleEn); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="controls col-sm-4">
                            <?php echo e(Form::label('Attach Day','Attach Day' , array('class' => 'control-label col-sm-4'))); ?>

                            <div class="controls col-sm-8">
                                <input type="checkbox"  name="attach_day" value="1" class="form-control " <?php if(old('attach_day')=="1"): ?> checked="checked" <?php endif; ?>>
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

                <div class="control-group form-group col-lg-12" >
                   
                    <label for="count_day" class="control-label col-sm-4">CountDay:</label>
                    <div class="controls col-sm-2">
                        <input class="form-control" name="count_day" type="number" max="365" min="0" value="0" id="count_day">
                    </div>
                </div>

<br/>
                <hr/>
                <br/>

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

<?php echo $__env->make('admin.forms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/user/add.blade.php ENDPATH**/ ?>