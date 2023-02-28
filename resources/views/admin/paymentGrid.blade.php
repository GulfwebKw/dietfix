@extends('admin.layouts.main')



@section('custom_head_include')



    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js') }}
    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js') }}
    {{ HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css') }}
    {{ HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css') }}
    {{ HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') }}

    {{ HTML::style('cpassets/css/pqgrid.min.css') }}
    {{ HTML::script('cpassets/js/pqgrid.min.js') }}
    <!--{{ HTML::style('cpassets/css/jquery.dataTables.min.css') }}
    {{ HTML::script('cpassets/js/datatables.min.js') }}-->
    <!--{{ HTML::style('cpassets/css/jquery.dataTables.min.css') }}-->
    <!--{{ HTML::script('cpassets/js/datatables.min.js') }}-->
   <!-- {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css') }}-->
    {{ HTML::style('//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css') }}

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

                @if ($b['name'] == 'Paid')

                    <a  class="btn green" href="/admin/payments/paid">

                        Paid <i class="fa fa-list"></i>

                    </a>
                
                @endif
                @if ($b['name'] == 'UnPaid')

                    <a  class="btn red" href="/admin/payments/unpaid">

                        UnPaid <i class="fa fa-list"></i>

                    </a>
                
                @endif

            @endforeach

        </div>

    </div>

    <div class="clearfix"></div>

    <br>
    <style>.nwrap{width:100px; text-align:left;}</style>
    <div class="flip-scroll">

        <table class="table table-striped table-bordered" style="width:100%"  id="grid">

            <thead class="flip-content">

           
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

                @foreach($gridFields as $field)

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

    {{ HTML::script('https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js') }}

    {{ HTML::script('https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js') }}

    <script>

        jQuery(document).ready(function($) {

            var table = $('#grid').DataTable({
                "ajax":{
                    "url": "{{url($menuUrl.'/ajax?type='.Request()->type)}}",

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

                    "bInfo":false,
                    {{--"info": "@lang('main.Showing _START_ to _END_ of _TOTAL_ entries')",--}}

                    "processing": '<img src="http://dietfix.com/images/progress.gif" width="20">&nbsp;@lang('main.Please wait')...',

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
                },

            });





            $("#grid .search-field").on( 'keyup change', function  () {

                var colIdx = $(this).parent().parent().index();

                table.column( colIdx ).search( this.value ).draw();

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

        function myFunction(txtValue) {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = txtValue;
            //filter = input.value.toUpperCase();
            table = document.getElementById("grid");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

@endsection
