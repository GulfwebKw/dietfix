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
				{{ Form::text('date', request()->get('date'), array('class' => 'datepicker form-control','id' => 'date')) }}
			</div>
		</div>
		{{ Form::label('driver', 'Driver') }}
		<div class="control-group form-group">
			<div class="controls">
                <select name="driver" id="driver" class="form-control chosen">
                    <option value="">All</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" @if(request()->get('driver' , false) == $driver->id ) selected @endif>{{ $driver->{'title'.LANG} }}</option>
                    @endforeach
                </select>
            </div>
		</div>


		<div class="control-group form-group">
			{{ Form::button('<i class="fa fa-search"></i>', array('class' => 'btn btn-primary','id' => 'submit','type' => 'submit')) }}
		</div>
    <a href="{{ request()->fullUrlWithQuery(['download' => '1']) }} " class="btn btn-primary"><i class="fa fa-download"></i> Download</a>


	{{ Form::close() }}

	@if (count($orders->users) == 0 )
		<div>
			{{ trans('main.No Results') }}
		</div>
	@else
	<h2 class="text-center">{{ trans('main.'.ucfirst($type)) }}</h2>
	<h3 class="text-center">{{ Input::get('date') }}</h3>
		<div class="table-responsive">
			<table class="table table-hover">
				<tbody>
						<tr>
							<th class="text-center">{{ trans('main.ID') }}</th>
							<th class="text-center">{{ trans('main.Username') }}</th>
							<th class="text-center">{{ trans('main.Phone') }}</th>
							<th class="text-center">{{ trans('main.Address') }}</th>
						</tr>
						@foreach ($orders->users as $province)
						@foreach ($province as $area)
						@foreach ($area as $user)
						<tr style="font-size: large;">
							<td class="text-center">
								{{ $user->id }}
							</td>
							<td class="text-center">
								{{ $user->username }}
							</td>
							<td class="text-center">
								{{ $user->mobile_number }}
							</td>
							<td class="text-center">
								@if($weekEndAddress)
									{{ optional($user->countryWeekends)->{'title'.LANG} }}<br>
									{{ optional($user->provinceWeekends)->{'title'.LANG} }}<br>
									{{ optional($user->areaWeekends)->{'title'.LANG} }}<br>,
									Block:{{ $user->block_work }}<br>
									Street:{{ $user->street_work }}<br>
									Avenue:{{ $user->avenue_work }}<br>
									HouseNumber:{{ $user->house_number_work }}<br>
									Floor:{{ $user->floor_work }}<br>
									Address:{{ $user->address_work }}
									@else
									{{ $user->country->{'title'.LANG} }}<br>
									{{ $user->province->{'title'.LANG} }}<br>
									{{ $user->area->{'title'.LANG} }}<br>
									Block:{{ $user->block }}<br>
									Street:{{ $user->street }}<br>
									Avenue:{{ $user->avenue }}<br>
									HouseNumber:{{ $user->house_number }}<br>
									Floor:{{ $user->floor }}<br>
									Address:{{ $user->address }}
								@endif

							</td>
						</tr>
						@endforeach
						@endforeach
						@endforeach
					</tbody>
			</table>
		</div>
	@endif
@stop
