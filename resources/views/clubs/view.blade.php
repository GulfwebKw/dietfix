@extends('layouts.main')

@section('contents')

	@include('partials.sidebar')
	<div class="col_three_fourth col_last">

		<div class="col_three_fifth">

	        <div class="heading-block">
	            <h3>{{ $item->{'title'.LANG} }}</h3>
	            <span class="alert alert-info">{{ $item->{'notes'.LANG} }}</span>
	        </div>

	        <p>{{ $item->{'details'.LANG} }}</p>

			@include('partials.sharing')
	    </div>

	    <div class="col_two_fifth topmargin col_last">
	        @if ($item->logo)
                    <img src="{{ url('resize?w=400&h=400&r=1&c=1&src=media/clubs/' . $item->logo) }}" alt="{{ $item->{'title'.LANG} }}">
            @else
                <img src="{{ url('resize?w=400&h=400&r=1&c=1&src=media/default.png') }}" alt="{{ $item->{'title'.LANG} }}">
            @endif
	    </div>

	    <div class="line"></div>

		@include('partials.packages_table')

		
		@if (!$item->activities->isEmpty())
		<div class="col-md-12 col-padding10 text-center dark" style="background:rgb(90, 90, 90);">
		
		@foreach ($item->activities as $activity)
			<div class="col-md-6 text-center" style="width:{{ 100 / $item->activities->count() }}%;">{{ $activity->{'title'.LANG} }}</div>
		@endforeach

	    </div>

	    <div class="line"></div>
		@endif
	
		@include('partials.view_footer')
		@include('partials.comments')
	</div>


@stop