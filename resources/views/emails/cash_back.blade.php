@extends('emails.layouts.main')

@section('contents')

    <h2>{{ trans('main.Cash_Back_Request') }}</h2>


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
