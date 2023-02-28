@extends('emails.layouts.main')
@section('contents')
    <div align="center">
        {{--        <p>Telephone: {{ $_setting['phone'] }}</p>--}}
        {{--        <p>Website: {{ url('') }}</p>--}}
        {{--        <p>Mobile: {{ $_setting['mobile'] }}</p>--}}
    </div>
    <h2>Password Reset</h2>

    <div>
        To reset your password, complete this form: {{ URL::to('user/reset', array($token)) }}.
    </div>
@stop
