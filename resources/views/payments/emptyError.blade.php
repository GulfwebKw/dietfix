@extends('layouts.main')



@section('contents')

    <div class="portlet box red">

        <div class="portlet-title">

            <div class="caption"><i class="icon-money"></i>{{ trans('main.Payment Failed') }}</div>

        </div>

        <div class="portlet-body">

                <p>{{$message}}</p>

        </div>

    </div>

@stop
