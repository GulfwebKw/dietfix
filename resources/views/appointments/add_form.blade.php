@section('custom_head_include')
{{ HTML::style('assets/datepicker/css/datepicker.css') }}
{{ HTML::style('assets/time-picker/jquery.timepicker.css') }}
{{ HTML::style('cpassets/uploadifive/uploadifive.css') }}
@stop
@section('custom_foot')
{{ HTML::script('assets/moment/moment.js') }}
{{ HTML::script('assets/datepicker/js/bootstrap-datepicker.js') }}
{{ HTML::script('assets/time-picker/jquery.timepicker.min.js') }}
{{ HTML::script('cpassets/uploadifive/jquery.uploadifive.min.js') }}

<script>
	

	function hourChanged () {
		var doctor_id = $('#doctor_id').val();
		$('#hour').on('changeTime', function() {
	  		check_hour();
		});
	}

	function check_hour () {
		var doctor_id = $('#doctor_id').val();
		var dated = $('#date').val();
  		var data = {'doctor' : doctor_id, 'date' : dated, 'hour' : $('#hour').val()};
  		$('#submit').prop('disabled',true);
	    $.post(APP_URL+'/appointments/check-hour', data).done(function( data ) {
		    if (!data.result) {
		    	alert('{{ trans('main.Please choose another time') }}');
		    } else {
		    	$('#submit').prop('disabled',false);
		    }
	  	});
	}

	function hourCreated(start,end) {
		$('#hour').timepicker({
		    'step': 15,
		    'useSelect': true,
		    'minTime': start,
			'maxTime': end
		});
	}

	function getDoctorTimes() {
		var doctor_id = $('#doctor_id').val();
		
		var weekday = weekdays();
		var day_of_weeks = swap(weekday);

		$.getJSON(APP_URL+'/appointments/doctor-times/'+doctor_id, null, function(json, textStatus) {

			$('#hour').timepicker({
				'step': 15,
				'useSelect': true,
				'minTime': "10:00",
				'maxTime': "20:00"
			});

			// var accepted_days = [];
			// $.each(json.times_sorted, function(index, val) {
			// 	accepted_days.push(parseInt(day_of_weeks[index]));
			// });
			//  console.log(accepted_days);
			// var nowTemp = new Date();
			// var old_date_val = $('#date').val();
			// $('#date').remove();
			// $('.date_holder').html('<input class="datepicker form-control" id="date" name="date" type="text">');
			// // var date = $('#date').datepicker('remove');
			// var date = $('#date').datepicker({
			//   onRender: function(date) {
			//   	// console.log(json);
			//   	// console.log(date.getDay());
			//   	// console.log(accepted_days);
			//   	// console.log(date);
			//   	// console.log(moment());
			//     return ($.inArray(date.getDay(), accepted_days) >= 0 && date.valueOf() >= moment()) ? '' : 'disabled';
			//
			//   },
			//   format: 'yyyy-mm-dd'
			// }).on('changeDate', function(ev) {
			//   date.hide();
			//   check_hour();
			//   var start = json.times_sorted[weekday[ev.date.getDay()]].from;
			//   var end = json.times_sorted[weekday[ev.date.getDay()]].to;
			//   hourCreated(start,end);
			//   hourChanged();
			// }).data('datepicker');
		});
	}

	function setTimePicker () {
		var doctor_id = $('#doctor_id').val();
		var date = $('#date').val();
		if (date && doctor_id) {
			$.getJSON(APP_URL+'/appointments/doctor-date-times/'+doctor_id+'/'+date, null, function(json, textStatus) {
				if (json.result) {
					hourCreated(json.result.from,json.result.to);
				}
				hourChanged();
			});
		}
	}

	setTimePicker();
	$("#date").on('click', function(){
	getDoctorTimes();
	});

	$('#doctor_id').change(function(event) {
		getDoctorTimes();
	});



			
			$('#filess').uploadifive({
			'auto' : true,
						'multi' : true,
						'formData' : {
			'timestamp' : '{{ time() }}',
			'token' : '{{ md5('SecUre!tN0w' . time()) }}',
			'folder' : 'media/files/',
			'fileExt' : 'doc,docx,xls,xlsx,pdf,png,jpg,gif,zip,rar,jpeg,bmp',
			},
			'queueID' : 'queuefiless',
			'uploadScript' : '{{ url('upload_files') }}',
			'onUploadComplete' : function(file, data) { 
				var rname=data;
				var fullpath= '{{ url('resize?w=30&h=30&src=media/files') }}'+"/"+rname;
				var link= '{{ url('resize?w=500&r=1&src=media/files') }}'+"/"+rname;

				// Photo Handling Vars
				var linkhrefstart = '';
				var linkhrefend = '';
				
				// Multi Field Name Handling
				var fieldname = 'filesss[]';
				
				// Paragraph Setup
				var p = '';
				var p=p+'<div class="uploadedImg">';
				var p=p+'<input type="checkbox" checked="checked" value="'+rname+'" name="' + fieldname + '" >';
				var p=p+linkhrefstart;
				var p=p+'<img src="'+fullpath+'">';
				var p=p+linkhrefend;
				var p=p+'<br /><small>'+rname+"</small>";
				var p=p+'</div>';

				$('#filess-thumbnails').append(p);
				
				$('.mesg').fadeOut('slow');	}
			});
			
			$(document).ready(function(){
				var date = $('#date').datepicker({
		  onRender: function(date) {
		  	return '';
		    // return (date.valueOf() >= moment()) ? '' : 'disabled';

		  },
		  dateFormat: 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
		  date.hide();
		}).data('datepicker');
			});


