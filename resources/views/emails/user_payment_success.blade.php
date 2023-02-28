@extends('emails.layouts.main')

@section('contents')

    @if($status==1)
         <h2>{{ trans('main.Payment Successfully') }}</h2>
     @else
        <h2 style="color:#800">{{ trans('main.Payment Failed') }}</h2>
     @endif


<table>
	<tr>
		<th>{{ trans('main.Payment ID') }}</th>
		<td>{{ $payment->paymentID }}</td>
	</tr>
	<tr>
		<th>{{ trans('main.Result') }}</th>
        @if($payment->paid==1)
            <td style="color: green">Success</td>
            @else
            <td style="color: red">Failed</td>
        @endif

	</tr>
	<tr>
		<th>{{ trans('main.Created Date') }}</th>
		<td>{{ $payment->created_at }}</td>
	</tr>
	<tr>
		<th>{{ trans('main.Username') }}</th>
		<td>{{ optional($payment->user)->username }}</td>
	</tr>
	<tr>
		<th>{{ trans('main.Amount') }}</th>
		<td>{{$payment->total}}</td>
	</tr>
	<tr>
		<th>{{ trans('main.RefId') }}</th>
		<td>{{ $payment->ref }}</td>
	</tr>
</table>

@endsection
