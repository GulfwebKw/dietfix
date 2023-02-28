@extends('layouts.main')
@section('contents')
<div class="row">
	<div class="col-md-3">
		<img class="replace-2x" src="{{ url('resize?w=300&amp;h=300&amp;r=1&amp;c=1&amp;src=media/shops/'.$shop->photo) }}" alt="" title="" width="300" height="300">
		<br>
		<div class="pricing-success">
		<div class="rating-box">
		  <div style="width: {{ $rating }}%" class="rating">
			<svg x="0" y="0" width="73px" height="12px" viewBox="0 0 73 12" enable-background="new 0 0 73 12" xml:space="preserve">
			  <polygon fill-rule="evenodd" clip-rule="evenodd" fill="#1e1e1e" points="6.5,0 8,5 13,5 9,7.7 10,12 6.5,9.2 3,12 4,7.7 0,5 5,5"></polygon>
			  <polygon fill-rule="evenodd" clip-rule="evenodd" fill="#1e1e1e" points="66.5,0 68,5 73,5 69,7.7 70,12 66.5,9.2 63,12 64,7.7 60,5 65,5 "></polygon>
			  <polygon fill-rule="evenodd" clip-rule="evenodd" fill="#1e1e1e" points="21.5,0 23,5 28,5 24,7.7 25,12 21.5,9.2 18,12 19,7.7 15,5 20,5 "></polygon>
			  <polygon fill-rule="evenodd" clip-rule="evenodd" fill="#1e1e1e" points="51.5,0 53,5 58,5 54,7.7 55,12 51.5,9.2 48,12 49,7.7 45,5 50,5 "></polygon>
			  <polygon fill-rule="evenodd" clip-rule="evenodd" fill="#1e1e1e" points="36.5,0 38,5 43,5 39,7.7 40,12 36.5,9.2 33,12 34,7.7 30,5 35,5 "></polygon>
			</svg>
		  </div>
		</div>
		</div>
		<br>
	  	<a class="btn btn-lg btn-success btn-block" href="tel:{{ $shop->phone }}">{{ $shop->phone }}</a>
	</div>
	<div class="col-md-9">
		<p class="alert alert-info fade in ">
			<i class="fa fa-star alert-icon"></i>
			<button type="button" class="close" data-dismiss="alert">×</button>
			{{ $shop->{'notes'.LANG} }}
		</p>
		<p>
			{{ $shop->{'details'.LANG} }}
		</p>
	</div>
</div>
@if (!$data->isEmpty())
<div class="container">
  <div class="products grid row">
  	<div class="title-box no-margin">
      <h2 class="title">{{ trans('main.Products') }}</h2>
    </div>
    
    <div class="clearfix"></div>
    <div class="row">
      <div class="carousel products">
        @foreach ($data as $product)
        @include('shops.product_short')
        @endforeach
      </div>
    </div>
  </div><!-- .carousel-box -->
  {{ $data->links() }}
</div>
@endif


@stop
