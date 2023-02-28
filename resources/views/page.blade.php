@extends('layouts.main')

@section('contents')
<div class="block-content block-content-small-padding">
  <div class="block-content-inner">
      <div class="content">
      	{{ $page->{'contents'.ucfirst(LANG_SHORT)} }}

      </div>
      @if(Auth::user() && Auth::user()->isAdmin == 1)
      	<a class="btn btn-primary pull-right flip" target="_blank" href="{{ url(ADMIN_FOLDER.'/pages/edit/'.$page->id) }}">{{ trans('main.Edit This Page') }}</a>
      @endif
  </div><!-- /.block-content-inner -->
</div>
@stop