</script>
@stop

@section('contents')
	{{ Form::open(array('url' => url('appointments/save/'.$user->id), 'class' => 'form-horizontal form-bordered form-row-stripped col-md-8 col-md-push-2', 'files' => true, 'role' => 'form')) }}
		{{ Form::hidden('id',$item->id) }}
		{{ Form::hidden('user_id',$user->id) }}
		{{ Form::hidden('clinic_id',$user->clinic_id) }}


	<div class="control-group form-group">
		{{ Form::label('doctor_id', trans('main.Dietitian') , array('class' => 'control-label col-sm-4')) }}
		<div class="controls col-sm-8">
			@if(Auth::user()->role_id == 1)
				<div class="form-control">{{ @$item->doctor->username }}
					{{ Form::hidden('doctor_id',($item->doctor_id) ? $item->doctor_id : $user->doctor_id,['id' => 'doctor_id']) }}
				</div>
			@else
				<select class="chosen form-control" id="doctor_id" name="doctor_id">

					@if(isset($doctors))
						@foreach($doctors as $item)
							<option value="{{$item->id}}" selected="selected">{{$item->username}}</option>
						@endforeach
					@endif

				</select>
			@endif
		</div>
	</div>


	<div class="control-group form-group">
		{{ Form::label('date', trans('main.Date') , array('class' => 'control-label col-sm-4')) }}
		<div class="controls col-sm-8 date_holder">
			{{ Form::text('date', $item->date, array('class' => 'datepicker form-control','id' => 'date')) }}
		</div>
	</div>




		<div class="control-group form-group">
			{{ Form::label('hour', trans('main.Time') , array('class' => 'control-label col-sm-4')) }}
			<div class="controls col-sm-8">
				{{ Form::text('hour', $item->hour, array('class' => 'timepicker form-control','id' => 'hour')) }}
			</div>
		</div>
		@if (Auth::user()->role_id != 1)
		<div class="control-group form-group">
			{{ Form::label('notes', trans('main.Notes') , array('class' => 'control-label col-sm-4')) }}
			<div class="controls col-sm-8">
				{{ Form::textarea('notes', $item->notes, array('class' => 'form-control')) }}
			</div>
		</div>
		<div class="control-group form-group">
			{{ Form::label('height', trans('main.Height') , array('class' => 'control-label col-sm-4')) }}
			<div class="controls col-sm-8">
				{{ Form::text('height', $item->height, array('class' => 'form-control')) }}
			</div>
		</div>
		<div class="control-group form-group">
			{{ Form::label('weight', trans('main.Weight') , array('class' => 'control-label col-sm-4')) }}
			<div class="controls col-sm-8">
				{{ Form::text('weight', $item->weight, array('class' => 'form-control')) }}
			</div>
		</div>
		<div class="control-group form-group">
			{{ Form::label('confirmed', trans('main.Attendance') , array('class' => 'control-label col-sm-4')) }}
			<div class="controls col-sm-8">
				{{ Form::checkbox('confirmed', 1,$item->confirmed, array('class' => 'form-control')) }}
			</div>
		</div>
		<div class="control-group form-group">
			{{ Form::label('files', trans('main.Files') , array('class' => 'control-label col-sm-4')) }}
			<div class="controls col-sm-8">
				{{ Form::file('filess' , array('multiple' => 'true', 'id' => 'filess')) }}
				<div id="filess-thumbnails">
				</div>
				<div class="clearfix"></div>
				<div id="queuefiless" class="help-inline"></div>
				<div class="mesg">
				Please wait untill upload completing otherwise the photo will not appear				
				</div>

			</div>
		</div>
		@endif

		<div class="control-group form-group">
			{{ Form::button('<i class="fa fa-check"></i> '.trans('main.Request'), array('class' => 'btn btn-primary col-sm-6 col-sm-push-3','id' => 'submit','type' => 'submit')) }}
		</div>

	{{ Form::close() }}
@stop