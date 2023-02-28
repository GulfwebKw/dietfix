@extends('admin.layouts.main')



@section('custom_head_include')



    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js') }}
    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js') }}
    {{ HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css') }}
    {{ HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css') }}
    {{ HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') }}

    {{ HTML::style('cpassets/css/pqgrid.min.css') }}
    {{ HTML::script('cpassets/js/pqgrid.min.js') }}
    {{ HTML::style('cpassets/css/jquery.dataTables.min.css') }}
    {{ HTML::script('cpassets/js/datatables.min.js') }}
    @if (!empty($customJS))
        {{ HTML::script($customJS) }}
    @endif


    @if ($_adminLang == 'arabic')

        {{ HTML::style('cpassets/plugins/data-tables/DT_bootstrap_rtl.css') }}

    @else

        {{ HTML::style('cpassets/plugins/data-tables/DT_bootstrap.css') }}

    @endif

@endsection







@section('content')

    <div class="table-toolbar">


    </div>

    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">
            <tr>
                <th class="exclude-search">ID</th>
                <th>{{trans('main.User Name')}}</th>
                <th>{{trans('main.Email Address')}}</th>
                <th>{{trans('main.Phone No.')}}</th>
                <th>{{trans('main.Mobile')}}</th>
                <th>Count Freez Day</th>
                <th>{{trans('main.Package')}}</th>
                <th>Start Suspension</th>
                <th>{{trans('main.EndsBy')}}</th>
                <th>{{trans('main.Active')}}</th>
            </tr>

            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->phone}}</td>
                    <td>{{$user->mobile_number}}</td>
                    <td>{{$user->dates->count()}}</td>
                    <td>{{optional($user->package)->titleEn}}</td>
                    <td>{{$user->dates->first()->date}}</td>
                    <td>{{$user->dates->last()->date}}</td>

                    <td>
                        <a href="/admin/users/renew-or-addmembership/{{$user->id}}" data-id="4280" class="nwrap btn btn-xs green btn-block "><i class="fa fa-print"></i> Renew/Add Membership</a>
                        <a href="/admin/users/orders/{{$user->id}}" data-id="{{$user->id}}" class="nwrap btn btn-xs red btn-block "><i class="fa fa-print"></i> Orders</a>
                        <a href="/admin/users/edit/{{$user->id}}" data-id="{{$user->id}}" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-edit"></i> Edit</a>
                        <a href="/admin/users/delete/{{$user->id}}" data-id="{{$user->id}}" class="nwrap btn btn-xs yellow btn-block grid-del-but"><i class="fa fa-remove"></i> Delete</a>
                        <a href="/admin/orders/view/{{$user->id}}" class="btn btn-xs  btn-success"><i class="fa fa-plus"></i> Menu </a>
                        <a href="/admin/users/progress/{{$user->id}}" data-id="{{$user->id}}" class="nwrap btn btn-xs green btn-block "><i class="fa fa-print"></i> progress</a>
                        <a href="/admin/users/transactions/{{$user->id}}" data-id="{{$user->id}}" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-list"></i> Transactions</a>
                        <a href="/admin/users/points/{{$user->id}}" data-id="{{$user->id}}" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-list"></i> Points</a>
                        <a href="/admin/users/freeze/{{$user->id}}" data-id="{{$user->id}}" class="nwrap btn btn-xs red btn-block "><i class="fa fa-lock"></i> Freeze</a>
                        <a href="/admin/users/unfreeze/{{$user->id}}" data-id="{{$user->id}}" class="nwrap btn btn-xs green btn-block "><i class="fa fa-unlock"></i> main.unFreeze</a>
                    </td>

                </tr>

            @endforeach

            </tbody>
        </table>
        {{--        {!! $points->links() !!}--}}
    </div>

@endsection

@section('custom_foot')

    {{ HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js') }}

    {{ HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js') }}



@endsection
