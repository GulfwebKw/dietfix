@extends('layouts.main')


@section('contents')
	
	@if (Auth::user()->role_id == 2)
		{{-- teachers --}}
		@include('teachers.user')
	@endif
	@if (Auth::user()->role_id == 3)
		{{-- schools --}}
		@include('schools.user')
	@endif
	@if (Auth::user()->role_id == 4)
		{{-- clubs --}}
		@include('clubs.user')
	@endif

@stop