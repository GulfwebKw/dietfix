<div class="col-sm-3 col-md-3 product rotation">
  <div class="default">
    <a href="{{ url('product/view/'.$product->id) }}" class="product-image">
      <img class="replace-2x" src="{{ url('resize?w=270&amp;h=270&amp;r=1&amp;c=1&amp;src=media/products/'.$product->photos->first()->photo) }}" alt="" title="" width="270" height="270">
    </a>
    <div class="product-description">
      <div class="vertical">
    <h3 class="product-name">
      <a href="{{ url('product/view/'.$product->id) }}">{{ $product->{'title'.LANG} }}</a>
    </h3>
    <div class="price">@price($product->price)</div> 
      </div>
    </div>
  </div>
  <div class="product-hover">
    <h3 class="product-name">
      <a href="{{ url('product/view/'.$product->id) }}">{{ $product->{'title'.LANG} }}</a>
    </h3>
    <div class="price">@price($product->price)</div>
    <a href="{{ url('product/view/'.$product->id) }}" class="product-image">
      <img class="replace-2x" src="{{ url('resize?w=70&amp;h=70&amp;r=1&amp;c=1&amp;src=media/products/'.$product->photos->first()->photo) }}" alt="" title="" width="70" height="70">
    </a>
    <ul>
      <li>{{ trans('main.Shop') }}: {{ $product->shop->{'title'.LANG} }}</li>
      <li>{{ trans('main.Views') }}: {{ $product->views }}</li>
      <li>{{ trans('main.Price') }}: @price($product->price)</li>
      <li>{{ trans('main.Available') }}: @if ($product->available)
        <i class="fa fa-check"></i>
      @else
        <i class="fa fa-times"></i>
      @endif</li>
    </ul>
    <div class="actions">
      <a href="#" data-product="{{ $product->id }}" class="@if ($product->available)add-cart @else add-cart2 @endif">
        <svg x="0" y="0" width="16px" height="16px" viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve">
          <g>
            <path fill="#1e1e1e" d="M15.001,4h-0.57l-3.707-3.707c-0.391-0.391-1.023-0.391-1.414,0c-0.391,0.391-0.391,1.023,0,1.414L11.603,4
              H4.43l2.293-2.293c0.391-0.391,0.391-1.023,0-1.414s-1.023-0.391-1.414,0L1.602,4H1C0.448,4,0,4.448,0,5s0.448,1,1,1
              c0,2.69,0,7.23,0,8c0,1.104,0.896,2,2,2h10c1.104,0,2-0.896,2-2c0-0.77,0-5.31,0-8c0.553,0,1-0.448,1-1S15.554,4,15.001,4z
              M13.001,14H3V6h10V14z"></path>
            <path fill="#1e1e1e" d="M11.001,13c0.553,0,1-0.447,1-1V8c0-0.553-0.447-1-1-1s-1,0.447-1,1v4C10.001,12.553,10.448,13,11.001,13z"></path>
            <path fill="#1e1e1e" d="M8,13c0.553,0,1-0.447,1-1V8c0-0.553-0.448-1-1-1S7,7.447,7,8v4C7,12.553,7.448,13,8,13z"></path>
            <path fill="#1e1e1e" d="M5,13c0.553,0,1-0.447,1-1V8c0-0.553-0.447-1-1-1S4,7.447,4,8v4C4,12.553,4.448,13,5,13z"></path>
          </g>
        </svg>
      </a>
    </div><!-- .actions -->
  </div><!-- .product-hover -->
</div><!-- .product -->