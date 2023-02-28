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
                <th>{{trans('main.Mobile')}}</th>
                <th>{{trans('main.Package')}}</th>
                <th>Action</th>
                <th>Date</th>
                <th> Meals Package </th>
                <th>Save Meals</th>
            </tr>

            </thead>
            <tbody>
            @foreach($result as $date)
                <tr @if(optional($date->user)->package->meals->count()!=optional($date->orders)->count()) style="color: red" @endif>
                    <td>{{optional($date->user)->id}}</td>
                    <td>{{optional($date->user)->username}}</td>
                    <td>{{optional($date->user)->mobile_number}}</td>
                    <td>{{optional($date->user)->package->titleEn}}</td>
                    <td>{{$date->update_status}}</td>
                    <td>{{$date->date}}</td>
                    <td>{{optional($date->user)->package->meals->count()}}</td>
                    <td>{{optional($date->orders)->count()}}</td>
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
    </script>


@endsection
