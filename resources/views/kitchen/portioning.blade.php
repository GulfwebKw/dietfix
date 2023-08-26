@extends('layouts.main')
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

	{{ Form::open(array('url' => url('kitchen/get-'.$type), 'method' => 'get', 'class' => 'form-inline form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form')) }}

		{{ Form::label('date', trans('main.Date')) }}
		<div class="control-group form-group">
			<div class="controls">
				{{ Form::text('date', null, array('class' => 'datepicker form-control','id' => 'date')) }}
			</div>
		</div>


		<div class="control-group form-group">
			{{ Form::button('<i class="fa fa-search"></i>', array('class' => 'btn btn-primary','id' => 'submit','type' => 'submit')) }}
		</div>

	{{ Form::close() }}

	<h2 class="text-center">{{ trans('main.'.ucfirst($type)) }}</h2>
	<h3 class="text-center">{{ Input::get('date') }}</h3>
	<div class="table-responsive">
		<table class="table table-hover">
			<tbody>
				@if ($orders->isEmpty())
					<tr>
						<td>{{ trans('main.No Results') }}</td>
					</tr>
				@else
					@foreach ($orders->meals as $meal)
					<tr>
						<th colspan="5" class="text-center"><h3>{{ $meal['meal']->{'title'.LANG} }}</h3></th>
					</tr>
					<tr>
						<th>{{ trans('main.ID') }}</th>
						<th>{{ trans('main.Meal') }}</th>
						<th>{{ trans('main.Portion') }}</th>
						<th>{{ trans('main.Salt') }}</th>
					</tr>
						@foreach ($meal['orders'] as $order)
						<tr>
							<td>{{ $order->user->id }}</td>
							<td>{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}</td>
							<td>{{ ($order->portion) ? $order->portion->{'title'.LANG} : 1 }}</td>
							<td>{{ $order->user->salt }}</td>
						</tr>
						@endforeach
                        <tr>
                            <th colspan="5">
                                {{ trans('main.Notes') }}:
                                @foreach ($meal['orders'] as $order)
                                    @if (!$order->addons->isEmpty())
                                        <br>#{{ $order->user->id }}:
                                        @foreach ($order->addons as $addon)
                                           {{ $addon->{'title'.LANG} }} @if( ! $loop->last) - @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </th>
                        </tr>
					@endforeach
			</tbody>
				@endif
		</table>
	</div>
@stop
