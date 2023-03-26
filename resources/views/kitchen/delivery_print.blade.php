
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

@if (count($orders->users) == 0 )
    <div>
        {{ trans('main.No Results') }}
    </div>
@else
    @foreach ($orders->users as $province)
        @foreach ($province as $area)
            @foreach ($area as $user)
                <table class="table table-hover"   style="height:310px;margin-bottom:10px">
                    <tbody>
                    <tr style="font-size: 11px; font-family:Tahoma,Arial, Helvetica, sans-serif;">
                        <th class="text-center">{{ trans('main.ID') }}</th>
                        <th class="text-center">{{ trans('main.Username') }}</th>
                        <th class="text-center">{{ trans('main.Phone') }}</th>
                        <th class="text-center">{{ trans('main.Address') }}</th>
                    </tr>
                    <tr style="font-size: 11px; font-family:Tahoma,Arial, Helvetica, sans-serif;">
                        <td class="text-center">
                            {{ $user->id }}
                        </td>
                        <td class="text-center">
                            {{ $user->username }}
                        </td>
                        <td class="text-center">
                            {{ $user->mobile_number }}
                        </td>
                        <td class="text-center">
                            @if($weekEndAddress)
                                {{ optional($user->countryWeekends)->{'title'.LANG} }}<br>
                                {{ optional($user->provinceWeekends)->{'title'.LANG} }}<br>
                                {{ optional($user->areaWeekends)->{'title'.LANG} }}<br>,
                                Block:{{ $user->block_work }}<br>
                                Street:{{ $user->street_work }}<br>
                                Avenue:{{ $user->avenue_work }}<br>
                                HouseNumber:{{ $user->house_number_work }}<br>
                                Floor:{{ $user->floor_work }}<br>
                                Address:{{ $user->address_work }}
                            @else
                                {{ $user->country->{'title'.LANG} }}<br>
                                {{ $user->province->{'title'.LANG} }}<br>
                                {{ $user->area->{'title'.LANG} }}<br>
                                Block:{{ $user->block }}<br>
                                Street:{{ $user->street }}<br>
                                Avenue:{{ $user->avenue }}<br>
                                HouseNumber:{{ $user->house_number }}<br>
                                Floor:{{ $user->floor }}<br>
                                Address:{{ $user->address }}
                            @endif

                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="page-break"></div>
            @endforeach
        @endforeach
    @endforeach
@endif

</body>
</html>






