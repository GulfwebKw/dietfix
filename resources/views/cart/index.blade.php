@extends('layouts.main')
@section('contents')
<div class="container">
  <div class="row">
  <article class="content col-sm-12 col-md-12">
    {{ Form::open(['url' => url('cart/update')]) }}
    <div class="table-box">
    @if (Cart::content()->isEmpty())
      <p class="text-center">{{ trans('main.Your cart is empty') }}</p>
    @else
      
      <table id="shopping-cart-table" class="shopping-cart-table table table-bordered table-striped">
      <thead>
        <tr>
        <th></th>
        <th class="td-name">{{ trans('main.Name') }}</th>
        <th class="td-price">{{ trans('main.Price') }}</th>
        <th class="td-qty">{{ trans('main.Qty') }}</th>
        <th class="td-total">{{ trans('main.Subtotal') }}</th>
        <th class="td-remove"></th>
        </tr>
      </thead>
      <tbody>
        @foreach (Cart::content() as $item)
        <tr>
        <td class="td-images">
          <a href="{{ url('product/view/'.$item->id) }}" class="product-image">
          @if ($item->options->data->photos->isEmpty())
          <img class="replace-2x" src="{{ url('resize?w=70&amp;h=70&amp;r=1&amp;c=1&amp;src=media/default.png') }}" width="70" height="70" alt="">
          @else
          <img class="replace-2x" src="{{ url('resize?w=70&amp;h=70&amp;r=1&amp;c=1&amp;src=media/products/'.$item->options->data->photos->first()->photo) }}" width="70" height="70" alt="">
          @endif
          </a>
        </td>
        <td class="td-name">
          <h2 class="product-name">
          <a href="{{ url('product/view/'.$item->id) }}">{{ $item->options->data->{'title'.LANG} }}</a>
          </h2>
        </td>
        <td class="td-price">
          <div class="price">@price($item->price)</div>
        </td>
        <td class="td-qty">
          {{ Form::text('qty[' . $item->rowid . ']',$item->qty,['class' => 'form-control']) }}
        </td>
        <td class="td-total">
          <div class="price">@price($item->subtotal)</div>
        </td>
        <td class="td-remove">
          <a href="#" data-product="{{ $item->id }}" class="product-remove">
          <svg x="0" y="0" width="16px" height="16px" viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve">
            <g>
            <path fill="#7f7f7f" d="M6,13c0.553,0,1-0.447,1-1V7c0-0.553-0.447-1-1-1S5,6.447,5,7v5C5,12.553,5.447,13,6,13z"></path>
            <path fill="#7f7f7f" d="M10,13c0.553,0,1-0.447,1-1V7c0-0.553-0.447-1-1-1S9,6.447,9,7v5C9,12.553,9.447,13,10,13z"></path>
            <path fill="#7f7f7f" d="M14,3h-1V1c0-0.552-0.447-1-1-1H4C3.448,0,3,0.448,3,1v2H2C1.447,3,1,3.447,1,4s0.447,1,1,1
            c0,0.273,0,8.727,0,9c0,1.104,0.896,2,2,2h8c1.104,0,2-0.896,2-2c0-0.273,0-8.727,0-9c0.553,0,1-0.447,1-1S14.553,3,14,3z M5,2h6v1
            H5V2z M12,14H4V5h8V14z"></path>
            </g>
          </svg>
          </a><!-- .product-remove -->
        </td>
        </tr>
        @endforeach
      </tbody>
      </table><!-- .shopping-cart-table -->

      @endif
    </div>
    <table class="shopping-cart-table shopping-cart-table-button table">
      <tbody>
      <tr>
        <td class="action no-border">
        <a href="{{ url() }}"><i class="fa fa-angle-left"></i> {{ trans('main.Continue Shopping') }}</a>
        <button type="submit" class="btn btn-success pull-right flip"><i class="fa fa-rotate-right"></i> Update Shopping Cart</button>
        </td>
      </tr>
      </tbody>
    </table>
    {{ Form::close() }}
    <div id="car-bottom" class="row">
      
      
      <div class="col-sm-12 col-md-4 col-md-push-8">
      <div class="car-bottom-box bg total">
        <table>
        <tbody>
          <tr>
          <td>{{ trans('main.Subtotal') }}</td>
          <td><span class="price">@price(Cart::total())</span></td>
          </tr>
          <tr>
          <td>{{ trans('main.Shipping Cost') }}</td>
          <td><span class="price">@price($shipping_cost)</span></td>
          </tr>
          <tr class="tr-total">
          <td>{{ trans('main.Total') }}</td>
          <td><span class="price">@price(Cart::total() + $shipping_cost)</span></td>
          </tr>
        </tbody>
        </table>
        <div>
        <a href="{{ url('cart/checkout') }}" class="btn checkout btn-default btn-lg">{{ trans('main.Proceed to Checkout') }}</a>
        </div>
      </div>
      </div>
    </div>
    
    <div class="clesrfix"></div>
    
  </article><!-- .content -->

    <div class="clearfix"></div>


@stop
