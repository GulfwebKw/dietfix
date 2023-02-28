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

	@if ($orders->isEmpty())
		<div>
			{{ trans('main.No Results') }}
		</div>
	@else
		@foreach ($orders->users as $user)
        
        <?php $userdoctor = DB::table('users')->where('id', $user['user']->doctor_id)->first();?>
		<div class="page">
		<div class="table-responsive">
			<table class="table table-hover">
				<tbody>
						<tr style="font-size: large; font-family:Tahoma,Arial, Helvetica, sans-serif;">
							<th colspan="1"><h2>{{ $user['user']->id }}</h2></th>
							<th colspan="2"><h2>{{ optional($user['user'])->username }}</h2></th>
							<th colspan="1"><h2>{{ optional($user['user'])->mobile_number }}</h2></th>
						</tr>
						@if(isset($user['user']->note) &&  $user['user']->note!="")
							<tr  style="font-size: 12px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
								<th colspan="2">
									<p>
										{{$user['user']->note}}
									</p>
								</th>
							</tr>
					     @endif

						<tr style="font-size: 20px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
							<th colspan="2">
								<p>
									@if($weekEndAddress)
										{{ optional($user['user']->countryWeekends)->{'title'.LANG} }} ,  {{ optional($user['user']->provinceWeekends)->{'title'.LANG} }} , {{ optional($user['user']->areaWeekends)->{'title'.LANG} }} , Block:{{ $user['user']->block_work }} , Street:{{ $user['user']->street_work }} , Avenue: {{ $user['user']->avenue_work }} , HouseNumber:{{ $user['user']->house_number_work }} ,  Floor:{{ $user['user']->floor_work }}<br>
										Address:{{ $user['user']->address_work }}
										@else
										{{ $user['user']->country->{'title'.LANG} }} ,  {{ $user['user']->province->{'title'.LANG} }} , {{ $user['user']->area->{'title'.LANG} }} , Block:{{ $user['user']->block }} , Street:{{ $user['user']->street }} , Avenue:{{ $user['user']->avenue }} , HouseNumber:{{ $user['user']->house_number }} ,  Floor:{{ $user['user']->floor }}<br>
										Address:{{ $user['user']->address }}

{{--										@if(strlen($user['user']->delivery)>4)--}}
{{--												<small>({{ $user['user']->delivery }})</small>--}}
{{--											@endif--}}
									@endif




								</p>
							</th>
							<th colspan="2">
								<h2 class="text-center">{{ Input::get('date') }}</h2>Dietitian: <?php echo optional($userdoctor)->username;?></th>
						</tr>
						<tr style="font-size: large;font-family:Tahoma,Arial, Helvetica, sans-serif;">
							<th>{{ trans('main.Meal') }}</th>
							<th>{{ trans('main.Portion') }}</th>
							<th>{{ trans('main.Notes') }}</th>
							<th>{{ trans('main.Salt') }}</th>
						</tr>
							@foreach ($user['orders'] as $order)
							<tr style="font-size:  16px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
								<td>{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}</td>
								<td>{{ ($order->portion) ? $order->portion->{'title'.LANG} : 1 }}</td>
								<td>
									@if (!$order->addons->isEmpty())
										@foreach ($order->addons as $addon)
											{{ $addon->{'title'.LANG} }}
										@endforeach
									@endif
								</td>
								<td>{{ $order->user->salt }}</td>
							</tr>
							@endforeach
					</tbody>
					
			</table>
		</div>
		</div>
		<div class="page-break"></div>
		@endforeach
	@endif
@stop