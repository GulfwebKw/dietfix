@extends('layouts.main')
@section('contents')
@if (!$data->isEmpty())
<div class="container">
  <div class="products grid row">
    
    <div class="clearfix"></div>
    <div class="row">
      <div class="carousel products">
        @foreach ($data as $product)
        @include('shops.product_short')
        @endforeach
      </div>
    </div>
  </div><!-- .carousel-box -->
  {{ $data->appends(['keyword' => $keyword])->links() }}
</div>
@endif


@stop
