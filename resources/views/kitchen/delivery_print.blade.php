
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
        table {
            border-collapse: collapse;
        }

        tr {
            border-bottom: 1pt solid black;
        }

    </style>

</head>


<body>

@if (count($orders->users) == 0 )
    <div>
        {{ trans('main.No Results') }}
    </div>
@else
    <table class="table table-hover"   style="margin-top:20px; width: 100%;margin-left: 20px; margin-right: 20px;">
        <tbody>
        <tr style="font-size: 11px; font-family:Tahoma,Arial, Helvetica, sans-serif; border-bottom: 2pt solid black;">
            <th class="text-center">{{ trans('main.ID') }}</th>
            <th class="text-center">{{ trans('main.Username') }}</th>
            <th class="text-center">{{ trans('main.Phone') }}</th>
            <th class="text-center">{{ trans('main.Address') }}</th>
        </tr>
    @foreach ($orders->users as $province)
        @foreach ($province as $area)
            @foreach ($area as $user)
                    <tr style="font-size: 11px; font-family:Tahoma,Arial, Helvetica, sans-serif; border-bottom: 1pt solid black;">
                        <td class="text-center" style="padding-top:5px;padding-bottom:5px; border-bottom: 1pt solid black;">
                            {{ $user->id }}
                        </td>
                        <td class="text-center" style="padding-top:5px;padding-bottom:5px; border-bottom: 1pt solid black;">
                            {{ $user->username }}
                        </td>
                        <td class="text-center" style="padding-top:5px;padding-bottom:5px; border-bottom: 1pt solid black;">
                            {{ $user->mobile_number }}
                        </td>
                        <td class="text-center" style="padding-top:5px;padding-bottom:5px; border-bottom: 1pt solid black;">
                            @if($weekEndAddress)
                                @php
                                    echo implode(', ', array_filter([
                                        optional($user->countryWeekends)->{'title'.LANG} ,
                                        optional($user->provinceWeekends)->{'title'.LANG} ,
                                        optional($user->areaWeekends)->{'title'.LANG} ,
                                        $user->block_work ? 'Block: '.$user->block_work : null ,
                                        $user->street_work ? 'Street: '.$user->street_work : null ,
                                        $user->avenue_work ? 'Avenue: '.$user->avenue_work : null ,
                                        $user->house_number_work ? 'HouseNumber: '.$user->house_number_work : null ,
                                        $user->floor_work ? 'Floor: '.$user->floor_work : null ,
                                        $user->address_work ? 'Address: '.$user->address_work : null ,
]));
                                @endphp
                            @else
                                @php
                                    echo implode(', ', array_filter([
                                        optional($user->country)->{'title'.LANG} ,
                                        optional($user->province)->{'title'.LANG} ,
                                        optional($user->area)->{'title'.LANG} ,
                                        $user->block ? 'Block: '.$user->block : null ,
                                        $user->street ? 'Street: '.$user->street : null ,
                                        $user->avenue ? 'Avenue: '.$user->avenue : null ,
                                        $user->house_number ? 'HouseNumber: '.$user->house_number : null ,
                                        $user->floor ? 'Floor: '.$user->floor : null ,
                                        $user->address ? 'Address: '.$user->address : null ,
]));
                                @endphp
                            @endif

                        </td>
                    </tr>
            @endforeach
        @endforeach
    @endforeach
        </tbody>
    </table>
    {{--            <div class="page-break"></div>--}}
@endif

</body>
</html>






