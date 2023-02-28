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
            font-size: 14px;
        }
    </style>
@endsection
@section('contents')
    <div class="col-md-12" align="center">
        <h4>You can change position of item day. if you want to change drag item day and Move it to  another day</h4>
    </div>
    <br>
    <div class="col-md-12 " align="center">
      <div class="col-md-3">

        </div>

        <div class="col-md-3" ><button  style="background:#28a745;border-color:#28a745;color: white; height:30px;width: 30px" ></button><p style="margin-bottom: 10px">Meal is Selected </p></div>


        <div class="col-md-3"> <button style="background:#17a2b8;border-color:#17a2b8;color: white; height:30px;width: 30px"></button><p style="margin-bottom: 10px">Meal is Not Selected</p></div>

        <div class="col-md-3">

        </div>

    </div>

    <div class="col-md-12">
        <div id='calendar'>



        </div>
    </div>
    <br/>
    <div class="col-md-12" align="center">
        <button class="btn btn-success"  id="save_days">Save and Continue</button>
    </div>
    <br/>


@stop
@section('custom_foot')
    {{ HTML::script('assets/js/jquery.validate.min.js') }}
    <script src='/packages/core/main.js'></script>
    <script src='/packages/interaction/main.js'></script>
    <script src='/packages/daygrid/main.js'></script>
    <script src='/packages/timegrid/main.js'></script>
    <script>

        $(function () {
            var calendarEl = document.getElementById('calendar');

            var  start="";
            var  end="";

           var  selectedDay=[];

            $.ajax({
                type: "POST",
                url:"/menu/getUserDays",
                data:{}
            }).done(function( msg ) {
                if(msg.result) {
                    selectedDay=msg.listDays;
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        plugins: [ 'interaction','dayGrid',],
                        header: {
                            left: 'prev,next',
                            center: 'title',
                            right: 'today'
                        },
                        week:false,
                        month:false,
                        navLinks: true, // can click day/week names to navigate views
                        selectable: true,
                        selectMirror: true,
                        select: function(eve) {
                            console.log(eve);
                        },
                        dragOpacity:0.10,
                        snapDuration:0.1,
                        editable: false,
                        eventLimit: true, // allow "more" link when too many events
                        views: {
                            timeGrid: {
                                eventLimit: 1 // adjust to 6 only for timeGridWeek/timeGridDay
                            },
                            dayGrid:{
                                eventLimit: 1
                            }
                        },
                        events:msg.listEvents ,
                        selectAllow: function(info) {
                            var today = new Date();
                            if (info.start)
                                return false;
                            return true;
                        },
                        eventRender: function(info) {
                            info.el.innerHTML = info.el.innerHTML.replace('ICON', "<em class='fa fa-"+info.event.extendedProps.img+"'></em>");
                        },
                        // eventAllow: function(dropInfo, draggedEvent) {
                        //
                        //     console.log(draggedEvent);
                        //     console.log(dropInfo.start);
                        //     console.log(new Date(2019,9,10));
                        //      if(dropInfo.start < new Date(2019,9,10)){
                        //          return false
                        //      }
                        //
                        //    return dropInfo.start > new Date(2019,9,10); // a boolean
                        // },

                        eventDrop: function(info) {


                            end=info.event.start;

                            var disDay=info.event.start.getFullYear()+"-"+("0" +(info.event.start.getMonth()+ 1)).slice(-2)+"-"+("0" + info.event.start.getDate()).slice(-2);
                            var soursDay=start.getFullYear()+"-"+("0" +(start.getMonth()+1)).slice(-2)+"-"+("0"+start.getDate()).slice(-2);


                            if(start<new Date().getTime() + (2 * 24 * 60 * 60 * 1000)){
                                info.revert()
                            }else if(end<new Date().getTime() + (2 * 24 * 60 * 60 * 1000)){

                                info.revert()
                            }else if(selectedDay.includes(disDay)){
                                info.revert();
                            }else{
                                console.log("run");
                                console.log(selectedDay);
                                console.log(soursDay);
                                selectedDay=selectedDay.filter((value)=>value!=soursDay);
                                console.log(selectedDay);
                                selectedDay.push(disDay);
                                console.log(selectedDay);

                            }

                        },
                        eventDragStart:function (info) {
                            start=info.event.start;
                        },
                    });
                    calendar.render();
                }else{
                    alert("first install app and subscribe");
                }
            });


            $("#save_days").on("click",function () {
                $(this).attr("disabled",true);
                $.ajax({
                    type: "POST",
                    url:"/menu/saveDays",
                    data:{selectedDays:JSON.stringify(selectedDay)}
                }).done(function( msg ) {
                    if(msg.result) {
                        window.location.href="/menu/listDays";
                    }else{
                        $(this).attr("disabled",false);

                    }
                });


            });


        });

    </script>

@stop



