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
                <th>Title</th>
                <th>Message</th>
                <th>User</th>
                <th>Admin</th>
                <th>Options</th>
                <th>Date</th>

            </tr>

            </thead>
            <tbody>
            @if(isset($logs))
                @foreach($logs as $log)
                    <tr>
                        <td>{{$log->id}}</td>
                        <td>{{isset($log->title)?$log->title:'----'}}</td>
                        <td>{{isset($log->message)?$log->message:'----'}}</td>
                        <td>{{isset($log->user)?$log->user->username:'----'}}</td>
                        <td>{{isset($log->admin)?$log->admin->username:'----'}}</td>
                        <td>
                            @if(isset($log->options))
                                @foreach(json_decode($log->options) as $key=>$val)
                                    {{ $key.' ==> '.$val }}
                                @endforeach
                            @endif
                        </td>
                        <td>{{isset($log->created_at)?$log->created_at:'---'}}</td>

                    </tr>
                @endforeach
              @endif

            </tbody>



        </table>
        {!! $logs->links() !!}

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
