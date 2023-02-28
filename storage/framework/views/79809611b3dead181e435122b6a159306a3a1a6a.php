



<?php $__env->startSection('custom_head_include'); ?>



    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js')); ?>

    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js')); ?>

    <?php echo e(HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css')); ?>

    <?php echo e(HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css')); ?>

    <?php echo e(HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css')); ?>


    <?php echo e(HTML::style('cpassets/css/pqgrid.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/pqgrid.min.js')); ?>

    <?php echo e(HTML::style('cpassets/css/jquery.dataTables.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/datatables.min.js')); ?>

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

    <div class="table-toolbar">

        <div class="btn-group pull-left">



            <a id="grid_new" class="btn green" href="https://www.dietfix.com/admin/packages-duration/add">

                Add <i class="fa fa-plus"></i>

            </a>




            <button id="grid_edit" class="btn blue">

                Edit <i class="fa fa-edit"></i>

            </button>




            <button id="grid_delete" class="btn red">

                Delete <i class="fa fa-remove"></i>

            </button>



        </div>

    </div>


    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">

            <tr role="row">
                <th class="exclude-search table-checkbox sorting_asc" rowspan="1" colspan="1" aria-label="" style="width: 148px;">
                    <div class="checker"><span><input type="checkbox" class="group-checkable checkall" data-set="#grid .checkboxes"></span></div>
                </th>



                <th class="exclude-search sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending" style="width: 118px;">ID
                </th>

                <th class="exclude-search sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="Package: activate to sort column ascending">
                    Package
                </th>

                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Arabic Title

                    : activate to sort column ascending" style="width: 44px;">

                    Arabic Title

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        English Title

                    : activate to sort column ascending" style="width: 51px;">

                    English Title

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Count Day

                    : activate to sort column ascending" style="width: 41px;">

                    Count Day

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Price

                    : activate to sort column ascending" style="width: 35px;">

                    Price

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Price After Discount

                    : activate to sort column ascending" style="width: 60px;">

                    Price After Discount

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Active

                    : activate to sort column ascending" style="width: 43px;">

                    Active

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Active For App

                    : activate to sort column ascending" style="width: 43px;">

                    Active For App

                </th>
                <th class="exclude-search nwrap sorting_disabled" rowspan="1" colspan="1" aria-label="

			" style="width: 202px;">

                </th>
            </tr>

            </thead>

            <?php $i=0; ?>
            <tbody>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <input type="checkbox" class="checkboxes" value="<?php echo e($item->id); ?>">
                    </td>

                    <td><?php echo e($item->id); ?></td>
                    <td><?php echo e(optional($item->package)->titleEn); ?><hr><b>PID =<?php echo e(optional($item->package)->id); ?></b></td>
                    <td><?php echo e($item->titleAr); ?></td>
                    <td><?php echo e($item->titleEn); ?></td>
                    <td><?php echo e($item->count_day); ?></td>
                    <td><?php echo e($item->price); ?></td>
                    <td><?php echo e($item->price_after_discount); ?></td>
                    <td>
                        <?php if($item->active==1): ?>
                            <?php echo trans('main.Yes'); ?>

                        <?php else: ?>
                            <?php echo trans('main.No'); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($item->show_mobile==1): ?>
                            <?php echo trans('main.Yes'); ?>

                        <?php else: ?>
                            <?php echo trans('main.No'); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/admin/packages-duration/edit/<?php echo e($item->id); ?>" data-id="<?php echo e($item->id); ?>" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-edit"></i> Edit</a>
                        <a href="/admin/packages-duration/delete/<?php echo e($item->id); ?>" data-id="<?php echo e($item->id); ?>" class="nwrap btn btn-xs red btn-block grid-del-but"><i class="fa fa-remove"></i> Delete</a>
                    </td>

                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>


        </table>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>

    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js')); ?>


    <?php echo e(HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js')); ?>


    <script>
        jQuery(document).ready(function($) {

            var table = $('#grid').DataTable({

                

                        
                        

                        

                        

                        
                        
                        
                        
                        
                        

                        
                        
                        

                        

                        

                        

                "processing" : true,

                // set the initial value

                "displayLength": <?php echo e($noOfItems); ?>,

                "paginationType": "bootstrap_full_numbers",

                "pagingType": "full_numbers",

                "language": {

                    "lengthMenu": "_MENU_ <?php echo app('translator')->getFromJson('main.Records'); ?> ",

                    "search": '<?php echo app('translator')->getFromJson('main.Search'); ?> : ',

                    "info": "<?php echo app('translator')->getFromJson('main.Showing _START_ to _END_ of _TOTAL_ entries'); ?>",

                    "processing": '<img src="http://sys.dietfix.com/images/progress.gif" width="20">&nbsp;<?php echo app('translator')->getFromJson('main.Please wait'); ?>...',

                    "paginate": {

                        "last": '<i class="fa fa-arrow-circle-left"></i>',

                        "first": '<i class="fa fa-arrow-circle-right"></i>',

                        "previous": '<i class="fa fa-angle-left"></i>',

                        "next": '<i class="fa fa-angle-right"></i>'

                    }

                },

                "columnDefs": [{
                    "defaultContent": "",
                    "targets": "_all",
                }

                ],

                "initComplete": function (oSettings, json) {

                    //     	$('#grid tfoot td:not(.exclude-search)').each( function () {

                    // var currentTD = $(this);

                    //       var title = $('#grid tfoot td').eq( currentTD.index() ).text();

                    //       var select = $('<select class="form-control"><option value=""><?php echo e(trans('main.Search')); ?> '+title+'</option></select>')

                    //           .appendTo( currentTD.empty() )

                    //           .on( 'change', function () {

                    //               var val = $(this).val();

                    // 			console.log(val);

                    //               table.columns( currentTD.index() )

                    //                   .search( val ? '^'+$(this).val()+'$' : val, true, false )

                    //                   .draw();

                    //           } );



                    // 	table.columns( currentTD.index() ).data().unique().sort().each( function ( j ) {

                    // 		$.each(j,function  (k,d) {

                    // 	select.append( '<option value="'+d+'">'+d+'</option>' );

                    // 		});

                    // 	});

                    //   });

                }

            });





            $("#grid .search-field").on( 'keyup change', function  () {

                var colIdx = $(this).parent().parent().index();

                table.column( colIdx ).search( this.value ).draw();

            });


            <?php $__currentLoopData = $buts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php if($b['name'] != 'Add'): ?>

            $('#grid_<?php echo e(strtolower($b['name'])); ?>').click(function  () {

                        <?php if($b['name'] == 'Delete'): ?>

                var answer = confirm ( "<?php echo e(trans('main.ConfirmDelete')); ?> "+$('.trSelected',grid).length +" <?php echo e(trans('endConfirm')); ?>"  );

                if (answer){

                    var value = [];

                    $(".checkboxes:checked").each(function() {

                        value.push($(this).val())

                    });

                    var resp = $.ajax({

                        type: "POST",

                        url: "<?php echo e($deleteurl); ?>",

                        data: "id="+value,

                        success: function(msg){

                            alert(  $('.checkboxes:checked').length + "  Deleted!" );

                            $('.checkboxes').prop('checked', false);

                            window.location.reload();

                        }

                    }).responseText;

                }

                        <?php else: ?>

                var value = $('.checkboxes:checked').val();

                window.location = '<?php echo e(url($menuUrl.'/'.strtolower($b['name']))); ?>/'+value;

                <?php endif; ?>

            });

            <?php endif; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            $('.grid-del-but').click(function  () {

                var answer = confirm ("<?php echo e(trans('main.ConfirmDelete')); ?> "+$('.trSelected',grid).length +" <?php echo e(trans('endConfirm')); ?>" );

                if (answer)

                    return true;

                else

                    return false;

            });

            $('.checkall').click(function(event) {

                if(this.checked) {

                    // Iterate each checkbox

                    $('.table tbody input:checkbox').each(function() {

                        this.checked = true;

                    });

                }

                else {

                    $('.table tbody input:checkbox').each(function() {

                        this.checked = false;

                    });

                }

            });

        });
    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/package_duration.blade.php ENDPATH**/ ?>