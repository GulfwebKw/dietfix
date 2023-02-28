@extends('layouts.main')

@section('contents')
  <?php /* @include('partials.slideshow') */ ?>

    <div class="block-content boxes">
        <div class="row">
            <div class="col-sm-12">

                    @if (!$data->isEmpty())
                    @foreach (break_array($data, 4) as $row)
                    <div class="row">
                    @foreach ($row as $single)
                        <div class="col-md-3">
                            <div class="box background-white">
                                <div class="box-picture">
                                    <a href="{{ url('ads/section/'.$single->id) }}">
                                        @if (!$single->img)
                                        <img src="{{ url('resize?w=320&amp;h=320&amp;r=1&amp;c=1&amp;src=media/default.png')}}" alt="{{ $single->title }}">
                                        @else
                                        <img src="{{ url('resize?w=320&amp;h=320&amp;r=1&amp;c=1&amp;src=media/sections/'.$single->img)}}" alt="{{ $single->title }}">
                                        @endif
                                        <span></span>
                                    </a>
                                </div><!-- /.box-picture -->

                                <div class="box-body">
                                    <h2 class="box-title">
                                        <a href="{{ url('ads/section/'.$single->id) }}">
                                            {{ $single->title }}
                                        </a>
                                    </h2><!-- /.box-title -->
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    @endforeach
                    </div><!-- /.row -->
                    @endforeach

                    @else 
                    <div class="row">
                    <div class="contents center">{{ trans('main.No Data Avilable') }}</div>
                    </div>
                    @endif
                    
                                              
                
            </div>
        </div><!-- /.row -->
    </div><!-- /.boxes -->  

@stop