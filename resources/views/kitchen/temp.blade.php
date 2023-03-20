
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        body { font-family: DejaVu Sans, sans-serif;font-size:10px;}
        @page {
            margin-bottom:1px;
            margin-left: 5px;
            margin-right: 2px;
            margin-top:1px;
        }

    </style>

</head>


<body>

@foreach($data['orders'] as $order)
    <div style="height:115px!important;width:219px;margin-bottom:0px!important;margin-top:8px;overflow: hidden;">

        {{substr($order->category->{'title'.LANG},0,32) . ' ' . substr($order->item->{'title'.LANG},0,32) }}
        <br>
        @if($order->portion)
            {{$order->portion->{'title'.LANG} }} <br>
        @endif

        {{' '.\Input::get('date')}}@if(!empty($order->user->packageone->{'title'.LANG})),{{($order->user->packageone->{'title'.LANG})}}@endif
        <br>
        Production : {{ date('Y-m-d' , strtotime(Input::get('date') . ' -'.$productionDay.' days ') ) }}
        <br>
        Expiry : {{ date('Y-m-d' , strtotime(Input::get('date') . ' +'.$expireDay.' days ') )  }}

        <br>
        @if( $showIdOnPrint )
        <p>
            {!!  optional($order->user)->salt
               ."<br>".' ID:'.$order->user->id
            !!}
       </p>
        @endif
        @if(!$order->addons->isEmpty())
            @php $addOnItem[$order->id]=[]; @endphp
            @foreach ($order->addons as $addon)

                @if(!in_array($addon->{'title'.LANG},$addOnItem[$order->id]))
                    <p style="font-size:8px">{{$addon->{'title'.LANG}.','}}</p>
                @endif
                @php $addOnItem[$order->id][]=$addon->{'title'.LANG}; @endphp
            @endforeach
        @endif
    </div>
@endforeach

</body>
</html>






