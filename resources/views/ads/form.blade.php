@extends('layouts.main')


@section('custom_foot')
	{{ HTML::style('/cpassets/uploadifive/uploadifive.css') }}
	{{ HTML::script('/cpassets/uploadifive/jquery.uploadifive.min.js') }}
	{{ HTML::script('//www.appelsiini.net/download/jquery.chained.js') }}
	<script>
	$("#category_id").chained("#section_id");

	$('#category_id, #section_id').on('change', function(){
	    $('#category_id, #section_id').trigger('chosen:updated');
	});


	@if(isset($ad))
		$('#category_id').val('{{ $ad->category_id }}');
	@elseif(null !== Input::old('category_id'))
		$('#category_id').val('{{ Input::old('category_id') }}');
	@endif
	$('#photoss').uploadifive({
	'buttonText' : '{{ trans('main.Upload') }}',
	'auto' : true,
	'multi' : true,
	'formData' : {
	'timestamp' : '{{ time() }}',
	'token' : '{{ md5('SecUre!tN0w' . time()) }}',
	'folder' : 'media/ads/',
	'fileExt' : 'jpeg,jpg,bmp,gif,png',
	},
	'queueID' : 'queuephotoss',
	'uploadScript' : '{{ url('/upload_files') }}',
	'onUploadComplete' : function(file, data) { 
		var rname=data;
		var fullpath= '{{ url("resize?w=80&h=80&src=media/ads") }}'+"/"+rname;
		var link= '{{ url("resize?w=80&h=80&src=media/ads") }}'+"/"+rname;

		// Photo Handling Vars
		// var linkhrefstart = '<a class="fancybox" href="' + link + '">';
		// var linkhrefend = '</a>';
		
		// Multi Field Name Handling
		var fieldname = 'photoss[]';
		
		// Paragraph Setup
		var p = '';
		var p=p+'<div class="uploadedImg">';
		var p=p+'<input type="checkbox" checked="checked" value="'+rname+'" name="' + fieldname + '" > ';
		// var p=p+linkhrefstart;
		var p=p+'<img src="'+fullpath+'">';
		// var p=p+linkhrefend;
		// var p=p+'<br /><small>'+rname+"</small>";
		var p=p+'</div>';

		$('#photoss-thumbnails').append(p);
		
		$('.mesg').fadeOut('slow');	}
	});
	// $('.fancybox').attr('rel', 'media-gallery').fancybox();
	</script>

@stop
@section('contents')

{{ Form::open(array('url' => url('ads/save'), 'class' => 'form-horizontal', 'id' => 'ad-form')) }}
	{{ Form::hidden('user_id',$userid) }}
	{{ Form::hidden('ad_id',$ad_id) }}
	<div class="form-group">
		{{ Form::label('title', trans('main.Title') , array('class' => 'control-label col-sm-4')) }}
		<div class="col-sm-8">
			{{ Form::text('title', (isset($ad) && !Input::old('title')) ? $ad->title : null, array('class' => 'form-control middle', 'placeholder' => trans('main.Title'))) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('details', trans('main.Details') , array('class' => 'control-label col-sm-4')) }}
		<div class="col-sm-8">
			{{ Form::textarea('details', (isset($ad) && !Input::old('details')) ? $ad->details : null, array('class' => 'form-control middle', 'placeholder' => trans('main.Details'))) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('phone', trans('main.Phone') , array('class' => 'control-label col-sm-4')) }}
		<div class="col-sm-8">
			{{ Form::text('phone', (isset($ad) && !Input::old('phone')) ? $ad->phone : null, array('class' => 'form-control middle', 'placeholder' => trans('main.Phone'))) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('whatsapp', trans('main.Whatsapp') , array('class' => 'control-label col-sm-4')) }}
		<div class="col-sm-8">
			{{ Form::text('whatsapp', (isset($ad) && !Input::old('whatsapp')) ? $ad->whatsapp : null, array('class' => 'form-control middle', 'placeholder' => trans('main.Whatsapp'))) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('price', trans('main.Price') , array('class' => 'control-label col-sm-4')) }}
		<div class="col-sm-8">
			{{ Form::text('price', (isset($ad) && !Input::old('price')) ? $ad->price : null, array('class' => 'form-control middle', 'placeholder' => trans('main.Price'))) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('province_id', trans('main.Province') , array('class' => 'control-label col-sm-4')) }}
		<div class="col-sm-8">
			{{ Form::select('province_id', $provinces, (isset($ad) && !Input::old('province_id')) ? $ad->province_id : null, array('class' => 'form-control middle', 'placeholder' => trans('main.Province'))) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('section_id', trans('main.Section') , array('class' => 'control-label col-sm-4')) }}
		<div class="col-sm-8">
			{{ Form::select('section_id', $sections, (isset($ad) && !Input::old('section_id')) ? $ad->section_id : null, array('class' => 'form-control middle', 'placeholder' => trans('main.Section'))) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('category_id', trans('main.Category') , array('class' => 'control-label col-sm-4')) }}
		<div class="col-sm-8">
			<select name="category_id" id="category_id" class="form-control middle">
				@foreach ($categories as $section)
					@foreach ($section->categories as $category)
						<option value="{{ $category->id }}" class="{{ $section->id }}">{{ $category->title }}</option>
					@endforeach
				@endforeach
			</select>
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('photoss', trans('main.Photos') , array('class' => 'control-label col-sm-4')) }}
		<div class="col-sm-8">
			{{ Form::file('photoss' , array('multiple' => 'true')) }}
			
			<div id="photoss-thumbnails">
				@if(isset($ad) && !$ad->photos->isEmpty())
					@foreach ($ad->photos as $photo)
					<div class="uploadedImg">
					<input type="checkbox" checked="checked" value="{{ $photo->photo }}" name="photoss[]" > 
					<img src="{{ url('resize?w=80&amp;h=80&amp;src=media/ads/'.$photo->photo) }}">
					</div>
					@endforeach
				@elseif(null !== Input::old('photoss') && !empty(Input::old('photoss')))
					@foreach (Input::old('photoss') as $photo)
					<div class="uploadedImg">
					<input type="checkbox" checked="checked" value="{{ $photo }}" name="photoss[]" > 
					<img src="{{ url('resize?w=80&amp;h=80&amp;src=media/ads/'.$photo) }}">
					</div>
					@endforeach
				@endif
			</div>
			<div class="clearfix"></div>
			<div id="queuephotoss" class="help-inline"></div>
		</div>
	</div>
	<div class="form-actions center">
		{{ Form::button('<i class="fa fa-check"></i> '. trans('main.Save'), array('type' => 'submit', 'class' => 'btn col-md-4 col-md-push-4 blue')) }}
	</div>
{{ Form::close() }}
@stop