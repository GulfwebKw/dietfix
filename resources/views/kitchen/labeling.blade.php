@extends('layouts.blank')
@section('custom_head_include')
{{ HTML::style('assets/datepicker/css/datepicker.css') }}
@stop
@section('custom_foot')
{{ HTML::script('http://momentjs.com/downloads/moment.js') }}
{{ HTML::script('assets/datepicker/js/bootstrap-datepicker.js') }}

<script>
		var date = $('#date').datepicker({
		  onRender: function(date) {
		  	return '';
		    // return (date.valueOf() >= moment()) ? '' : 'disabled';

		  },
		  format: 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
		  date.hide();
		}).data('datepicker');

</script>
@stop

@section('contents')
	
	@if ($orders->isEmpty())
		<div>
			{{ trans('main.No Results') }}
		</div>
	@else
		<div class="page">
			<?php $i = 0; ?>
			@foreach ($orders as $order)
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}<br>
				{{ ($order->portion) ? $order->portion->{'title'.LANG} : '' }} {{ Input::get('date') }}<br>
				{{ $order->user->salt }}<br>
				@if (!$order->addons->isEmpty())
					@foreach ($order->addons as $addon)
						{{ $addon->{'title'.LANG} }},
					@endforeach
				@endif
			</div>
			<?php $i++ ?>
			@if ($i%4 == 0)
				<div class="clearfix"></div>
			@endif
			@if ($i%44 == 0)
				</div><div class="page">
			@endif
			@endforeach
			@foreach ($orders as $order)
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}<br>
				{{ ($order->portion) ? $order->portion->{'title'.LANG} : '' }} {{ Input::get('date') }}<br>
				{{ $order->user->salt }}<br>
				@if (!$order->addons->isEmpty())
					@foreach ($order->addons as $addon)
						{{ $addon->{'title'.LANG} }},
					@endforeach
				@endif
			</div>
			<?php $i++ ?>
			@if ($i%4 == 0)
				<div class="clearfix"></div>
			@endif
			@if ($i%44 == 0)
				</div><div class="page">
			@endif
			@endforeach
			@foreach ($orders as $order)
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}<br>
				{{ ($order->portion) ? $order->portion->{'title'.LANG} : '' }} {{ Input::get('date') }}<br>
				{{ $order->user->salt }}<br>
				@if (!$order->addons->isEmpty())
					@foreach ($order->addons as $addon)
						{{ $addon->{'title'.LANG} }},
					@endforeach
				@endif
			</div>
			<?php $i++ ?>
			@if ($i%4 == 0)
				<div class="clearfix"></div>
			@endif
			@if ($i%44 == 0)
				</div><div class="page">
			@endif
			@endforeach
			@foreach ($orders as $order)
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}<br>
				{{ ($order->portion) ? $order->portion->{'title'.LANG} : '' }} {{ Input::get('date') }}<br>
				{{ $order->user->salt }}<br>
				@if (!$order->addons->isEmpty())
					@foreach ($order->addons as $addon)
						{{ $addon->{'title'.LANG} }},
					@endforeach
				@endif
			</div>
			<?php $i++ ?>
			@if ($i%4 == 0)
				<div class="clearfix"></div>
			@endif
			@if ($i%44 == 0)
				</div><div class="page">
			@endif
			@endforeach
			@foreach ($orders as $order)
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}<br>
				{{ ($order->portion) ? $order->portion->{'title'.LANG} : '' }} {{ Input::get('date') }}<br>
				{{ $order->user->salt }}<br>
				@if (!$order->addons->isEmpty())
					@foreach ($order->addons as $addon)
						{{ $addon->{'title'.LANG} }},
					@endforeach
				@endif
			</div>
			<?php $i++ ?>
			@if ($i%4 == 0)
				<div class="clearfix"></div>
			@endif
			@if ($i%44 == 0)
				</div><div class="page">
			@endif
			@endforeach
			@foreach ($orders as $order)
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}<br>
				{{ ($order->portion) ? $order->portion->{'title'.LANG} : '' }} {{ Input::get('date') }}<br>
				{{ $order->user->salt }}<br>
				@if (!$order->addons->isEmpty())
					@foreach ($order->addons as $addon)
						{{ $addon->{'title'.LANG} }},
					@endforeach
				@endif
			</div>
			<?php $i++ ?>
			@if ($i%4 == 0)
				<div class="clearfix"></div>
			@endif
			@if ($i%44 == 0)
				</div><div class="page">
			@endif
			@endforeach
			@foreach ($orders as $order)
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}<br>
				{{ ($order->portion) ? $order->portion->{'title'.LANG} : '' }} {{ Input::get('date') }}<br>
				{{ $order->user->salt }}<br>
				@if (!$order->addons->isEmpty())
					@foreach ($order->addons as $addon)
						{{ $addon->{'title'.LANG} }},
					@endforeach
				@endif
			</div>
			<?php $i++ ?>
			@if ($i%4 == 0)
				<div class="clearfix"></div>
			@endif
			@if ($i%44 == 0)
				</div><div class="page">
			@endif
			@endforeach
		</div>
	@endif
@stop