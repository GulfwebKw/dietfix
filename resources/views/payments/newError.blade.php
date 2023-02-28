@extends('layouts.main')



@section('contents')

    <div class="portlet box red">

        <div class="portlet-title">

            <div class="caption"><i class="icon-money"></i>{{ trans('main.Payment Failed') }}</div>
            @if(isset($message))
              <div class="caption"><i class="icon-money"></i>{{ $message }}</div>
            @endif

        </div>

        <div class="portlet-body">

            <table class="table table-striped table-hover">

                <tbody>

                <tr>
                    <th>{{ trans('Payment ID') }}</th>
                    <td>{{ $payment->paymentID }}</td>
                </tr>
                <tr>
                    <th>{{ trans('Post Date') }}</th>
                    <td>{{ $payment->created_at }}</td>
                </tr>
{{--                <tr>--}}
{{--                    <th>{{ trans('main.Order ID') }}</th>--}}
{{--                    <td><a href="{{ url('orders/view/'.$payment->order_id) }}">{{ $payment->order_id }}</a></td>--}}
{{--                </tr>--}}
                <tr>
                    <th>{{ trans('Amount') }}</th>
                    <td>{{ $payment->total }}</td>
                </tr>

                <tr>
                    <th>{{ trans('Ref ID') }}</th>
                    <td>{{ $payment->ref_id }}</td>
                </tr>
                <tr>
                    <th>{{ trans('Error') }}</th>
                    <td>{{ $payment->description }}</td>
                </tr>

                </tbody>

            </table>

        </div>

    </div>

@stop
