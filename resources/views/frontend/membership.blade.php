@extends('layouts.frontend')
@section('content')

    @component('frontend.components.breadcrumb')
        @slot('title')
            {{$item->titleEn}}
        @endslot
    @endcomponent
    {!! $item->contentEn !!}
@endsection