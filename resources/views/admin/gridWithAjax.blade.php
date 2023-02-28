@extends('admin.layouts.main')


@if($message=session()->get('message'))
    @section('messages')

        <div class="alert @if(session()->get('success')) alert-success  @else alert-danger  @endif alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>

    @endsection
@endif

@section('custom_head_include')



    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js') }}
    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js') }}
    {{ HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css') }}
    {{ HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css') }}
    {{ HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') }}

    {{ HTML::style('cpassets/css/pqgrid.min.css') }}
    {{ HTML::script('cpassets/js/pqgrid.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css') }}
    {{ HTML::script('//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css') }}
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

            @foreach ($buts as $b)

                @if ($b['name'] == 'Add')

                    <a id="grid_new" class="btn green" href="{{ $newurl }}">

                        @lang('main.Add') <i class="fa fa-plus"></i>

                    </a>

                @else

                    <button id="grid_{{ strtolower($b['name']) }}" class="btn {{ $b['color'] }}">

                        @lang('main.'.$b['name']) <i class="fa fa-{{ $b['icon'] }}"></i>

                    </button>

                @endif

            @endforeach

        </div>

    </div>

    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered" style="width:100%"  id="grid">

            <thead class="flip-content">

            <?php /*

		<tr class="search-row">

			<th class="exclude-search table-checkbox">

			</th>

			<th class="exclude-search">

			</th>

			@foreach ($gridFields as $field)

			<th width="{{ $field['width'] or 10 }}%">

				{{ $field['title'] }}

			</th>

			@endforeach

			<th class="exclude-search nwrap">

			</th>

		</tr>

		*/  ?>
            <?php if($menuUrl=="admin/membership"){$sdate = Session::get('sdate');?>
            Filter by Date: <input id="startdate" value="<?php if(!empty($sdate)){ echo $sdate;}?>" class="form-control input-sm" style="width:150px;">
            <?php }?>
            <tr>

                <th class="exclude-search table-checkbox">

                    <input type="checkbox" class="group-checkable checkall" data-set="#grid .checkboxes"/>

                </th>

                <th class="exclude-search">

                    ID

                </th>

                @foreach ($gridFields as $field)

                    @if(array_key_exists('width',$field))

                        <th width="{{ $field['width'] or 10 }}%">

                            {{ $field['title'] }}

                        </th>
                    @else

                        <th width="10%">
                            {{ $field['title'] }}
                        </th>
                    @endif


                @endforeach

                <th class="exclude-search nwrap">

                </th>

            </tr>

            </thead>


            <tbody>

            </tbody>

        </table>

    </div>

@endsection

@section('custom_foot')

    {{ HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js') }}

    {{ HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js') }}

    <script>

        jQuery(document).ready(function($) {

         /* $('#grid').DataTable( {
          "ajax": "{{ url($menuUrl.'/ajax') }}",
		  "columns": [
                    { "sortable": false, "data": "checkboxCol" } ,
                    { "sortable": true, "data": "{{ $_pk}}" },
                        @foreach ($gridFields as $field)
                    { "sortable": true, "data": "{{ $field['name']}}" },
                        @endforeach
                    { "sortable": false, "data": "butsCol" }
                    ],
					"displayLength": {{ $noOfItems }}
          });*/
		  
		  
            var table = $('#grid').DataTable({

                "ajax":{
                    "url": "{{ url($menuUrl.'/ajax') }}",

                    "data": function(d, settings){
                        var api = new $.fn.dataTable.Api(settings);
                        var page=0;
                        var start=d.start;
                        if(start==0){
                            page  =1;
                        }else{
                            page=(start/20)+1
                        }

                        // console.log(api.page.len());


                        // Convert starting record into page number
                        d.page =page;
                    }

                },


                "columns": [
                    { "sortable": false, "data": "checkboxCol" } ,

                    { "sortable": true, "data": "{{ $_pk}}" },

                        @foreach ($gridFields as $field)

                    { "sortable": true, "data": "{{ $field['name']}}" },

                        @endforeach

                    { "sortable": false, "data": "butsCol" }
//          { "sortable": false, "data": "mainGridButsCol" } //MH
                ],"lengthMenu": [

                    [10, 20, 50, 100, -1],

                    [10, 20, 50, 100, "All"] // change per page values here

                ],

                "processing" : true,
                "serverSide": true,

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
                "paging": true,
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
        //
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
            $.ajax({
                type: "GET",
                url:  '{{ url($menuUrl.'s_date/get_dates/') }}/'+str,
                data: "",
                dataType: "json",
                contentType: false,
                cache: false,
                processData:false,
                success: function(msg){
                    location.reload();
                },
                error: function(msg){
                    alert("Error found !!!");
                }
            });
        });
    </script>

@endsection
