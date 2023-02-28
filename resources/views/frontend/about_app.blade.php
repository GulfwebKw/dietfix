@extends('layouts.appFrontend')

@section('content')
@if(!empty(Request()->lang) && Request()->lang=="en")
{!! $item->contentEn !!}
@elseif(!empty(Request()->lang) && Request()->lang=="ar")
<div dir="rtl" align="right">{!! $item->contentAr !!}</div>
@else
{!! $item->contentEn !!}
@endif
@endsection