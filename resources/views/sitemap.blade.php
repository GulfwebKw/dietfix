@extends('layouts.main')
@section('head_title')
@stop
@section('contents')
  
  <ul>
    <li><a href="{{ url('') }}">{{ trans('main.Home') }}</a></li>
    <li>
      <a href="#">{{ trans('main.Pages') }}</a>
      @if (!$pages->isEmpty())
      <ul>
        @foreach ($pages as $page)
          <li><a href="{{ url('page/'.$page->alias) }}">{{ $page->{'title'.LANG} }}</a></li>
        @endforeach
      </ul>
      @endif
    </li>
    <li>
      <a href="#">{{ trans('main.Clubs') }}</a>
      @if (!$clubs->isEmpty())
      <ul>
        @foreach ($clubs as $club)
          <li><a href="{{ url('clubs/view/'.$club->id) }}">{{ $club->{'title'.LANG} }}</a></li>
        @endforeach
      </ul>
      @endif
    </li>
    <li>
      <a href="#">{{ trans('main.KGs') }}</a>
      @if (!$schools->isEmpty())
      <ul>
        @foreach ($schools as $school)
          <li><a href="{{ url('schools/view/'.$school->id) }}">{{ $school->{'title'.LANG} }}</a></li>
        @endforeach
      </ul>
      @endif
    </li>
    <li>
      <a href="#">{{ trans('main.Teachers') }}</a>
      @if (!$teachers->isEmpty())
      <ul>
        @foreach ($teachers as $teacher)
          <li><a href="{{ url('teachers/view/'.$teacher->id) }}">{{ $teacher->{'title'.LANG} }}</a></li>
        @endforeach
      </ul>
      @endif
    </li>
    
  </ul>
    
@stop