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

        <div class="btn-group pull-left">



            <a id="grid_new" class="btn green" href="https://www.dietfix.com/admin/packages-duration/add">

                Add <i class="fa fa-plus"></i>

            </a>




            <button id="grid_edit" class="btn blue">

                Edit <i class="fa fa-edit"></i>

            </button>




            <button id="grid_delete" class="btn red">

                Delete <i class="fa fa-remove"></i>

            </button>



        </div>

    </div>


    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">

            <tr role="row">
                <th class="exclude-search table-checkbox sorting_asc" rowspan="1" colspan="1" aria-label="" style="width: 148px;">
                    <div class="checker"><span><input type="checkbox" class="group-checkable checkall" data-set="#grid .checkboxes"></span></div>
                </th>



                <th class="exclude-search sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending" style="width: 118px;">ID
                </th>

                <th class="exclude-search sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="Package: activate to sort column ascending">
                    Package
                </th>

                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Arabic Title

                    : activate to sort column ascending" style="width: 44px;">

                    Arabic Title

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        English Title

                    : activate to sort column ascending" style="width: 51px;">

                    English Title

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Count Day

                    : activate to sort column ascending" style="width: 41px;">

                    Count Day

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Price

                    : activate to sort column ascending" style="width: 35px;">

                    Price

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Price After Discount

                    : activate to sort column ascending" style="width: 60px;">

                    Price After Discount

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Active

                    : activate to sort column ascending" style="width: 43px;">

                    Active

                </th>
                <th width="1%" class="sorting" tabindex="0" aria-controls="grid" rowspan="1" colspan="1" aria-label="

                        Active For App

                    : activate to sort column ascending" style="width: 43px;">

                    Active For App

                </th>
                <th class="exclude-search nwrap sorting_disabled" rowspan="1" colspan="1" aria-label="

			" style="width: 202px;">

                </th>
            </tr>

            </thead>

            <?php $i=0; ?>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <input type="checkbox" class="checkboxes" value="{{$item->id}}">
                    </td>

                    <td>{{$item->id}}</td>
                    <td>{{optional($item->package)->titleEn}}<hr><b>PID ={{optional($item->package)->id}}</b></td>
                    <td>{{$item->titleAr}}</td>
                    <td>{{$item->titleEn}}</td>
                    <td>{{$item->count_day}}</td>
                    <td>{{$item->price}}</td>
                    <td>{{$item->price_after_discount}}</td>
                    <td>
                        @if($item->active==1)
                            {!! trans('main.Yes') !!}
                        @else
                            {!! trans('main.No') !!}
                        @endif
                    </td>
                    <td>
                        @if($item->show_mobile==1)
                            {!! trans('main.Yes') !!}
                        @else
                            {!! trans('main.No') !!}
                        @endif
                    </td>
                    <td>
                        <a href="/admin/packages-duration/edit/{{$item->id}}" data-id="{{$item->id}}" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-edit"></i> Edit</a>
                        <a href="/admin/packages-duration/delete/{{$item->id}}" data-id="{{$item->id}}" class="nwrap btn btn-xs red btn-block grid-del-but"><i class="fa fa-remove"></i> Delete</a>
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

                {{--                "ajax": "{{ url($menuUrl.'/ajax') }}",--}}

                        {{--                "columns": [--}}
                        {{--                    { "sortable": false, "data": "checkboxCol" } ,--}}

                        {{--                    { "sortable": true, "data": "{{ $_pk}}" },--}}

                        {{--                        @foreach ($gridFields as $field)--}}

                        {{--                            @if($field['name']=='Package')--}}
                        {{--                                { "sortable": true, "data": "{{ $field['name']}}" },--}}
                        {{--                              @else--}}
                        {{--                               { "sortable": true, "data": "{{ $field['name']}}" },--}}
                        {{--                            @endif--}}
                        {{--                        @endforeach--}}

                        {{--                    { "sortable": false, "data": "butsCol" }--}}
                        {{--//          { "sortable": false, "data": "mainGridButsCol" } //MH--}}
                        {{--                ],"lengthMenu": [--}}

                        {{--                    [10, 20, 50, 100, -1],--}}

                        {{--                    [10, 20, 50, 100, "All"] // change per page values here--}}

                        {{--                ],--}}

                "processing" : true,

                // set the initial value

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

                    //     	$('#grid tfoot td:not(.exclude-search)').each( function () {

                    // var currentTD = $(this);

                    //       var title = $('#grid tfoot td').eq( currentTD.index() ).text();

                    //       var select = $('<select class="form-control"><option value="">{{ trans('main.Search') }} '+title+'</option></select>')

                    //           .appendTo( currentTD.empty() )

                    //           .on( 'change', function () {

                    //               var val = $(this).val();

                    // 			console.log(val);

                    //               table.columns( currentTD.index() )

                    //                   .search( val ? '^'+$(this).val()+'$' : val, true, false )

                    //                   .draw();

                    //           } );



                    // 	table.columns( currentTD.index() ).data().unique().sort().each( function ( j ) {

                    // 		$.each(j,function  (k,d) {

                    // 	select.append( '<option value="'+d+'">'+d+'</option>' );

                    // 		});

                    // 	});

                    //   });

                }

            });





            $("#grid .search-field").on( 'keyup change', function  () {

                var colIdx = $(this).parent().parent().index();

                table.column( colIdx ).search( this.value ).draw();

            });


            @foreach ($buts as $b)

            @if ($b['name'] != 'Add')

            $('#grid_{{ strtolower($b['name']) }}').click(function  () {

                        @if ($b['name'] == 'Delete')

                var answer = confirm ( "{{trans('main.ConfirmDelete')}} "+$('.trSelected',grid).length +" {{trans('endConfirm')}}"  );

                if (answer){

                    var value = [];

                    $(".checkboxes:checked").each(function() {

                        value.push($(this).val())

                    });

                    var resp = $.ajax({

                        type: "POST",

                        url: "{{ $deleteurl }}",

                        data: "id="+value,

                        success: function(msg){

                            alert(  $('.checkboxes:checked').length + "  Deleted!" );

                            $('.checkboxes').prop('checked', false);

                            window.location.reload();

                        }

                    }).responseText;

                }

                        @else

                var value = $('.checkboxes:checked').val();

                window.location = '{{ url($menuUrl.'/'.strtolower($b['name'])) }}/'+value;

                @endif

            });

            @endif

            @endforeach

            $('.grid-del-but').click(function  () {

                var answer = confirm ("{{trans('main.ConfirmDelete')}} "+$('.trSelected',grid).length +" {{trans('endConfirm')}}" );

                if (answer)

                    return true;

                else

                    return false;

            });

            $('.checkall').click(function(event) {

                if(this.checked) {

                    // Iterate each checkbox

                    $('.table tbody input:checkbox').each(function() {

                        this.checked = true;

                    });

                }

                else {

                    $('.table tbody input:checkbox').each(function() {

                        this.checked = false;

                    });

                }

            });

        });
    </script>


@endsection
