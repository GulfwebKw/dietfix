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
    <div class="table-toolbar">

        <div class="btn-group pull-left">
            <a id="grid_new" class="btn green" href="/admin/invoices/add">
                Add <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>

    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">
            <tr>
                <th class="exclude-search">id</th>
                <th>User</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Mobile</th>
                <th>package</th>
                <th>package duration</th>
                <th>count</th>
                <th>sum </th>
                <th>date </th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($invoice))
              @foreach($invoice as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->user->username}}</td>
                    <td>{{$item->user->email}}</td>
                    <td>{{$item->user->phone}}</td>
                    <td>{{$item->user->mobile_number}}</td>
                    <td>{{optional($item->package)->titleEn}}</td>
                    <td>{{optional($item->packageDuration)->titleEn}}</td>
                    <td>{{$item->count}}</td>
                    <td>{{$item->sum}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        <a href="/admin/invoices/print/{{$item->id}}" data-id="{{$item->id}}" class="nwrap btn btn-xs red btn-block "><i class="fa fa-print"></i> Print</a>
{{--                        <a href="/admin/invoice/edit/{{$item->id}}" data-id="{{$item->id}}" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-edit"></i> Edit</a>--}}
{{--                        <a href="/admin/invoice/delete/{{$item->id}}" data-id="{{$item->id}}" class="nwrap btn btn-xs yellow btn-block grid-del-but"><i class="fa fa-remove"></i> Delete</a>--}}
                    </td>
                </tr>
            @endforeach
            @endif

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
    </script>


@endsection
