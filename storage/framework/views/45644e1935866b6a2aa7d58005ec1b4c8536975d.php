

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
            <form method="POST" action="/admin/app/setting/save" accept-charset="UTF-8" class="form-horizontal form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data">
                <?php echo e(csrf_field()); ?>


                <div class="row">
                    <div class="control-group form-group  col-sm-4" id="username_holder">
                        <label for="username" class="control-label col-sm-4">Android Version(Version Code)</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="android_version"  type="text" id="android_version" value="<?php echo e($settings['android_version']->value); ?>">
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="control-group form-group  col-sm-8" id="username_holder">
                        <label for="username" class="control-label col-sm-4">Content</label>
                        <div class="controls col-sm-8">
                            <textarea name="content" rows="10"  class="form-control">
                             <?php echo $settings['notification_content']->value; ?>

                            </textarea>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="control-group form-group  col-sm-8" id="username_holder">
                        <label for="username" class="control-label col-sm-4">Terms And Condition</label>
                        <div class="controls col-sm-8">
                            <textarea name="terms_conditions" rows="10"  class="form-control form-control wysiwyg editor" id="terms_conditions">
                                 <?php echo $settings['terms_conditions']->value; ?>

                            </textarea>
                        </div>

                    </div>
                </div>

                <hr/>
                <br/>
                <div class="form-actions">
                    <button type="submit" class="btn col-md-3 yellow"><i class="fa fa-check"></i> Save</button>
                </div>


            </form>

        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_foot'); ?>

    ##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##
    <?php echo e(HTML::script('js/moment.js')); ?>

    <?php echo e(HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')); ?>



    <?php echo e(HTML::script('/cpassets/plugins/ckeditor/ckeditor.js')); ?>


    <?php echo e(HTML::script('/cpassets/plugins/ckeditor/adapters/jquery.js')); ?>


    <?php echo e(HTML::script('/cpassets/plugins/ckeditor/own.js')); ?>



    <script src="/cpassets/plugins/tinymce/js/tinymce/tinymce.min.js"></script>

    <script src="/cpassets/plugins/tinymce/js/tinymce/jquery.tinymce.min.js"></script>


    <script>

        jQuery(document).ready(function($) {

            $("#terms_conditions").tinymce({

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

<?php echo $__env->make('admin.forms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/application/application.blade.php ENDPATH**/ ?>