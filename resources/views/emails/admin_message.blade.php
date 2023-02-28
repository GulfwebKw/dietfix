@extends('emails.layouts.main')

@section('contents')

<h2>رسالة من موقعك</h2>



<div>

المرسل {{ $theMessage['name'] }}<br />الهاتف {{ $theMessage['phone'] or '' }}<br />البريد {{ $theMessage['email'] }}<br />الرسالة <br> {{ $theMessage['user_message'] }}

</div>

@stop