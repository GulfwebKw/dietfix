@extends('admin.layouts.main')



@section('content')
{{ HTML::script('cpassets/jquery-ui-1.11.4/external/jquery/jquery.js') }}
{{ HTML::style('cpassets/jquery-ui-1.11.4/jquery-ui.css') }}
{{ HTML::script('cpassets/jquery-ui-1.11.4/jquery-ui.js') }}
{{ HTML::style('assets/datepicker/css/datepicker.css')}}
{{ HTML::script('http://momentjs.com/downloads/moment.js') }}
{{ HTML::style('cpassets/css/jquery.dataTables.min.css') }}
{{ HTML::script('cpassets/js/datatables.min.js') }}
{{ HTML::script('http://momentjs.com/downloads/moment.js') }}
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
 			{"title": "{{trans('main.ID')}}", "sortable": false},
 			{"title": "{{trans('main.Username') }}", "sortable": true},
 			{"title": "{{trans('main.Clinic')}}", "sortable": true},
 			{"title": "{{trans('main.Doctor')}}", "sortable": false},
 			{"title": "{{trans('main.Date')}}", "sortable": true},
 			{"title": "{{trans('main.Time')}}", "sortable": true},
 			{"title": "{{trans('main.Notes')}}", "sortable": true},
 			{"title": "{{trans('main.Attended')}}", "sortable": true},
 			{"title": "{{trans('main.Membership end')}}", "sortable": true}
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
								"{{trans('main.ID')}}":id,
								"{{trans('main.Username')}}":username,
								"{{trans('main.Clinic')}}":clinicEn, 
								"{{trans('main.Doctor')}}": doctorName,
								"{{trans('main.Date')}}": apptDate, 
								"{{trans('main.Time')}}": hour,  
								"{{trans('main.Notes')}}":notes,
								"{{trans('main.Attended')}}":(confirmed==0? 'False': 'True')
								
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
{{ trans('main.Dietician') }}
</label>
<select id="dietician_select" name="dietician_select">
<option value = "-1">Please select a dietician</option>

		@foreach($dieticians AS $dietician)
		       {!!'<option value="'.$dietician->id.'">'.$dietician->username.'</option>' !!}
		@endforeach


</select>
</div>
<div >
	{{ Form::label('date', trans('main.Date') , array('class' => 'control-label')) }}
	<div class="controls">
		{{ Form::text('date', "select first", array('class' => '','id' => 'date')) }}
	</div>
</div>
<table id="grid" class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content dataTable no-footer">
<thead>
</thead>
</table>
</div>
@stop