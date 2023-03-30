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
    {{ HTML::script('cpassets/js/mebership_dashboard.js') }}
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

        <div class="btn-group pull-left">
            <a id="grid_export" class="btn grey" href="{{ route('adminExportMembership') }}">
                Export <i class="fa fa-file-excel-o"></i>
            </a>
        </div>

    </div>

    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">

            Filter by Date: <input id="startdate" @if(isset($date)) value="{{$date}}"  @endif  class="form-control input-sm" style="width:150px;">

            <tr>
                <th class="exclude-search">ID</th>
                <th>{{trans('main.User Name')}}</th>
                <th>{{trans('main.Phone No.')}}</th>
                <th>EndsBy</th>
                <th>PackageAr</th>
                <th>PackageEn</th>
                <th>RoleAr</th>
                <th>RoleEn</th>
                <th>{{trans('main.Active')}}</th>
            </tr>

            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->phone}}</td>
                    <td>{{optional($user->lastDay)->date}}</td>
                    <td>{{optional($user->package)->titleAr}}</td>
                    <td>{{optional($user->package)->titleEn}}</td>
                    <td>{{optional($user->role)->roleNameAr}}</td>
                    <td>{{optional($user->role)->roleNameEn}}</td>
                    <td>
                        <button onclick="showPopup({{$user->id}})" data-id="{{$user->id}}" class="nwrap btn btn-xs green btn-block ">Follow ups</button>
                        <button onclick="sendEmail({{$user->id}})" data-id="{{$user->id}}" class="nwrap btn btn-xs gray btn-block ">Email</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('custom_foot')

    {{ HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js') }}

    {{ HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js') }}
    <script>
        jQuery(document).ready(function($) {
            var table = $('#grid').DataTable({
                "processing": true,
                "displayLength": {{ $noOfItems }},
                "paginationType": "bootstrap_full_numbers",
                "pagingType": "full_numbers",
                "language": {
                    "lengthMenu": "_MENU_ @lang('main.Records') ",
                    "search": '@lang('main.Search') : ',
                    "info": "@lang('main.Showing _START_ to _END_ of _TOTAL_ entries')",
                    "processing": '<img src="http://sys.dietfix.com/images/progress.gif" width="20">&nbsp;@lang('main.Please wait')...',
                    "paginate": {
                        "last": '<i class="fa fa-arrow-circle-left"></i>',
                        "first": '<i class="fa fa-arrow-circle-right"></i>',
                        "previous": '<i class="fa fa-angle-left"></i>',
                        "next": '<i class="fa fa-angle-right"></i>'
                    }

                },

                "columnDefs": [{
                    "defaultContent": "",
                    "targets": "_all",
                }
                ],

                "initComplete": function (oSettings, json) {

                }

            });
            $("#grid .search-field").on('keyup change', function () {

                var colIdx = $(this).parent().parent().index();

                table.column(colIdx).search(this.value).draw();

            });

        });
        $(function(){
            $("#startdate").datepicker({
                autoclose:!0,
                format:"yyyy-mm-dd",
                startDate:new Date
            }),
                $("#enddate").datepicker({
                    autoclose:!0,
                    format:"yyyy-mm-dd",
                    startDate:new Date
                })
        });
        $("#startdate").change(function(){
            var dval = $(this).val();
            var mydate = new Date(dval);
            var str = mydate.toString("yyyy-MM-dd");
            window.location.href='/{{$menuUrl}}/'+str;

        });
    </script>


@endsection
