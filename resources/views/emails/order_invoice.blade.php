@extends('emails.layouts.main')

@section('contents')

<h2 style="color:#800;text-align:center">{{ trans('main.Order Invoice') .' #'. $order->id }}</h2>

@if(!$order->items->isEmpty())
<table class="table table-striped table-hover">
	<thead>
		<tr class="flip-content">
			<th width="50%">{{ trans('main.Title') }}</th>
			<th width="50%">{{ trans('main.Shop') }}</th>
			<th width="10%">{{ trans('main.Qty') }}</th>
			<th width="10%">{{ trans('main.Price') }}</th>
			<th width="10%">{{ trans('main.Total') }}</th>
		</tr>
	</thead>
	<tbody>
	@foreach($order->items as $order_item)
		<tr>
			<td>
				{{ $order_item->product->{'title'.LANG} }}
			</td>
			<td>
				{{ $order_item->product->shop->{'title'.LANG} }}
			</td>
			<td>
				{{ $order_item->qty }}
			</td>
			<td>
				{{ $order_item->product->price }}
			</td>
			<td>
				{{ $order_item->semi_price }}
			</td>
		</tr>
	@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4">{{ trans('main.Total') }}</td>
			<td>{{ $order->total}}</td>
		</tr>
	</tfoot>
</table>
@endif

<table class="table table-striped table-hover">
	<tbody>
		<tr>
			<th>
				{{ trans('main.Email') }}
			</th>
			<td>
				{{ $order->email }}
			</td>
		</tr>
		<tr>
			<th>
				{{ trans('main.Phone') }}
			</th>
			<td>
				{{ $order->phone }}
			</td>
		</tr>
		<tr>
			<th>
				{{ trans('main.Address') }}
			</th>
			<td>
				{{ $order->address }}
			</td>
		</tr>
		<tr>
			<th>
				{{ trans('main.Details') }}
			</th>
			<td>
				{{ $order->details }}
			</td>
		</tr>
		<tr>
			<th>
				{{ trans('main.Status') }}
			</th>
			<td>
				{{ $order->status }}
			</td>
		</tr>
		<tr>
			<th>
				{{ trans('main.Date') }}
			</th>
			<td>
				{{ $order->created_at }}
			</td>
		</tr>
		<tr>
			<th>
				{{ trans('main.Total Price') }}
			</th>
			<td>
				{{ $order->total }}
			</td>
		</tr>
	</tbody>
</table>

@stop