



<?php $__env->startSection('content'); ?>

<?php $__env->startSection('custom_foot'); ?>

    ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

    <script>

        $('.control-group.col-sm-6').each(function (i) {

            var z = i + 1;

            if(z % 2 == 0) {

                $(this).after('<div class="clearfix"></div>');

            }

        });

        $('.control-group.col-sm-4').each(function (i) {

            if((i+1) % 3 == 0) {

                $(this).after('<div class="clearfix"></div>');

            }

        });

        var control = $(".control-group.form-group:not(.col-sm-6,.col-sm-4)");

        $(control).find('label.col-sm-4').removeClass('col-sm-4').addClass('col-sm-2');

        $(control).find('div.col-sm-8').removeClass('col-sm-8').addClass('col-sm-10');

    </script>

<?php $__env->stopSection(); ?>


<?php if($errors->count()>=1): ?>

    <div class="alert alert-block alert-danger fade in">

        <ul>

            <?php $__currentLoopData = $errors->all('<li>:message</li>'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php echo $message; ?>


            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </ul>

    </div>

<?php endif; ?>


<?php if(session()->has('message')): ?>
    <div class="alert alert-<?php echo e(session()->get('status')); ?>">
        <?php echo e(session()->get('message')); ?>

    </div>
<?php endif; ?>

<?php if($uploadable): ?>

    <?php echo e(Form::open(array('url' => $url, 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form'))); ?>


<?php else: ?>

    <?php echo e(Form::open(array('url' => $url, 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form'))); ?>


<?php endif; ?>



<?php echo $__env->yieldContent('forms1'); ?>



<?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>



    <?php $name = $field['name']; ?>

    <?php $val = Input::old($name); ?>



    <?php if(isset($val) && !empty($val)): ?>

        <?php $value = $val; ?>

    <?php elseif(isset($item->$name)): ?>

        <?php $value = $item->$name; ?>

    <?php elseif(isset($field['value'])): ?>

        <?php $value = $field['value']; ?>

    <?php else: ?>

        <?php $value = null; ?>

    <?php endif; ?>





    <div class="control-group form-group <?php if($field['type'] == 'password'): ?>password-strength <?php endif; ?> <?php if(isset($field['col'])): ?>col-sm-<?php echo e((12/$field['col'])); ?><?php endif; ?>" id="<?php echo e($field['name']); ?>_holder">





        <?php if($field['type'] == 'text'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <?php echo e(Form::text($field['name'] , $value , array('class' => 'form-control'))); ?>


            </div>



        <?php elseif($field['type'] == 'textarea'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <?php echo e(Form::textarea ($field['name'] , $value , array('class' => 'form-control'))); ?>


            </div>



        <?php elseif($field['type'] == 'div'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <div class="form-control autoheight"><?php echo e($value); ?></div>

            </div>



        <?php elseif($field['type'] == 'color'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <div class="input-append color colorpicker-default" data-color="<?php echo e($value); ?>" data-color-format="hex">

                    <?php echo e(Form::text ($field['name'] , $value , array('class' => 'form-control colorpicker'))); ?>


                    <span class="add-on"><i style="background-color: <?php echo e($value); ?>;"></i></span>

                </div>

            </div>



            <?php if(!isset($colorLoadedBefore)): ?>

        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <?php echo e(HTML::script('cpassets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js')); ?>


            <script>$('.colorpicker-default').colorpicker();</script>

        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $colorLoadedBefore = true; ?>

        <?php endif; ?>





        <?php elseif($field['type'] == 'many2many'): ?>

            <?php $parent_id = (isset($field['parent_id'])) ? $field['parent_id'] : 'id'; ?>

            <?php $parent_title = $field['parent_title']; ?>

            <?php $fName = $field['name']; ?>

            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <select id="<?php echo e($fName); ?>" multiple="multiple" class="form_many_2_many">

                    <?php if(isset($item)): ?>

                        <?php $value = optional($item->$fName)->pluck('id')->toArray(); ?>

                    <?php endif; ?>

                    <?php $__currentLoopData = $field['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php if(!$value): ?>

                            <option value="<?php echo e($i->$parent_id); ?>"><?php echo e($i->$parent_title); ?></option>

                        <?php else: ?>

                            <option value="<?php echo e($i->$parent_id); ?>" <?php if(in_array($i->$parent_id,$value)): ?> selected="selected" <?php endif; ?>><?php echo e($i->$parent_title); ?></option>

                        <?php endif; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>

            </div>



            <?php if(!isset($many2ManyBefore)): ?>

        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <?php echo e(HTML::style('cpassets/plugins/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css')); ?>


            <?php echo e(HTML::script('cpassets/plugins/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js')); ?>


            <?php echo e(HTML::script('cpassets/plugins/bootstrap-multiselect-master/dist/js/bootstrap-multiselect-collapsible-groups.js')); ?>


        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $many2ManyBefore = true; ?>

        <?php endif; ?>



        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <script>

                jQuery(document).ready(function($) {

                    $("#<?php echo e($fName); ?>").multiselect({

                        maxHeight: 200,

                        checkboxName: '<?php echo e($field['name']); ?>[]',

                        buttonContainer: '<div class="btn-group btn-block" />',

                        buttonClass: 'btn btn-block',

                        disableIfEmpty: true,

                        dropRight: true,

                        delimiterText: ', ',

                        includeSelectAllOption: true,

                        enableFiltering: true,

                        enableClickableOptGroups: true,

                        enableCaseInsensitiveFiltering: true,

                        nonSelectedText: '<?php echo e(trans('main.Choose')); ?>',

                        allSelectedText: '<?php echo e(trans('main.All')); ?>',

                        selectAllText: '<?php echo e(trans('main.All')); ?>',

                        filterPlaceholder: '<?php echo e(trans('main.Search')); ?>'

                    });

                });

            </script>

        <?php $__env->stopSection(); ?>



        <?php elseif($field['type'] == 'datetime'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <div class="input-append">

                    <?php echo e(Form::text ($field['name'] , $value , array('class' => 'form-control form_datetime', 'readonly' => ''))); ?>


                    <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                    <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                </div>

            </div>



            <?php if(!isset($datetimeLoadedBefore)): ?>

        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <?php echo e(HTML::style('cpassets/plugins/bootstrap-datetimepicker/css/datetimepicker.css')); ?>


            <?php echo e(HTML::script('cpassets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')); ?>


            <script>

                jQuery(document).ready(function($) {

                    $(".form_datetime").datetimepicker({

                        // isRTL: App.isRTL(),

                        format: "dd-mm-yyyy hh:ii",

                        pickerPosition:"bottom-right" ,

                        language: "en"

                    });

                });

            </script>

        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $datetimeLoadedBefore = true; ?>

        <?php endif; ?>



        <?php elseif($field['type'] == 'time'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <div class="input-append">

                    <?php echo e(Form::text ($field['name'] , $value , array('class' => 'form-control form_time', 'readonly' => ''))); ?>


                    <span class="add-on"><i class="icon-time fa fa-clock-o "></i></span>

                </div>

            </div>



            <?php if(!isset($timeLoadedBefore)): ?>

        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <?php echo e(HTML::style('cpassets/plugins/bootstrap-timepicker/compiled/timepicker.css')); ?>


            <?php echo e(HTML::script('cpassets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js')); ?>


            <script>

                jQuery(document).ready(function($) {

                    $(".form_time").timepicker({

                        // showSeconds: true,

                        showMeridian: false

                    });

                });

            </script>

        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $timeLoadedBefore = true; ?>

        <?php endif; ?>



        <?php elseif($field['type'] == 'datetimesql'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <div class="input-append">

                    <?php echo e(Form::text ($field['name'] , $value , array('class' => 'form-control form_datetime', 'readonly' => ''))); ?>


                    <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                    <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                </div>

            </div>



            <?php if(!isset($datetimesqlLoadedBefore)): ?>

        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <?php echo e(HTML::style('cpassets/plugins/bootstrap-datetimepicker/css/datetimepicker.css')); ?>


            <?php echo e(HTML::script('cpassets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')); ?>


            <script>

                jQuery(document).ready(function($) {

                    $(".form_datetime").datetimepicker({

                        // isRTL: App.isRTL(),

                        format: "yyyy-mm-dd hh:ii:ss",

                        pickerPosition:"bottom-right" ,

                        language: "en",

                        todayBtn: true,

                        todayHighlight: true

                    });

                });

            </script>

        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $datetimesqlLoadedBefore = true; ?>

        <?php endif; ?>



        <?php elseif($field['type'] == 'date'): ?>



            <?php if(!isset($dateLoadedBefore)): ?>



        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <?php echo e(HTML::style('cpassets/plugins/bootstrap-datepicker/css/datepicker.css')); ?>


            <?php echo e(HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')); ?>


            <script>

                jQuery(document).ready(function($) {

                    $(".form_date.date").datepicker({

                        format: "yyyy-mm-dd",

                        todayBtn: "linked",

                        orientation: "auto left",

                        autoclose: true,

                        todayHighlight: true

                    });

                });

            </script>

        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $dateLoadedBefore = true; ?>

        <?php endif; ?>



        <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


        <div class="controls col-sm-8">

            <div class="input-append date form_date">

                <?php echo e(Form::text ($field['name'] , $value , array('class' => 'form-control form_date', 'data-date' => $value, 'data-date-format' => 'yyyy-mm-dd' ,'data-date-viewmode' => 'years'))); ?>


                <span class="add-on"><i class="icon-calendar fa fa-calendar"></i></span>

            </div>

        </div>



        <?php elseif($field['type'] == 'email'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <?php echo e(Form::email($field['name'] , $value , array('class' => 'form-control'))); ?>


            </div>





        <?php elseif($field['type'] == 'number'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <?php echo e(Form::input('number', $field['name'] , $value , array('class' => 'form-control'))); ?>


            </div>



        <?php elseif($field['type'] == 'password'): ?>



            <?php if(!isset($passwordLoadedBefore)): ?>

        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <?php echo e(HTML::script('cpassets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js')); ?>


        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $passwordLoadedBefore = true; ?>

        <?php endif; ?>



        <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


        <div class="controls col-sm-8">

            <?php echo e(Form::password($field['name'], array('class' => 'form-control'))); ?>


        </div>



        <?php elseif($field['type'] == 'hidden'): ?>



            <?php echo e(Form::hidden($field['name'] , $value)); ?>




        <?php elseif($field['type'] == 'switcher'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">



                <div class="switch" data-on="success" data-off="danger">

                    <?php echo e(Form::checkbox($field['name'] , 1, ($value) ? true : null , array('class' => 'toggle'))); ?>


                </div>

            </div>



        <?php elseif($field['type'] == 'radio'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <?php echo e(Form::radio($field['name'], $value )); ?>


            </div>



        <?php elseif($field['type'] == 'radios'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <?php $__currentLoopData = $field['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php echo e(Form::radio($field['name'],$k)); ?> <?php echo e($v); ?>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>



        <?php elseif($field['type'] == 'select'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <?php if(isset($field['chained'])): ?>

                    <select name="<?php echo e($field['name']); ?>" id="<?php echo e($field['name']); ?>" class="chosen">
                      
                        <?php $__currentLoopData = $field['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e(isset($field['select_key'])?$row->{$field['select_key']} : $row->id); ?>" class="<?php echo e($row->{$field['chained']}); ?>">

                                <?php echo e(dd($row->{$field['select_value']})); ?>


                            </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                    <?php if(!isset($chainedLoadedBefore)): ?>

                <?php $__env->startSection('custom_foot'); ?>

                    ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

                    <?php echo e(HTML::script('cpassets/js/jquery.chained.min.js')); ?>


                    <script>

                        $("#<?php echo e($field['name']); ?>").chained("#<?php echo e($field['chained']); ?>");

                        $('#<?php echo e($field['name']); ?>, #<?php echo e($field['chained']); ?>').on('change', function(){

                            $('#<?php echo e($field['name']); ?>, #<?php echo e($field['chained']); ?>').trigger('liszt:updated');

                        });

                    </script>

                <?php $__env->stopSection(); ?>

                <?php else: ?>



                <?php endif; ?>

                <?php else: ?>

                    <?php if(isset($field['noChosen'])): ?>
                        <?php if(array_key_exists('valOptions',$field)): ?>

                            <select name="<?php echo e($field['name']); ?>" id="<?php echo e($field['name']); ?>" class="nochosen">

                                <?php if(isset($field['data'])): ?>
                                    <?php $__currentLoopData = $field['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemS): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php if($itemS[$field['valOptions']]==$value): ?>  selected <?php endif; ?> value="<?php echo e($itemS[$field['valOptions']]); ?>"><?php echo e($itemS[$field['keyOptionsSelect']]); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php endif; ?>

                            </select>

                        <?php else: ?>
                            <?php echo Form::select($field['name'], @$field['data'] , $value, array('class' => 'nochosen')); ?>



                        <?php endif; ?>


                    <?php else: ?>

                        <?php if($field['valOptions']=='otherType'): ?>


                            <?php echo Form::select($field['name'], @$field['data'] , $value, array('class' => 'chosen')); ?>


                        <?php elseif($field['valOptions']=='keyVal'): ?>
                            <select name="<?php echo e($field['name']); ?>" id="<?php echo e($field['name']); ?>" class="chosen">


                                <?php if(isset($field['data'])): ?>
                                    <?php $__currentLoopData = $field['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemKey=>$itemVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php if($itemKey==$value): ?>  selected <?php endif; ?> value="<?php echo $itemKey; ?>"><?php echo $itemVal; ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                            </select>

                        <?php else: ?>
                            <select name="<?php echo e($field['name']); ?>" id="<?php echo e($field['name']); ?>" class="chosen">

                                <?php if(isset($field['data'])): ?>
                                    <?php $__currentLoopData = $field['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemS): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(is_array($itemS)): ?>
                                            <option <?php if($itemS[$field['valOptions']]==$value): ?>  selected <?php endif; ?> value="<?php echo e($itemS[$field['valOptions']]); ?>"><?php echo e($itemS[$field['keyOptionsSelect']]); ?></option>
                                        <?php else: ?>
                                            <option <?php if($itemS->{$field['valOptions']}==$value): ?>  selected <?php endif; ?> value="<?php echo e($itemS->{$field['valOptions']}); ?>"><?php echo e($itemS->{$field['keyOptionsSelect']}); ?></option>

                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php endif; ?>

                            </select>

                        <?php endif; ?>





                    <?php endif; ?>

                <?php endif; ?>

            </div>



        <?php elseif($field['type'] == 'multiSelect'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <?php echo e(Form::select($field['name'].'[]', @$field['data'] , $value, array('class' => 'multiple-select', 'multiple' => true))); ?>


            </div>

        <?php elseif($field['type'] == 'rating'): ?>
            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>

            <div class="controls col-sm-8">
                <select name="<?php echo e($field['name']); ?>" class="example_rating">
                    <?php $__currentLoopData = $field['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php if($value==$itemd): ?> selected <?php endif; ?>  value="<?php echo e($itemd); ?>"><?php echo e($itemd); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>


        <?php elseif($field['type'] == 'selectRange'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <?php echo e(Form::selectRange($field['name'], $field['start'], $field['end'])); ?>


            </div>



        <?php elseif($field['type'] == 'file'): ?>

            <?php if(!isset($fileLoadedBefore)): ?>



        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##



            <?php echo e(HTML::style('/cpassets/uploadifive/uploadifive.css')); ?>


            <?php echo e(HTML::script('/cpassets/uploadifive/jquery.uploadifive.min.js')); ?>




            <script>

                $('#<?php echo e($field['name']); ?>').uploadifive({

                    'auto' : true,

                    <?php if($field['multi']): ?>

                    'multi' : true,

                    <?php else: ?>

                    'multi' : false,

                    <?php endif; ?>

                    'formData' : {

                        'timestamp' : '<?php echo e(time()); ?>',

                        'token' : '<?php echo e(md5('SecUre!tN0w' . time())); ?>',

                        'folder' : 'media/<?php echo e($field['folder']); ?>/',

                        'fileExt' : '<?php echo e(isset($field['ext']) ? $field['ext'] : 'jpeg,jpg,bmp,gif,png'); ?>',

                    },

                    'queueID' : 'queue<?php echo e($field['name']); ?>',

                    'uploadScript' : '<?php echo e(url('/upload_files')); ?>',

                    'onUploadComplete' : function(file, data) {

                        var rname=data;

                        var fullpath= '<?php echo e(url("resize?w=30&h=30&src=media/".$field['folder'])); ?>'+"/"+rname;

                        var link= '<?php echo e(url("resize?w=500&r=1&src=media/".$field['folder'])); ?>'+"/"+rname;



                        // Photo Handling Vars

                                <?php if($field['photo']): ?>

                        var linkhrefstart = '<a class="fancybox" href="' + link + '">';

                        var linkhrefend = '</a>';

                                <?php else: ?>

                        var linkhrefstart = '';

                        var linkhrefend = '';

                        <?php endif; ?>



                        // Multi Field Name Handling

                                <?php if($field['multi']): ?>

                        var fieldname = '<?php echo e($field['name']); ?>[]';

                                <?php else: ?>

                        var fieldname = '<?php echo e($field['name']); ?>';

                        <?php endif; ?>



                        // Paragraph Setup

                        var p = '';

                        var p=p+'<div class="uploadedImg">';

                        var p=p+'<input type="checkbox" checked="checked" value="'+rname+'" name="' + fieldname + '" >';

                        var p=p+linkhrefstart;

                        var p=p+'<img src="'+fullpath+'">';

                        var p=p+linkhrefend;

                        var p=p+'<br /><small>'+rname+"</small>";

                        var p=p+'</div>';



                        <?php if($field['multi']): ?>

                        $('#<?php echo e($field['name']); ?>-thumbnails').append(p);

                        <?php else: ?>

                        $('#<?php echo e($field['name']); ?>-thumbnails').html(p);

                        <?php endif; ?>



                        $('.mesg').fadeOut('slow');	}

                });

                <?php if($field['photo']): ?>

                $('.fancybox').attr('rel', 'media-gallery').fancybox();

                <?php endif; ?>



            </script>

        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $fileLoadedBefore = true; ?>

        <?php endif; ?>



        <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


        <div class="controls col-sm-8">

            <?php if($field['multi']): ?>

                <?php echo e(Form::file($field['name'] , array('multiple' => 'true'))); ?>


            <?php else: ?>

                <?php echo e(Form::file($field['name'])); ?>


            <?php endif; ?>

            <div id="<?php echo e($field['name']); ?>-thumbnails">

                <?php if(!empty($value)): ?>

                    <?php if($field['multi']): ?>

                        <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="uploadedImg">

                                <input type="checkbox" checked="checked" value="<?php echo e($v); ?>" name="<?php echo e($field['name']); ?>[]" >

                                <?php if($field['photo']): ?>

                                    <a href="<?php echo e(url('resize?w=500&r=1&src=media/'.$field['folder'].'/'.$v)); ?>" class="fancybox">

                                        <?php endif; ?>

                                        <img src="<?php echo e(url('resize?w=30&h=30&src=media/'.$field['folder'].'/'.$v)); ?>">

                                        <?php if($field['photo']): ?>

                                    </a>

                                <?php endif; ?>

                                <br />

                                <small><?php echo e($v); ?></small>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php else: ?>

                        <div class="uploadedImg">

                            <input type="checkbox" checked="checked" value="<?php echo e($value); ?>" name="<?php echo e($field['name']); ?>" >

                            <?php if($field['photo']): ?>

                                <a href="<?php echo e(url('resize?w=500&r=1&src=media/'.$field['folder'].'/'.$value)); ?>" class="fancybox">

                                    <?php endif; ?>

                                    <img src="<?php echo e(url('resize?w=30&h=30&src=media/'.$field['folder'].'/'.$value)); ?>">

                                    <?php if($field['photo']): ?>

                                </a>

                            <?php endif; ?>

                            <br />

                            <small><?php echo e($value); ?></small>

                        </div>

                    <?php endif; ?>

                <?php endif; ?>

            </div>

            <div class="clearfix"></div>

            <div id="queue<?php echo e($field['name']); ?>" class="help-inline"></div>

            <div class="mesg">

                <?php echo app('translator')->getFromJson('main.Please wait untill upload completing otherwise the photo will not appear'); ?>

            </div>



        </div>



        <?php elseif($field['type'] == 'timestampDisplay'): ?>



            <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


            <div class="controls col-sm-8">

                <div class="form-control"><?php echo e($value); ?></div>

            </div>



        <?php elseif($field['type'] == 'wysiwyg'): ?>



            <?php if(!isset($tinymceLoadedBefore)): ?>



        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <?php echo e(HTML::script('/cpassets/plugins/tinymce/js/tinymce/tinymce.min.js')); ?>


            <?php echo e(HTML::script('/cpassets/plugins/tinymce/js/tinymce/jquery.tinymce.min.js')); ?>


        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $tinymceLoadedBefore = true; ?>

        <?php endif; ?>



        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <script>

                jQuery(document).ready(function($) {

                    $("#<?php echo e($field['name']); ?>").tinymce({

                        selector: "textarea",
                        plugins: [

                            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",

                            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",

                            "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"

                        ],
                        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
                        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
                        menubar: false,
                        toolbar_items_size: 'small',
                        style_formats: [

                            {title: 'Bold text', inline: 'b'},

                            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},

                            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                            {title: 'Example 1', inline: 'span', classes: 'example1'},
                            {title: 'Example 2', inline: 'span', classes: 'example2'},
                            {title: 'Table styles'},
                            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}

                        ],



                        templates: [

                            {title: 'Slideshow Content', content: '<p>Insert Info Here</p><a href="#" class="but">Insert Url Here</a>'},

                            {title: 'Test', content: 'Test'}

                        ],
                        forced_root_block : 'p'

                    });



                });

            </script>

        <?php $__env->stopSection(); ?>



        <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


        <div class="controls col-sm-8">

            <?php echo e(Form::textarea($field['name'] , $value , array('class' => 'form-control wysiwyg editor', 'id' => $field['name']))); ?>


        </div>



        <?php elseif($field['type'] == 'wysiwyg2'): ?>



            <?php if(!isset($ckeditorLoadedBefore)): ?>



        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <script>

                CKEDITOR_BASEPATH  = "<?php echo e(url('cpassets/plugins/ckeditor')); ?>/";

            </script>

            <?php echo e(HTML::script('/cpassets/plugins/ckeditor/ckeditor.js')); ?>


            <?php echo e(HTML::script('/cpassets/plugins/ckeditor/adapters/jquery.js')); ?>


            <?php echo e(HTML::script('/cpassets/plugins/ckeditor/own.js')); ?>




        <?php $__env->stopSection(); ?>

        <?php else: ?>

            <?php $ckeditorLoadedBefore = true; ?>

        <?php endif; ?>



        <?php $__env->startSection('custom_foot'); ?>

            ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##

            <script>

                jQuery(document).ready(function($) {

                    $("#<?php echo e($field['name']); ?>").ckeditor({

                        toolbarGroups: CKEDITOR_Style<?php echo e((isset($field['size'])) ? $field['size'] : 1); ?>


                    });



                });

            </script>

        <?php $__env->stopSection(); ?>





        <?php echo e(Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4'))); ?>


        <div class="controls col-sm-8">

            <?php echo e(Form::textarea($field['name'] , $value , array('class' => 'form-control wysiwyg editor', 'id' => $field['name']))); ?>


        </div>





        <?php endif; ?>



    </div>



<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<?php echo $__env->yieldContent('forms2'); ?>




<?php if(isset($item->$_pk)): ?>
    <?php echo e(Form::hidden($_pk, $item->$_pk)); ?>

<?php endif; ?>



<?php if(Request::segment(3) !== 'view'): ?>

    <div class="form-actions text-center" style="justify-content: center;align-content: center;">

        <?php echo e(Form::button('<i class="fa fa-reply"></i> '.'Send', array('type' => 'submit', 'name' => 'save_return', 'value' => '1' , 'class' => 'btn col-md-4 blue'))); ?>


    </div>

<?php else: ?>

    <div class="form-actions">

        <a href="<?php echo e(url($menuUrl)); ?>" class="btn blue"><i class="fa fa-chevron-left"></i> <?php echo e(trans('main.Back')); ?></a>

    </div>

<?php endif; ?>

<?php echo e(Form::close()); ?>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.'.$extender, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/forms/formNotifications.blade.php ENDPATH**/ ?>