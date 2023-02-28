@extends('layouts.main')

@section('contents')
@if (Auth::user())
  <div style='min-height:200px;' class="text-center">Welcome back to Dashboard</div>
@endif
@stop
