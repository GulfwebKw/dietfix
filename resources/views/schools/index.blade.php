@extends('layouts.main')
@section('head_title')
@stop
@section('contents')



<div class="container clearfix">
@include('partials.sidebar')
	<div class="col_three_fourth col_last">

@if (!$items->isEmpty())
    <div class=" bothsidebar nobottommargin">
        <div class="fancy-title title-border">
            <h3>{{ trans('main.Recent KGs\\Schools') }}</h3>
        </div>
        <div id="posts" class="events small-thumbs">
          @foreach ($items as $school)
            @include('schools.short2')
          @endforeach
        </div>
    </div>


{{ $items->links() }}
@else
  <div class="fancy-title title-border">
      <h3>{{ trans('main.No Results') }}</h3>
      
  </div>
  @if (Request::segment(1) == 'search')
      @include('search.school')
    @endif
@endif
</div>
</div><!-- .container clearfix -->

@stop