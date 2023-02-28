@extends('layouts.main')

@section('contents')

	
	<table class="table table-bordered table-striped table-condensed table-hover">
		<thead>
			<tr>
				<th>{{ trans('main.ID') }}</th>
				<th>{{ trans('main.Order') }}</th>
				<th>{{ trans('main.PaymentID') }}</th>
				<th>{{ trans('main.Type') }}</th>
				<th>{{ trans('main.Total') }}</th>
				<th>{{ trans('main.Paid?') }}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		@if (!$payments->isEmpty())
			@foreach ($payments as $payment)
			<tr>
				<td>{{ $payment->id }}</td>
				<td>{{ $payment->order_id }}</td>
				<td>{{ $payment->paymentID }}</td>
				<td>{{ $payment->type }}</td>
				<td>@price($payment->total)</td>
				<td>{{ ($payment->paid == 1) ? trans('main.Yes') : trans('main.No') }}</td>
				<td>
					@if($payment->paid == 1)
					<a href="{{ url('payments/success/'.$payment->id) }}" class="btn btn-success">{{ trans('main.View') }}</a>
					@else
					<a href="{{ url('payments/error/'.$payment->id) }}" class="btn btn-danger">{{ trans('main.View') }}</a>
					@endif
				</td>
			</tr>
			@endforeach
		@else
			<tr>
				<td colspan="6">
					{{ trans('main.No Results') }}

				</td>
			</tr>
		@endif
		</tbody>
	</table>

	{{ $payments->links() }}
@stop