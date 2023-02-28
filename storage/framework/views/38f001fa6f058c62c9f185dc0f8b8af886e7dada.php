<?php $__env->startSection('content'); ?>
<?php echo e(HTML::script('cpassets/jquery-ui-1.11.4/external/jquery/jquery.js')); ?>

<?php echo e(HTML::style('cpassets/jquery-ui-1.11.4/jquery-ui.css')); ?>

<?php echo e(HTML::script('cpassets/jquery-ui-1.11.4/jquery-ui.js')); ?>

<?php echo e(HTML::style('assets/datepicker/css/datepicker.css')); ?>

<?php echo e(HTML::script('http://momentjs.com/downloads/moment.js')); ?>

<?php echo e(HTML::style('cpassets/css/jquery.dataTables.min.css')); ?>

<?php echo e(HTML::script('cpassets/js/datatables.min.js')); ?>

<?php echo e(HTML::script('http://momentjs.com/downloads/moment.js')); ?>

<script>
$(document).ready(function(){
	//$("#dietician_select").selectmenu();
		var date = $('#date').datepicker({
		  onRender: function(date) {
		  	return '';
		    // return (date.valueOf() >= moment()) ? '' : 'disabled';

		  },
		  dateFormat: 'yy-mm-dd'
		}).on('changeDate', function(ev) {
		  date.hide();
		}).data('datepicker');
		$('#date').change(function(){
			if($("#dietician_select").val().trim() != "")
			{ 
			loadData();
			}
		});

 	$("#grid").DataTable({
 		"columns": 
 		[
 			{"title": "<?php echo e(trans('main.ID')); ?>", "sortable": false},
 			{"title": "<?php echo e(trans('main.Username')); ?>", "sortable": true},
 			{"title": "<?php echo e(trans('main.Clinic')); ?>", "sortable": true},
 			{"title": "<?php echo e(trans('main.Doctor')); ?>", "sortable": false},
 			{"title": "<?php echo e(trans('main.Date')); ?>", "sortable": true},
 			{"title": "<?php echo e(trans('main.Time')); ?>", "sortable": true},
 			{"title": "<?php echo e(trans('main.Notes')); ?>", "sortable": true},
 			{"title": "<?php echo e(trans('main.Attended')); ?>", "sortable": true},
 			{"title": "<?php echo e(trans('main.Membership end')); ?>", "sortable": true}
 		]
 	});
	$("#dietician_select").change(function(){
		//console.log($("#date").val());
		loadData();
	});
});
function loadData()
{
	if($("#date").val().trim() == "" )
		{
			return;
		}
		if($("#dietician_select").val() != -1)
		{
			$.ajax({
				url: "appointments_by_doctor/get_appointments_for_d",
				type: "POST",
				data: {dietician_id: $("#dietician_select").val(), appts_date: $("#date").val()}
			}).done(function(msg){
				if(msg != null)
				{
					var json = $.parseJSON(msg);
					if(json["result"] == "SUCCESS")
					{
						$("#grid").DataTable().clear().destroy();
						var id;
						var username;
						var clinicEn;
						var doctorName;
						var apptDate;
						var hour;
						var notes;
						var confirmed;
						var membershipEnd;
						var row = new Array;
						for(var i = 0 ; i < json["data"].length; i++)
						{
							id = json["data"][i]["id"];
							username  = json["data"][i]["username"];
							clinicEn = json["data"][i]["clinicEn"];
							doctorName = json["data"][i]["doctorName"];
							apptDate = json["data"][i]["ApptDate"];
							hour = json["data"][i]["hour"];
							notes = json["data"][i]["notes"];
							confirmed = json["data"][i]["confirmed"];
							membershipEnd = json["data"][i]["membership_end"];
							if(id  !== undefined)
							{
							//console.log(id, username, clinicEn, doctorName, apptDate, hour, notes, confirmed);
							
							/*$("#grid").DataTable().row.add([{
								1: id,
								2:username,
								3:clinicEn, 
								4: doctorName,
								5:apptDate, 
								6: hour,  
								7:notes,
								8:confirmed
															 }]).draw(false);*/
								row = [
								id,
								username,
								clinicEn, 
								doctorName,
								apptDate, 
								hour,  
								notes,
								(confirmed==0? 'False': 'True'),
								membershipEnd
								
															 ];
								/*row = {
								"<?php echo e(trans('main.ID')); ?>":id,
								"<?php echo e(trans('main.Username')); ?>":username,
								"<?php echo e(trans('main.Clinic')); ?>":clinicEn, 
								"<?php echo e(trans('main.Doctor')); ?>": doctorName,
								"<?php echo e(trans('main.Date')); ?>": apptDate, 
								"<?php echo e(trans('main.Time')); ?>": hour,  
								"<?php echo e(trans('main.Notes')); ?>":notes,
								"<?php echo e(trans('main.Attended')); ?>":(confirmed==0? 'False': 'True')
								
															 };*/
								$("#grid").DataTable().row.add(row);
/*
								$("#grid").DataTable().row.add([
								id,
								username,
								clinicEn, 
								doctorName,
								apptDate, 
								hour,  
								notes,
								confirmed
															 ]).draw(false);*/
							}

							
						}
						$("#grid").DataTable().draw(false);
						
						//	console.log($("#grid").DataTable().rows().data());
					}
				}
			});
		}
}
</script>
<div>
<div>
<label>
<?php echo e(trans('main.Dietician')); ?>

</label>
<select id="dietician_select" name="dietician_select">
<option value = "-1">Please select a dietician</option>

		<?php $__currentLoopData = $dieticians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dietician): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		       <?php echo '<option value="'.$dietician->id.'">'.$dietician->username.'</option>'; ?>

		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


</select>
</div>
<div >
	<?php echo e(Form::label('date', trans('main.Date') , array('class' => 'control-label'))); ?>

	<div class="controls">
		<?php echo e(Form::text('date', "select first", array('class' => '','id' => 'date'))); ?>

	</div>
</div>
<table id="grid" class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content dataTable no-footer">
<thead>
</thead>
</table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/appointments/doctor_selection.blade.php ENDPATH**/ ?>