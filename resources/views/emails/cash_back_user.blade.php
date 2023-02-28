@extends('emails.layouts.main')

@section('contents')

    @if($status==1)
       <h2>{{ trans('main.Accept_Cash_Back_By_Admin') }}</h2>
       @else
        <h2>{{ trans('main.Reject_Cash_Back_By_Admin') }}</h2>
    @endif


    <table>
        <tr>
            <th>{{ trans('main.Username') }}</th>
            <td>{{ optional($user->user)->username }}</td>
        </tr>
        <tr>
            <th>{{ trans('main.Mobile') }}</th>
            <td>{{ optional($user->user)->mobile }}</td>
        </tr>
        <tr>
            <th>{{ trans('main.Amount') }}</th>
            <td>{{$cashBack->value}}</td>
        </tr>
        <tr>
            <th>{{ trans('main.Created Date') }}</th>
            <td>{{ $cashBack->created_at }}</td>
        </tr>

    </table>

@endsection
