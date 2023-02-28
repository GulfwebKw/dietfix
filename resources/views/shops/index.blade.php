@extends('layouts.main')

@section('contents')
	
    <div class="container">
        <div class="row text-center">
            <div class="col-sm-12">

                    @if (!$data->isEmpty())
                    @foreach (break_array($data, 4) as $row)
                    <div class="row">
                    @foreach ($row as $shop)
                        @include('shops.shop_short')
                    @endforeach
                    </div>
                    @endforeach

                    @else 
                    <div class="row">
                    <div class="contents center">{{ trans('main.No Data Available') }}</div>
                    </div>
                    @endif
                    
                                              
            </div>
        </div><!-- /.row -->
    </div><!-- /.boxes -->  

@stop