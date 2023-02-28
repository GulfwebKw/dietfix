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
    @if(Session::get('status')=='danger')
    <div class="alert alert-danger">{{Session::get('message')}}</div>
    @endif
    @if(Session::get('status')=='success')
    <div class="alert alert-success">{{Session::get('message')}}</div>
    @endif
    
    <div class="clearfix"></div>
    
    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">
            <tr>
                <th class="exclude-search">ID</th>
                <th>User Details</th>
                <th>Package Details</th>
                <th>Trans. Details</th>
                <th>Dates</th>
                <th width="100">{{trans('main.Active')}}</th>
            </tr>

            </thead>
            <tbody>
            @foreach($renewDatas as $renewData)
                <tr>
                    <td>{{$renewData->id}}</td>
                    <td>
                    <table width="100%" class="table">
                    @if(!empty($renewData->users->username))
                    <tr><td width="50">Name:</td><td>{{$renewData->users->username}}</td></tr>
                    @endif
                    @if(!empty($renewData->users->phone))
                    <tr><td>Mobile:</td><td>{{$renewData->users->phone}}</td></tr>
                    @endif
                    @if(!empty($renewData->users->email))
                    <tr><td>Email:</td><td>{{$renewData->users->email}}</td></tr>
                    @endif
                    </table>
                    </td>
                    <td>
                    <table width="100%" class="table">
                    @if(!empty(optional($renewData->package)->titleEn))
                    <tr><td width="50">Package:</td><td>{{optional($renewData->package)->titleEn}}</td></tr>
                    @endif
                    @if(!empty(optional($renewData->packageduration)->titleEn))
                    <tr><td width="50">Duration:</td><td>{{optional($renewData->packageduration)->titleEn}}</td></tr>
                    @endif
                    @if(!empty($renewData->payment->total))
                    <tr><td width="100">Amount:</td><td>{{$renewData->payment->total}}</td></tr>
                    @endif
                    </table>
                    </td>
                    <td>
                    <table width="100%" class="table">
                    @if(!empty($renewData->payment->paymentID))
                    <tr><td width="100">Pay ID:</td><td>{{$renewData->payment->paymentID}}</td></tr>
                    @endif
                    @if(!empty($renewData->payment->pay_way_type))
                    <tr><td width="100">Pay Mode:</td><td>{{$renewData->payment->pay_way_type}}</td></tr>
                    @endif
                   
                    <tr><td width="100">Status:</td><td>
                    @if(!empty($renewData->payment->paid)) <font color="#006600">PAID</font> @else <font color="#ff0000">NOT PAID</font> @endif
                    </td></tr>
                    
                    </table>
                    </td>
                    <td>
                    <table width="100%" class="table">
                    @if(!empty($renewData->starting_date))
                    <tr><td width="100">Start Date:</td><td>{{$renewData->starting_date}}</td></tr>
                    @endif
                    <tr><td width="100">Created On:</td><td>{{$renewData->created_at}}</td></tr>
                    </table>
                    </td>
                    <td>
                    @php
                    $strtDate = date("Y-m-d",strtotime($renewData->starting_date));
                    $crDate   = date("Y-m-d");
                    @endphp
                    
                    @if(strtotime($crDate)>strtotime($strtDate))
                    <a href="/admin/future_pkg_subs_approve/{{$renewData->id}}"  class="btn btn-xs green btn-block ">Activate</a>
                    @else
                    <small><font color="#ff0000">Activation link will appear once starting date will cross the current date</font></small>
                    @endif    
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
