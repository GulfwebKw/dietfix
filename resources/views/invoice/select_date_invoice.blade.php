@extends('admin.layouts.main')



@section('content')
{{ HTML::style('assets/datepicker/css/datepicker.css') }}
{{ HTML::script('http://momentjs.com/downloads/moment.js') }}
{{ HTML::script('assets/datepicker/js/bootstrap-datepicker.js') }}
<script>
$(document).ready(function(){
var date = $('#date').datepicker({
		  onRender: function(date) {
		  	return '';
		    // return (date.valueOf() >= moment()) ? '' : 'disabled';

		  },
		  format: 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
		  date.hide();
		}).data('.datepicker');
		$.ajax({
			url: "invoices/getusers",
			type: "POST"
		}).done(function(msg){
			if(msg != null)
			{
				var json = $.parseJSON(msg);
				if(json["result"] == "SUCCESS")
				{
					var html ="";
					var idSelectHTML = "";
					for(var i = 0 ; i < json["data"].length; i++ )
					{
						html += "<option value='"+json["data"][i]["userid"]+"'>"+json["data"][i]["username"]+"</option>";
						idSelectHTML += "<option value='"+json["data"][i]["userid"]+"'>"+json["data"][i]["userid"]+"</option>";
					}
					$("#userid").append(html);
					$("#userid-val").append(idSelectHTML);
					$("#userid-val").change(function(){
					$("#userid option[value = "+$("#userid-val").val()+"]").attr('selected', 'selected');
					});
					$("#userid").change(function(){
					$("#userid-val").val($("#userid-val").val());
					});

				}
			}
		});


});
</script>
	{{ Form::open(array('url' => url('admin/invoices/getinvoice'), 'method' => 'post', 'class' => 'form-inline form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form')) }}



		{{ Form::label('username', trans('main.Username')) }}
		<div class="control-group form-group">
			<div class="controls">
			<select id="userid" name="userid">
				
			</select>
			</div>
		</div>

		{{ Form::label('UserID', trans('main.UserID')) }}
		<div class="control-group form-group">
			<div class="controls">
			<select id="userid-val" name="userid-val">
				
			</select>
			</div>
		</div>

		<div class="control-group form-group">
			{{ Form::button('<i class="fa fa-search"></i>', array('class' => 'btn btn-primary','id' => 'submit','type' => 'submit')) }}
		</div>

	{{ Form::close() }}
@stop