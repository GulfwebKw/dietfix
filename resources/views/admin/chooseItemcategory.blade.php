@extends('admin.layouts.main')

@section('custom_foot')
	{{ HTML::script('cpassets/js/jquery.chained.min.js') }}
	<script>
		jQuery(document).ready(function($) {
      
			
		});
	</script>
@stop

@section('content')
@if(!empty(Session::get('success')))
<div class="alert alert-success alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  {{ Session::get('success') }}
	</div>
@endif

<div class="row"><div class="col-sm-12"><h5>{{$items->titleEn}}</h5></div></div>
 @php
 use Illuminate\Support\Facades\Cookie;
 $bgcolor='';
 @endphp   
@if(!empty($categories) && count($categories)>0)
<div class="row">
@foreach($categories as $category)
@php
if(!empty(Cookie::get('chosen_'.$items->id.'_'.$category->id))){
$bgcolor='background-color:#006600;color:#FFFFFF;';
}else{
$bgcolor='';
}
@endphp
<div class="col-sm-3" style="border:1px #CCCCCC solid; margin-top:5px;padding:5px;{{$bgcolor}}"><b>{{$category->titleEn}}</b><a href="{{url('admin/items/choose/category/'.$items->id.'/'.$category->id)}}" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></div>
@endforeach
</div>
@endif
@stop