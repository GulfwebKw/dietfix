@extends('layouts.main')



@section('contents')

    <div class="portlet box blue">


        <div class="portlet-body">

            <table class="table table-striped table-hover">

                <tbody>

                    <tr>
                        <th>{{ trans('Payment ID') }}</th>
                        <td>{{ $payment->paymentID }}</td>
                    </tr>
                    @if ($payment->status == 1)
                        <tr>
                            <th>{{ trans('Result') }}</th>
                            <td>Success</td>
                        </tr>
                    @endif
                    <tr>
                        <th>{{ trans('Post Date') }}</th>
                        <td>{{ $payment->created_at }}</td>
                    </tr>
                    {{-- <tr> --}}
                    {{-- <th>{{ trans('main.Order ID') }}</th> --}}
                    {{-- <td><a href="{{ url('orders/view/'.$payment->order_id) }}">{{ $payment->order_id }}</a></td> --}}
                    {{-- </tr> --}}
                    <tr>
                        <th>{{ trans('Amount') }}</th>
                        <td>{{ $payment->total }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('Ref ID') }}</th>
                        <td>{{ $payment->ref_id }}</td>
                    </tr>
                    @if (isset($giftTitle))
                        <tr>
                            <th>{{ trans('Gift') }}</th>
                            <td>{{ $giftTitle }}</td>
                        </tr>
                    @endif
                </tbody>

            </table>

        </div>

    </div>

@stop
