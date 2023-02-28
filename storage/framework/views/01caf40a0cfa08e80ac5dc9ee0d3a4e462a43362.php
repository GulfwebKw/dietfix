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

	<?php echo e(HTML::script('cpassets/jsPDF-1.2.60/dist/jspdf.debug.js')); ?>

	<?php if(!empty($customJS)): ?>
	<?php echo e(HTML::script($customJS)); ?>

	<?php endif; ?>


	<?php if($_adminLang == 'arabic'): ?>

	<?php echo e(HTML::style('cpassets/plugins/data-tables/DT_bootstrap_rtl.css')); ?>


	<?php else: ?>

	<?php echo e(HTML::style('cpassets/plugins/data-tables/DT_bootstrap.css')); ?>


	<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>

<?php echo e(HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js')); ?>


<?php echo e(HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js')); ?>


<script>
var userId = <?php echo e($userId); ?>;
var membershipID;
var username;
var dateObj;
jQuery(document).ready(function($) {       
   $("#totinvoice").html(<?php echo e($totalInvoice); ?>);
   var table = $('#grid').DataTable({

   		data: <?php echo e($items); ?>,

        "columns": [
 		   //{ "sortable": false, "data": "checkboxCol" } , 

          //{ "sortable": true, "data": "<?php echo e($_pk); ?>" },

          <?php $__currentLoopData = $gridFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

          { "sortable": true, "title": "<?php echo e($field['name']); ?>" },

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

            "processing": '<i class="fa fa-coffee"></i>&nbsp;<?php echo app('translator')->getFromJson('main.Please wait'); ?>...',

            "paginate": {

                "last": '<i class="fa fa-arrow-circle-left"></i>',

                "first": '<i class="fa fa-arrow-circle-right"></i>',

                "previous": '<i class="fa fa-angle-left"></i>',

                "next": '<i class="fa fa-angle-right"></i>'

            }

        },

        "columnDefs": [{
        	"defaultContent": "<button onclick='showPrintDlg($(this))'><?php echo e(trans('main.Print Invoice')); ?></button>",
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



	// $('#grid thead tr.search-row th:not(.exclude-search)').each( function () {

 //        var title = $('#grid thead th').eq( $(this).index() ).text();

 //        $(this).html( '<div class="form-group"><input type="text" class="form-control search-field" placeholder="<?php echo e(trans('main.Search')); ?> '+title+'" /></div>' );

 //    });

    // Apply the search

 	$("#grid .search-field").on( 'keyup change', function  () {

 		var colIdx = $(this).parent().parent().index();

 		table

            .column( colIdx )

            .search( this.value )

            .draw();

 	});


/*   <?php $__currentLoopData = $buttons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

   <?php if($b['name'] != 'Add'): ?>

   $('#grid_<?php echo e(strtolower($b['name'])); ?>').click(function  () {

   	<?php if($b['name'] == 'Delete'): ?>

   	var answer = confirm ( <?php echo e(Lang::get('main.GridDeleteConfirmation')); ?> );

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
*/
   $('.grid-del-but').click(function  () {

   	var answer = confirm ( <?php echo e(Lang::get('main.GridDeleteConfirmation')); ?> );

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

function showPrintDlg(ths)
{
	var membershipRow = $("#grid").DataTable().row( $(ths).parents('tr') ).data();
        membershipID = membershipRow[0];
	dateObj = new Date();

	$.ajax({
		url: "getinvoice_data",
		type: "POST",
		data: {userid: userId, membership_id:membershipID}
	}).done(function(msg){
		if(msg != null)
		{
			var json = $.parseJSON(msg);
			if(json["result"] == "SUCCESS")
			{
				username = json["data"][0]["username"];
			var invoiceDialog = "<div id='invoice_dialog' title='Invoice' style='background-color: white;'><h1>INVOICE</h1><h4>Date:"+ (dateObj.getFullYear()+"/"+(dateObj.getMonth()+1) + "/"+dateObj.getDate()+" "+ dateObj.getHours()+":"+ dateObj.getSeconds())+"</h4><br /><h2>Username: "+json["data"][0]["username"]+"</h2><h2>Package: "+json["data"][0]["packageEn"]+"</h2><h2> Price: "+json["data"][0]["price"]+" KD <input type='text' name='discount' id='discount' placeholder='Discount%' style='width:160px;'></h2><h3>Days Left: "+json["data"][0]["days_left"]+"</h3><h3>Renewed at: "+json["data"][0]["renewed_at"]+"</h3><span id='discount_txt'></span><span id='Totalprice_txt'></span><div id='editor'></div></div>";

$( "#invoice_dialog" ).remove();
$(".page-content").append(invoiceDialog);
	$("#discount").change(function(){
	var disprice = $(this).val();
	var result = (disprice / 100) * json["data"][0]["price"];
	$("#discount_txt").html('<h2>Discount Price : '+parseFloat(result).toFixed(2)+' KD</h2>');
	var payments = json["data"][0]["price"] - result ;
	$("#Totalprice_txt").html('<h2>Total Price : '+parseFloat(payments).toFixed(2)+' KD</h2>');
	});
		
	$( "#invoice_dialog" ).dialog({
      		autoOpen: false,
      		height: 500,
      		width: 600,
      		modal: true,
      		buttons: {
        	"Print invoice": printInvoice,
        	Cancel: function() {
          	$("#invoice_dialog").dialog( "close" );
        	}
      		},
    		});
    		$( "#invoice_dialog" ).dialog('open');
			}
		}
	});
}

function printInvoice()
{
	var doc = new jsPDF();
	var specialElementHandlers = {
    '#editor': function (element, renderer) {
        return true;
    	}
	};
	doc.fromHTML($('#invoice_dialog').html(), 15, 50, {
        'width': 170,
            'elementHandlers': specialElementHandlers
    });
  /*  var imgData = 'data:image/png;base64,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';
 */
 var imgData = "data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMraHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkFGMjBDMUM5RjEzQjExRTc5MUMxOUM5QzA2MjczODA1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkFGMjBDMUNBRjEzQjExRTc5MUMxOUM5QzA2MjczODA1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QUYyMEMxQzdGMTNCMTFFNzkxQzE5QzlDMDYyNzM4MDUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QUYyMEMxQzhGMTNCMTFFNzkxQzE5QzlDMDYyNzM4MDUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAAGBAQEBQQGBQUGCQYFBgkLCAYG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";
 
    doc.addImage(imgData, 'JPEG', 10, 10, 150, 40);

    doc.save('invoice-'+username+'_'+(dateObj.getDate()+"/"+(dateObj.getMonth()+1) + "/"+dateObj.getFullYear()+" "+ dateObj.getHours()+":"+ dateObj.getSeconds())+'.pdf');

}
</script>

<?php $__env->stopSection(); ?>





<?php $__env->startSection('content'); ?> 

<div class="table-toolbar">

<div align="center">Total Invoice : <span id="totinvoice"></span></div>

</div>

<div class="clearfix"></div>

<br>

<div class="flip-scroll">

	<table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

		<thead class="flip-content">


		<tr>

			<?php $__currentLoopData = $gridFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

			<th width="<?php echo e($field['width'] or 10); ?>%">

				<?php echo e($field['title']); ?>


			</th>

			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

			<th class="exclude-search nwrap">

			</th>

		</tr>

		</thead>

		<?php /*

		<tfoot class="flip-content">

		<tr>

			<td class="table-checkbox exclude-search">

			</td>

			<td class="exclude-search">

				ID

			</td>

			@foreach ($gridFields as $field)

			<td width="{{ $field['width'] or 10 }}%">

				{{ $field['title'] }}

			</td>

			@endforeach

			<td class="nwrap exclude-search">

			</td>

		</tr>

		</tfoot>

		*/ ?>

		<tbody>

		</tbody>

	</table>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/invoice/invoice_report.blade.php ENDPATH**/ ?>