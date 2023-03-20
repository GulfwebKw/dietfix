
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        *{ font-family: DejaVu Sans, sans-serif;font-size:10px;}
        @page {
            margin-bottom:1px;
            margin-left: 5px;
            margin-right: 2px;
            margin-top:1px;
        }

    </style>

</head>


<body>

    <div style="height:115px!important;width:219px;margin-bottom:0px!important;margin-top:8px;overflow: hidden;">
        User Name: {{ $user->username }}
        <br>
        ID: {{ $user->id }}
        <br>
        Phone number: {{ $user->mobile_number }}
        <br>
        @if( $user->country->{'title'.LANG} )
            {{ $user->country->{'title'.LANG} }} ,
        @endif
        @if( $user->province->{'title'.LANG} )
            {{ $user->province->{'title'.LANG} }} ,
        @endif
        @if( $user->area->{'title'.LANG} )
            {{ $user->area->{'title'.LANG} }} ,
        @endif
        @if( $user->block )
            Block: {{ $user->block }} ,
        @endif
        @if( $user->street )
            Street: {{ $user->street }} ,
        @endif
        @if( $user->avenue )
            Avenue: {{ $user->avenue }} ,
        @endif
        @if( $user->house_number )
            HouseNumber: {{ $user->house_number }} ,
        @endif
        @if( $user->house_number )
            Floor: {{ $user->floor }}
        @endif
        @if( $user->house_number )
            <br>Address: {{ $user->address }}
        @endif
    </div>

</body>
</html>






