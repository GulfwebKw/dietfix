@extends('admin.layouts.main')

@section('custom_foot')
	{{ HTML::script('cpassets/js/jquery.chained.min.js') }}
	<script>
		jQuery(document).ready(function($) {

			// $("#ad").chained("#province");
			$("#product").chained("#shop");
			$("#user").chained("#country");
			// $("#user").chained("#country");

		});
	</script>
@stop

@section('content')
@if ($sent)
	@if ($result)
	<div class="alert alert-success alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  {{ trans('main.Send Successfully') }}
	</div>
	@else
	<div class="alert alert-danger alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  {{ trans('main.Send Failed') }}
	  <br>
	  {{ trans('main.Reasons') }}:
	  @foreach ($reasons as $reason)
	  	{{ $reason }}
	  @endforeach
	</div>
	@endif
@endif


{{ Form::open(array('url' => url(ADMIN_FOLDER.'/push'), 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form')) }}

<h3>{{ trans('main.Send What?') }}</h3>
<div class="control-group form-group">
	{{ Form::label('shop', trans('main.Shop') , array('class' => 'control-label col-sm-4')) }}
	<div class="controls col-sm-8">
		<select name="shop" id="shop" class="form-control nochosen">
			<option value="-1">{{ trans('main.Text') }}</option>
			@foreach ($shops as $shop)
				<option value="{{ $shop->id }}">{{ $shop->{'title'.LANG} }}</option>
			@endforeach
		</select>
	</div>
</div>
<div class="control-group form-group">
	{{ Form::label('product', trans('main.Product') , array('class' => 'control-label col-sm-4')) }}
	<div class="controls col-sm-8">
		<select name="product" id="product" class="form-control nochosen">
			@foreach ($shops as $shop)
				<option value="-1" class="{{ $shop->id }}">{{ trans('main.All') }}</option>
				@foreach ($shop->categories as $product)
					<option value="{{ $product->id }}" class="{{ $shop->id }}">{{ $product->{'title'.LANG} }}</option>
				@endforeach
			@endforeach
		</select>
	</div>
</div>
<div class="text-center">OR</div>
<div class="control-group form-group">
	{{ Form::label('banner', trans('main.Banner') , array('class' => 'control-label col-sm-4')) }}
	<div class="controls col-sm-8">
		<select name="banner" id="banner" class="form-control nochosen">
			<option value="-1">{{ trans('main.Text') }}</option>
			@foreach ($banners as $banner)
				<option value="{{ $banner->id }}">{{ $banner->url }}</option>
			@endforeach
		</select>
	</div>
</div>

<div class="control-group form-group">
	{{ Form::label('sendtext', trans('main.Send Text') , array('class' => 'control-label col-sm-4')) }}
	<div class="controls col-sm-8">
		<div class="switch" data-on="success" data-off="danger">
		{{ Form::checkbox('sendtext' , 1, true , array('class' => 'toggle')) }}
		</div>
	</div>
</div>
	
<div class="control-group form-group">
	{{ Form::label('text', trans('main.Text') , array('class' => 'control-label col-sm-4')) }}
	<div class="controls col-sm-8">
		<textarea name="text" id="text" class="form-control" rows="5" cols="50"></textarea>
	</div>
</div>


<h3>{{ trans('main.For Whom?') }}</h3>

<div class="control-group form-group">
	{{ Form::label('country', trans('main.Country') , array('class' => 'control-label col-sm-4')) }}
	<div class="controls col-sm-8">
		<select name="country" id="country" class="form-control nochosen">
			<option value="-1">{{ trans('main.All') }}</option>
			@foreach ($countries as $country)
				<option value="{{ $country->id }}">{{ $country->countryName }}</option>
			@endforeach
		</select>
	</div>
</div>

<div class="control-group form-group">
	{{ Form::label('user', trans('main.User') , array('class' => 'control-label col-sm-4')) }}
	<div class="controls col-sm-8">
		<select name="user" id="user" class="form-control nochosen">
			@foreach ($countries as $country)
				<option value="-1" class="{{ $country->id }}">{{ trans('main.All') }}</option>
				@foreach ($country->users as $user)
					<option value="{{ $user->id }}" class="{{ $country->id }}">{{ $user->phone }}</option>
				@endforeach
			@endforeach
		</select>
	</div>
</div>


<div class="form-actions">
	{{ Form::button('<i class="icon-ok"></i> '.trans('main.Send'), array('type' => 'submit', 'class' => 'btn blue')) }}
</div>

{{ Form::close() }}
@stop