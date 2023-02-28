@extends('layouts.main')
@section('otherheads')
    <link href='/packages/core/main.css' rel='stylesheet' />
    <link href='/packages/daygrid/main.css' rel='stylesheet' />
    <link href='/packages/timegrid/main.css' rel='stylesheet' />
    <style>
        .fc-highlight {
            background: green !important;
        }
        .fc-day-grid-event .fc-content {
            white-space: normal;
        }

        .loader {
            border: 4px solid #f3f3f3; /* Light grey */
            border-top: 4px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection
@section('contents')

        <div class="row">

            <div class="col-md-4">

            </div>
            <div class="col-md-2" align="center">
                <button style="background:#28a745;border-color:#28a745;color: white; height:30px;width: 30px" ></button><p style="margin-bottom: 10px"> Selected </p>
            </div>
            <div class="col-md-2" align="center">
                <button style="background:#17a2b8;border-color:#17a2b8;color: white; height:30px;width: 30px"></button><p style="margin-bottom: 10px">Not Selected</p>
            </div>

            <div class="col-md-4">

            </div>

        </div>
     

    @foreach($userDate as $collect)
        <div class="row">
            @foreach($collect as $day)
                <div class="col-sm-3 "  align="center" >
                    <h3>
                        <button   @if($day->isMealSelected==1) style="background:#28a745;color: white" @else style="background-color:#17a2b8; color: white" @endif   data-day="{{$day->id}}" data-date="{{$day->date}}"  type="button" class="btn   showdate">{{ date('l', strtotime( $day->date))}} - {{ $day->date }} </button>
                    </h3>
                </div>
            @endforeach
        </div>
    @endforeach

<div class="modal fade" style=" overflow: auto !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div id="content-model" class="modal-body">

            </div>



        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



@stop
@section('custom_foot')
    {{ HTML::script('assets/js/jquery.validate.min.js') }}
    <script>

        $(function () {
            $(".showdate").click(function  (e) {
                e.preventDefault();
                var d = $(this).attr('data-day');

                $('#content-model').html("<div align='center'><div class=\"loader\"></div></div>");
                $('.modal').modal();

                $.get('/menu/order/listHtml/'+d, null, function(json, textStatus) {
                    $('#content-model').html(json);
                    $('.modal').modal();
                    $('.item-radio').click(function(){
                        $(this).parent().parent().find('.item-checks').prop('checked', false);
                    });
                });
            });


        });

    </script>

@stop



