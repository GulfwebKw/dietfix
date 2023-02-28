@extends('admin.forms.form')
@section('custom_foot')
@parent
	<script>
		jQuery(document).ready(function() {    
			var city_id = $('#city_id');
			var area_id = $('#area_id');
			var current_city_id = $(city_id).val();

			$.getJSON( "{{ url(ADMIN_FOLDER.'/aqar/areas') }}/"+current_city_id, function( data ) {
					var items = [];
					console.log(area_id);
					area_id.find('option').remove();

					$.each( data, function( key, val ) {
						$(area_id).append('<option value="' + key + '">' + val + '</option>');
					});
					@if (Input::old('area_id'))
						$(area_id).val('{{ Input::old('area_id')  }}');
					@elseif (isset($item) && isset($item->area_id))
						$(area_id).val('{{ $item->area_id }}');
					@endif
			});

			$(city_id).change(function  () {
				var current_city_id = $(city_id).val();
				$.getJSON( "{{ url(ADMIN_FOLDER.'/aqar/areas') }}/"+current_city_id, function( data ) {
						var items = [];
						console.log(area_id);
						area_id.find('option').remove();

						$.each( data, function( key, val ) {
							$(area_id).append('<option value="' + key + '">' + val + '</option>');
						});
				});
			});

		});
	</script>
@stop

@section('forms2')

	<?php $photoval = Input::old('photos'); ?>

	@if (isset($photoval) && !empty($photoval))
		<?php $photovalue = $photoval; ?>
	@elseif (isset($item) && $item->has('photos'))
		@foreach ($item->photos as $photo)
			<?php $photovalue[] = $photo->url; ?>
		@endforeach
	@else
		<?php $photovalue = null; ?>
	@endif

	<?php $valueval = Input::old('values'); ?>

	@if (isset($valueval) && !empty($valueval))
		@foreach ($valueval as $value)
			@if ($value != 0)
				<?php $valuevalue[AqarFieldValue::find($value)->field->id] = $value; ?>
			@endif
		@endforeach
	@elseif (isset($item) && $item->has('values'))
		@foreach ($item->values as $value)
			<?php $valuevalue[$value->field->id] = $value->id; ?>
		@endforeach
	@else
		<?php $valuevalue = null; ?>
	@endif

	@foreach ($aqfields as $k => $f)
	@if (isset($f['values']))
	<div class="control-group">
		{{ Form::label('values_'.$k, $f['name'] , array('class' => 'control-label')) }}
		<div class="controls">
			{{ Form::select('values[]', @$f['values'] , @$valuevalue[$k], array('class' => 'chosen')) }}
		</div>
	</div>
	@endif
	@endforeach

	@section('custom_foot')
	@parent
	{{ HTML::style('/cpassets/uploadifive/uploadifive.css') }}
	{{ HTML::script('/cpassets/uploadifive/jquery.uploadifive.min.js') }}
	
	<script>
	$('#photos').uploadifive({
	'auto' : true,
	'multi' : true,
	'formData' : {
	'timestamp' : '{{ time() }}',
	'token' : '{{ md5('SecUre!tN0w' . time()) }}',
	'folder' : 'media/aqar/',
	'fileExt' : 'jpeg,jpg,bmp,gif,png', 
	},
	'queueID' : 'queuephotos',
	'uploadScript' : '{{ url('/upload_files') }}',
	'onUploadComplete' : function(file, data) { 
	var rname=data;
	var fullpath= '{{ url("resize?w=30&h=30&src=media/aqar") }}/'+rname;
	var p='<div class="uploadedImg"><input type="checkbox" checked="checked" value="'+rname+'" name="photos[]" ><img src="'+fullpath+'"><br /><small>'+rname+"</small></div>";
	$('#photos-thumbnails').append(p);
	$('.mesg').fadeOut('slow');	}
	});
	</script>
	@stop

	<div class="control-group">
		{{ Form::label('photos', trans('main.Photos') , array('class' => 'control-label')) }}
		<div class="controls">
			{{ Form::file('photos[]' , array('multiple' => 'true', 'id' => 'photos')) }}
			<div id="photos-thumbnails">
			@if(!empty($photovalue))
				@foreach ($photovalue as $v)
					<div class="uploadedImg">
					<input type="checkbox" checked="checked" value="{{ $v }}" name="photos[]" ><img src="{{ url('resize?w=30&amp;h=30&amp;src=media/aqar/'.$v) }}">
					<br />
					<small>{{ $v }}</small>
					</div>
				@endforeach
			@endif
			</div>
			<div class="clearfix"></div>
			<div id="queuephotos" class="help-inline"></div>
			<div class="mesg">
			@lang('main.Please wait untill upload completing otherwise the photo will not appear')
			</div>

		</div>
	</div>

@stop