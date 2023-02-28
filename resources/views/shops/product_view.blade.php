@extends('layouts.main')
@section('contents')
    <div class="row">
      <article class="content product-page col-sm-12 col-md-12">
		<div class="row">
		  <div class="col-sm-5 col-md-5">
			<div class="image-box">
			  <div class="general-img">
			  	@if ($product->photos->isEmpty())
				<img class="replace-2x" alt="" src="{{ url('resize?w=700&amp;h=700&amp;r=1&amp;c=1&amp;src=media/default.png'.$product->photos->first()->photo) }}" data-zoom-image="{{ url('resize?w=700&amp;h=700&amp;r=1&amp;c=1&amp;src=media/default.png') }}" width="700" height="700">
			  	@else
				<img class="replace-2x" alt="" src="{{ url('resize?w=700&amp;h=700&amp;r=1&amp;c=1&amp;src=media/products/'.$product->photos->first()->photo) }}" data-zoom-image="{{ url('resize?w=700&amp;h=700&amp;r=1&amp;c=1&amp;src=media/products/'.$product->photos->first()->photo) }}" width="700" height="700">
			  	@endif
			  </div><!-- .general-img -->
			  
			  <div class="thumblist-box load">
				<a href="#" class="prev">
				  <svg x="0" y="0" width="9px" height="16px" viewBox="0 0 9 16" enable-background="new 0 0 9 16" xml:space="preserve">
					<polygon fill-rule="evenodd" clip-rule="evenodd" fill="#fcfcfc" points="8,15.999 9,14.999 2,8 9,1.001 8,0.001 0,8 "></polygon>
				  </svg>
				</a>
				<a href="#" class="next">
				  <svg x="0" y="0" width="9px" height="16px" viewBox="0 0 9 16" enable-background="new 0 0 9 16" xml:space="preserve">
					<polygon fill-rule="evenodd" clip-rule="evenodd" fill="#fcfcfc" points="1,0.001 0,1.001 7,8 0,14.999 1,15.999 9,8 "></polygon>
				  </svg>
				</a>
				@if (!$product->photos->isEmpty())
				<div id="thumblist" class="thumblist">
					@foreach ($product->photos as $photo)
						<a href="#" data-image="{{ url('resize?w=700&amp;h=700&amp;r=1&amp;c=1&amp;src=media/products/'.$photo->photo) }}" data-zoom-image="{{ url('resize?w=700&amp;h=700&amp;r=1&amp;c=1&amp;src=media/products/'.$photo->photo) }}">
							<img class="replace-2x" alt="" src="{{ url('resize?w=200&amp;h=200&amp;r=1&amp;c=1&amp;src=media/products/'.$photo->photo) }}" width="700" height="700">
						</a>
					@endforeach
				</div><!-- #thumblist -->
				@endif
			  </div><!-- .thumblist -->
			</div>
		  </div>
		  
		  <div class="col-sm-7 col-md-7">
			
			
			<div class="description">
			  {{ $product->{'details'.LANG} }}
			</div>
			
			<div class="price-box">
			  {{-- <span class="price-old">$1500</span>  --}}
			  <span class="price">@price($product->price)</span>
			</div>
			
			{{ Form::open(['url' => url('cart/add'), 'class' => 'form-inline add-cart-form', 'method' => 'get'])}}
				{{ Form::button(trans('main.Add to Cart'),['type' => 'submit','class' => 'btn btn-default btn-lg']) }}
				  <div class="number">
					<label>{{ trans('Qty') }}:</label>
					{{ Form::text('qty',1,['class' => 'form-control','id' => 'qty_no']) }}
					<div class="regulator">
					  <a href="#" class="number-up"><i class="fa fa-angle-up"></i></a>
					  <a href="#" class="number-down"><i class="fa fa-angle-down"></i></a>
					</div>
				  </div>
				  {{ Form::hidden('product_id',$product->id,['id' => 'product_id_hidden']) }}
			{{ Form::close() }}
			
		  </div>
		</div>
		
		  
		<div class="clearfix"></div>
		@if (!$related->isEmpty())
		
		<div class="recommended-product carousel-box load overflow" data-autoplay-disable="true">
		  <div class="title-box no-margin">
			<a class="next" href="#">
			  <svg x="0" y="0" width="9px" height="16px" viewBox="0 0 9 16" enable-background="new 0 0 9 16" xml:space="preserve">
				<polygon fill-rule="evenodd" clip-rule="evenodd" fill="#fcfcfc" points="1,0.001 0,1.001 7,8 0,14.999 1,15.999 9,8 "></polygon>
			  </svg>
			</a>
			<a class="prev" href="#">
			  <svg x="0" y="0" width="9px" height="16px" viewBox="0 0 9 16" enable-background="new 0 0 9 16" xml:space="preserve">
				<polygon fill-rule="evenodd" clip-rule="evenodd" fill="#fcfcfc" points="8,15.999 9,14.999 2,8 9,1.001 8,0.001 0,8 "></polygon>
			  </svg>
			</a>
			<h2 class="title">{{ trans('main.Related Products') }}</h2>
		  </div>
		  
		  <div class="clearfix"></div>
		  
		  <div class="row">
			<div class="carousel products">
				@foreach ($related as $product)
					@include('shops.product_short')
				@endforeach
			</div>
		  </div>
		</div><!-- .recommended-product -->
		
		@endif
      </article><!-- .content -->
    </div>


@stop
