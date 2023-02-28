@extends('layouts.main')
@section('contents')
<div class="container">
  <div class="row">
  {{ Form::open(['url' => url('cart/confirm')]) }}
  <article class="content col-sm-12 col-md-12">
    <div class="table-box">
      <table id="shopping-cart-table" class="shopping-cart-table table table-bordered table-striped">
      <thead>
        <tr>
        <th></th>
        <th class="td-name">{{ trans('main.Name') }}</th>
        <th class="td-price">{{ trans('main.Price') }}</th>
        <th class="td-qty">{{ trans('main.Qty') }}</th>
        <th class="td-total">{{ trans('main.Subtotal') }}</th>
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
        </tr>
        @endforeach
      </tbody>
      </table><!-- .shopping-cart-table -->
    </div>
    <table class="shopping-cart-table shopping-cart-table-button table">
      <tbody>
      <tr>
        <td class="action no-border">
        <a href="{{ url() }}"><i class="fa fa-angle-left"></i> {{ trans('main.Continue Shopping') }}</a>
        </td>
      </tr>
      </tbody>
    </table>
    
    <div id="car-bottom" class="row">
        
      <div class="col-sm-12 col-md-8">
        <div class="form-box">
          <table>
            <tr>
              <td>{{ Form::label('email',trans('main.Email')) }}</td>
              <td>{{ Form::email('email',(Input::old('email')) ? Input::old('email') : Auth::user()->email,['class' => 'form-control']) }}</td>
            </tr>
            <tr>
              <td>{{ Form::label('phone',trans('main.Phone')) }}</td>
              <td>{{ Form::text('phone',(Input::old('phone')) ? Input::old('phone') : Auth::user()->phone,['class' => 'form-control']) }}</td>
            </tr>
            <tr>
              <td>{{ Form::label('address',trans('main.Address')) }}</td>
              <td>{{ Form::textarea('address',Auth::user()->address,['class' => 'form-control']) }}</td>
            </tr>
            <tr>
              <td>{{ Form::label('details',trans('main.Order Details')) }}</td>
              <td>{{ Form::textarea('details',null,['class' => 'form-control']) }}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-sm-12 col-md-4">
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
        {{ Form::button(trans('main.Submit Order'),['type' => 'submit','class' => 'btn checkout btn-default btn-lg']) }}
        </div>
      </div>
      </div>
    </div>
    
    <div class="clesrfix"></div>
    
  </article><!-- .content -->
  {{ Form::close() }}
  <div class="clearfix"></div>
@stop
