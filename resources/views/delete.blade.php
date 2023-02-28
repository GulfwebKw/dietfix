@extends('layouts.main')

@section('contents')
  {{ Form::open(array('url' => url('delete'), 'class' => 'form-g'))}}
  <div class="alert alert-warning" role="alert">
    هل انت متأكد من حذف اضافتك :
    {{$item->title}}
  </div>
  {{ Form::hidden('id', $item->id) }}
  {{ Form::hidden('edit_key', $item->edit_key) }}
  <div class="form-group">
    {{ Form::button(trans('main.Delete'), array('type' => 'submit', 'class' => 'btn btn-primary btn-block danger')) }}
  </div>
 
  {{ Form::close() }}

@stop
