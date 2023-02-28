@extends('layouts.main')
@section('head_title')
@stop
@section('contents')

<div class="container clearfix">
	@include('partials.sidebar')
	<div class="col_three_fourth col_last">

@if (!$items->isEmpty())
 <div class="fancy-title title-border">
      <h3>{{ trans('main.Recent Clubs\\Camps') }}</h3>
  </div>
        @foreach (break_array($items, 3) as $row)
          @foreach ($row as $club)
            @include('clubs.short')
          @endforeach
          <div class="clearfix"></div>
        @endforeach

{{ $items->links() }}

@else
  <div class="fancy-title title-border">
      <h3>{{ trans('main.No Results') }}</h3>
      
  </div>
  @if (Request::segment(1) == 'search')
      @include('search.club')
    @endif
@endif

</div>
</div><!-- .container clearfix -->

@stop