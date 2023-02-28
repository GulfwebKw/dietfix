<h3>{{ trans('main.Packages') }}</h3>

<table class="table table-responsive table-bordered">
	<thead>
		<tr>
			<td>{{ trans('main.Title') }}</td>
			<td>{{ trans('main.Details') }}</td>
			<td>{{ trans('main.Price') }}</td>
			<td>{{ trans('main.Period') }}</td>
			<td>{{ trans('main.Order') }}</td>
		</tr>
	</thead>
	@if ($data->isEmpty())
	<tbody>
		<tr>
			<td colspan="5">
				{{ trans('main.No Results') }}
			</td>
		</tr>
	</tbody>
	@else
	<tbody>
		@foreach ($data as $pro)
		<tr>
			<td>{{ $pro->{'title'.LANG} }}</td>
			<td>{{ $pro->{'details'.LANG} }}</td>
			<td>{{ $pro->price }}</td>
			<td>{{ $periods[$pro->period] }}</td>
			<td><a href="{{ url('orders/add/'.$pro->id) }}" class="btn btn-primary">{{ trans('main.Order') }}</a></td>
		</tr>
		@endforeach
	</tbody>
	@endif
</table>

<div class="line"></div>
