
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        body { font-family: DejaVu Sans, sans-serif;font-size:8px;}
        @page {
            margin-bottom:1px;
            margin-left: 5px;
            margin-right: 2px;
            margin-top:1px;
        }

    </style>

</head>


<body>

@if ($orders->isEmpty())
    <div>
        {{ trans('main.No Results') }}
    </div>
@else
    @foreach ($orders->users as $user)

        <?php $userdoctor = DB::table('users')->where('id', $user['user']->doctor_id)->first();?>
        <div class="page" >
            <div class="table-responsive">
                <table class="table table-hover"   style="height:310px;margin-bottom:10px">
                    <tbody>
                        <tr style="font-size: 11px; font-family:Tahoma,Arial, Helvetica, sans-serif;">
                            <th colspan="1" style="font-size: 15px"><h4>{{ $user['user']->id }}</h4></th>
                            <th colspan="1"><h4>{{ optional($user['user'])->username }}</h4></th>
                            <th colspan="1"><h4>{{ $user['user']->phone }}</h4></th>
                            <th colspan="2" ><h4>{{Input::get('date')}}</h4></th>
                            <th colspan="1" ><h4>{{optional($userdoctor)->username}}</h4></th>
                        </tr>

                    <tr style="font-size: 11px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
                        <th colspan="6" style="font-size: 11px">
                            <p>
                                @if($weekEndAddress)
                                    {{ optional($user['user']->countryWeekends)->{'title'.LANG} }} ,  {{ optional($user['user']->provinceWeekends)->{'title'.LANG} }} , {{ optional($user['user']->areaWeekends)->{'title'.LANG} }} , Block:{{ $user['user']->block_work }} , Street:{{ $user['user']->street_work }} , Avenue: {{ $user['user']->avenue_work }} , HouseNumber:{{ $user['user']->house_number_work }} ,  Floor:{{ $user['user']->floor_work }}<br>
                                    Address:{{ substr($user['user']->address_work,0,130) }}
                                @else
                                    {{ $user['user']->country->{'title'.LANG} }} ,  {{ optional($user['user']->area->province)->{'title'.LANG} }} , {{ $user['user']->area->{'title'.LANG} }} , Block:{{ $user['user']->block }} , Street:{{ $user['user']->street }} , Avenue:{{ $user['user']->avenue }} , HouseNumber:{{ $user['user']->house_number }} ,  Floor:{{ $user['user']->floor }}<br>
                                    Address:{{ substr($user['user']->address,0,130) }}
                                @endif

                            </p>
                        </th>
                    </tr>

                    <tr style="font-size: 10px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
                        <th colspan="3">{{ trans('main.Meal') }}</th>
                        <th colspan="1">{{ trans('main.Portion') }}</th>
                        <th  colspan="1">{{ trans('main.Notes') }}</th>
                        <th  colspan="2">{{ trans('main.Salt') }}</th>
                    </tr>
                    @foreach ($user['orders'] as $order)
                        <tr style="font-size:  10px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
                            <td colspan="3">{{ $order->category->{'title'.LANG} }} {{ $order->item->{'title'.LANG} }}</td>
                            <td colspan="1">{{ ($order->portion) ? $order->portion->{'title'.LANG} : 1 }}</td>
                            <td style="font-size:8px;" colspan="1">
                                @if (!$order->addons->isEmpty())
                                    @foreach ($order->addons as $addon)
                                        {{ $addon->{'title'.LANG} }}
                                    @endforeach
                                @endif
                            </td>
                            <td style="font-size:8px;" colspan="2">{{ $order->user->salt }}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
        <div class="page-break"></div>
    @endforeach
@endif

</body>
</html>






