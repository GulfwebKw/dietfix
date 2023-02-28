



<?php $__env->startSection('custom_head_include'); ?>



	<?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js')); ?>

	<?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js')); ?>

	<?php echo e(HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css')); ?>

	<?php echo e(HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css')); ?>

	<?php echo e(HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css')); ?>


	<?php echo e(HTML::style('cpassets/css/pqgrid.min.css')); ?>

	<?php echo e(HTML::script('cpassets/js/pqgrid.min.js')); ?>

	<!--<?php echo e(HTML::style('cpassets/css/jquery.dataTables.min.css')); ?>

	<?php echo e(HTML::script('cpassets/js/datatables.min.js')); ?>-->
    <!--<?php echo e(HTML::style('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css')); ?>-->
    <!--<?php echo e(HTML::style('//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css')); ?>-->
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

		<?php $__currentLoopData = $buts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

			<?php if($b['name'] == 'Add'): ?>

			<a id="grid_new" class="btn green" href="<?php echo e($newurl); ?>">

			<?php echo app('translator')->getFromJson('main.Add'); ?> <i class="fa fa-plus"></i>

			</a>

			<?php else: ?>

			<button id="grid_<?php echo e(strtolower($b['name'])); ?>" class="btn <?php echo e($b['color']); ?>">

			<?php echo app('translator')->getFromJson('main.'.$b['name']); ?> <i class="fa fa-<?php echo e($b['icon']); ?>"></i>

			</button>

			<?php endif; ?>

		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

	</div>

</div>

<div class="clearfix"></div>

<br>

<div class="flip-scroll">

	<table class="table table-striped table-bordered" style="width:100%"  id="grid">

		<thead class="flip-content">

		<?php /*

		<tr class="search-row">

			<th class="exclude-search table-checkbox">

			</th>

			<th class="exclude-search">

			</th>

			@foreach ($gridFields as $field)

			<th width="{{ $field['width'] or 10 }}%">

				{{ $field['title'] }}

			</th>

			@endforeach

			<th class="exclude-search nwrap">

			</th>

		</tr>

		*/  ?>
		<?php if($menuUrl=="admin/membership"){$sdate = Session::get('sdate');?>
   Filter by Date: <input id="startdate" value="<?php if(!empty($sdate)){ echo $sdate;}?>" class="form-control input-sm" style="width:150px;">
   <?php }?>
		<tr>

			<th class="exclude-search table-checkbox">

				<input type="checkbox" class="group-checkable checkall" data-set="#grid .checkboxes"/>

			</th>

			<th class="exclude-search">

				ID

			</th>

			<?php $__currentLoopData = $gridFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php if(array_key_exists('width',$field)): ?>

                    <th width="<?php echo e($field['width'] or 10); ?>%">

                        <?php echo e($field['title']); ?>


                    </th>
                    <?php else: ?>

                    <th width="10%">
                        <?php echo e($field['title']); ?>

                    </th>
                <?php endif; ?>


			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

			<th class="exclude-search nwrap">

			</th>

		</tr>

		</thead>


		<tbody>

		</tbody>

	</table>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>

	<?php echo e(HTML::script('https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js')); ?>


    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js')); ?>


	<script>

        jQuery(document).ready(function($) {
		
		 /* $('#grid').DataTable( {
          "ajax": "<?php echo e(url($menuUrl.'/ajax')); ?>",
		  "columns": [
                    { "sortable": false, "data": "checkboxCol" } ,
                    { "sortable": true, "data": "<?php echo e($_pk); ?>" },
                        <?php $__currentLoopData = $gridFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    { "sortable": true, "data": "<?php echo e($field['name']); ?>" },
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    { "sortable": false, "data": "butsCol" }
                    ],
					"displayLength": <?php echo e($noOfItems); ?>

          });*/
		  

           var table = $('#grid').DataTable({

                "ajax": "<?php echo e(url($menuUrl.'/ajax')); ?>",

                "columns": [
                    { "sortable": false, "data": "checkboxCol" } ,

                    { "sortable": true, "data": "<?php echo e($_pk); ?>" },

						<?php $__currentLoopData = $gridFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    { "sortable": true, "data": "<?php echo e($field['name']); ?>" },

						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    { "sortable": false, "data": "butsCol" }
//          { "sortable": false, "data": "mainGridButsCol" } //MH
                ],"lengthMenu": [

                    [10, 20, 50, 100, -1],

                    [10, 20, 50, 100, "All"] // change per page values here

                ],

                "processing" : true,

                // set the initial value

                "displayLength": <?php echo e($noOfItems); ?>,

                "paginationType": "bootstrap_full_numbers",

                "pagingType": "full_numbers",

                "language": {

                    "lengthMenu": "_MENU_ <?php echo app('translator')->getFromJson('main.Records'); ?> ",

                    "search": '<?php echo app('translator')->getFromJson('main.Search'); ?> : ',

                    "info": "<?php echo app('translator')->getFromJson('main.Showing _START_ to _END_ of _TOTAL_ entries'); ?>",

                    "processing": '<img src="http://dietfix.com/images/progress.gif" width="20">&nbsp;<?php echo app('translator')->getFromJson('main.Please wait'); ?>...',

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
        //
        $(function(){
            $("#startdate").datepicker({
                autoclose:!0,
                format:"yyyy-mm-dd",
                startDate:new Date
            }),
                $("#enddate").datepicker({
                    autoclose:!0,
                    format:"yyyy-mm-dd",
                    startDate:new Date
                })
        });
        $("#startdate").change(function(){
            var dval = $(this).val();
            var mydate = new Date(dval);
            var str = mydate.toString("yyyy-MM-dd");
            $.ajax({
                type: "GET",
                url:  '<?php echo e(url($menuUrl.'s_date/get_dates/')); ?>/'+str,
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
        });
	</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/grid.blade.php ENDPATH**/ ?>