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
                <img src="{{ url('resize?w=400&h=400&r=1&c=1&src=media/schools/' . $item->logo) }}" alt="{{ $item->{'title'.LANG} }}">
            @else
                <img src="{{ url('resize?w=400&h=400&r=1&c=1&src=media/default.png') }}" alt="{{ $item->{'title'.LANG} }}">
            @endif
	    </div>

	    <div class="line"></div>

		<div class="col-md-4">
			<h3>{{ trans('main.Activites') }}</h3>
			@if (!$item->activities->isEmpty())
			<ul>
				@foreach ($item->activities as $activity)
					<li>{{ $activity->{'title'.LANG} }}</li>
				@endforeach
			</ul>
			@endif
		</div>
		<div class="col-md-4">
			<h3>{{ trans('main.Grades') }}</h3>
			@if (!$item->ages->isEmpty())
			<ul>
				@foreach ($item->ages as $age)
					<li>{{ $age->{'title'.LANG} }}</li>
				@endforeach
			</ul>
			@endif
		</div>
		<div class="col-md-4">
			<h3>{{ trans('main.Genders') }}</h3>
			@if (!$item->genres->isEmpty())
			<ul>
				@foreach ($item->genres as $genre)
					<li>{{ $genre->{'title'.LANG} }}</li>
				@endforeach
			</ul>
			@endif
		</div>

	    <div class="line"></div>

	    
		@include('partials.packages_table')


		@include('partials.view_footer')
		@include('partials.comments')

	</div>

@stop