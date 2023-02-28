@extends('layouts.main')

@section('contents')

    <div class="container">
        <div class="row text-center">
            <div class="col-sm-12">

                    @if (!$data->isEmpty())
                    @foreach (break_array($data, 4) as $row)
                    <div class="row">
                    @foreach ($row as $single)
                        <div class="work-element col-xs-12 col-sm-3 col-md-3">
                          <a href="{{ url('single/view/'.$single->id) }}" class="work">
                            <img class="replace-2x" src="{{ url('resize?w=270&amp;h=270&amp;r=1&amp;c=1&amp;src=media/categories/'.$single->img) }}" width="270" height="197" alt="">
                            <span class="shadow"></span>
                            <div class="bg-hover"></div>
                            <div class="work-title">
                              <h3 class="title">{{ $single->{'title'.LANG} }}</h3>
                            </div>
                          </a>
                        </div>
                    @endforeach
                    </div>
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