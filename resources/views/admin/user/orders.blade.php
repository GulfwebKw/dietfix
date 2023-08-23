@extends('admin.forms.form')
@section('forms2')
<style>
    .modal { overflow: auto !important; }
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
    <input type="hidden" value="{{$user->id}}" name="user_id"  />

    @if($userDate->count()>=1)
        <div class="row" >
            <div class="col-md-2" style="margin-top:15px;">
                <div class="control-group form-group col-sm-3 center-block "  align="center" >
                    <button style="background-color:#ff422b; color: white" data-package="{{ $user->package_id }}" data-user="{{ $user->id }}" type="button" class="btn   cancel_day">Cancel All Day Package </button>

                </div>
            </div>
            <div class="col-md-2" style="margin-top:15px;">
                <div class="control-group form-group col-sm-3 center-block"  style="margin-right:10px;" align="center" >
                    <button style="background-color:#ff675d; color: white" data-package="{{ $user->package_id }}" data-user="{{ $user->id }}" type="button" class="btn   cancel_active_day">Cancel All Active Day Package </button>
                </div>

            </div>
            <div class="col-md-8">
                <div class="control-group form-group password-strength  " id="password_holder">
                    <label for="count_day" class="control-label col-sm-4">CountDay:</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="count_day" type="number" max="365" min="1" value="1" id="count_day">
                        <button type="button" data-package="{{$user->package_id}}" data-user="{{$user->id}}" id="add_day" name="save_new" style="background:#28a745" value="1" class="btn col-md-4 red"><i class="fa fa-plus"></i> Add</button>
                    </div>

                </div>
            </div>
        </div>
        <br/>
        <hr/>
    @endif


    @foreach($userDate as $collect)
        <div class="row">
            @foreach($collect as $day)
                <div class="control-group form-group col-sm-3 center-block"  align="center" >

                        <h3>
                            <button  @if($day->date<$firstValidDay) disabled  style="background: #9d261d;color: white"  @endif  @if($day->isMealSelected==1) style="background:#28a745;color: white" @else style="background-color:#17a2b8; color: white" @endif   data-day="{{ $day->id }}" data-date="{{$day->date}}" data-package="{{ $user->package_id }}" data-user="{{ $user->id }}" type="button" class="btn   showdate">{{ date('l', strtotime( $day->date))}} - {{ $day->date }} </button>
                        </h3>
                       <a style="margin-right: 12px;margin-left: 12px;" href="#"><i class="fa fa-remove remove_day" data-date="{{$day->date}}" data-user="{{$user->id}}"  data-showdate="{{ date('l', strtotime( $day->date))}} - {{ $day->date }}" style="color: red; margin-right: 2px;margin-left: 2px;">Delete</i></a>
                       <a style="margin-right: 12px;margin-left: 12px;" href="#"><i class="fa fa-trash permanent_remove_day" data-date="{{$day->date}}" data-user="{{$user->id}}"   data-showdate="{{ date('l', strtotime( $day->date))}} - {{ $day->date }}" style="color: red; margin-right: 2px;margin-left: 2px;">Permanent Delete</i></a>

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
    @parent

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"  onclick="forceCloseModal()"></button>
                    <h3 id="myModalLabel">Are you sure?</h3>
                </div>
                <div class="modal-body">
                    <p>You want to disable <span id="dateToDelete"></span> and do not add any new date!</p>
                </div>
                <div class="modal-footer">
                    <button class="btn" onclick="forceCloseModal()">Close</button>
                    <button class="btn" style="color: white;text-shadow: none;background-color: #4d90fe;" onclick="forceUncheck()">Delete with out new date</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var idOFunCheck1 = null;
        var idOFunCheck2 = null;
        function forceUncheck() {
            $.ajax({
                type: "GET",
                url:  '{{ url($menuUrl.'/cancelSingleDays')}}'+"/"+ idOFunCheck2 +"/"+ idOFunCheck1 +'?notInsert=true',
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
            forceCloseModal();
        }
        function forceCloseModal(){
            $('body').removeClass('modal-open');
            $('#deleteModal').modal('hide');
        }

        jQuery(document).ready(function($) {
            $(".blue").remove();
            $(".green").remove();
            $(".yellow").remove();


            $(".showdate").click(function  (e) {
                e.preventDefault();
                var p = $(this).attr('data-package');
                var d = $(this).attr('data-day');
                var u = $(this).attr('data-user');
                $('#content-model').html("<div align='center'><div class=\"loader\"></div></div>");
                $('.modal').modal();

                $.get('/admin/users/order/'+p+'/'+d+'/'+u, null, function(json, textStatus) {
                    $('#content-model').html(json);
                     $('.modal').modal();
                    $('.item-radio').click(function(){
                        $(this).parent().parent().find('.item-checks').prop('checked', false);
                    });
                });
            });

        });
        $(function () {
            $(".cancel_day").on("click",function () {
                var r = confirm("Are you sure?");
                if (r == true) {
                    $.ajax({
                        type: "GET",
                        url:  '{{ url($menuUrl.'/cancelDays')}}'+"/"+$(this).attr('data-user'),
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
                }
            });

            $(".cancel_active_day").on("click",function () {
                var r = confirm("Are you sure?");
                if (r == true) {
                    $.ajax({
                        type: "GET",
                        url:  '{{ url($menuUrl.'/cancelActiveDays')}}'+"/"+$(this).attr('data-user'),
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
                }
            });
            $(".remove_day").on("click",function () {
                var r = confirm("Are you sure?");
                if (r == true) {
                    $.ajax({
                        type: "GET",
                        url:  '{{ url($menuUrl.'/cancelSingleDays')}}'+"/"+$(this).attr('data-user')+"/"+$(this).attr("data-date"),
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
                }
            });
            $(".permanent_remove_day").on("click",function () {
                $('#dateToDelete').html($(this).attr('data-showdate'));
                idOFunCheck1 = $(this).attr('data-date');
                idOFunCheck2 = $(this).attr('data-user');
                $('#deleteModal').modal('show');
            });
            $("#add_day").on("click",function () {
                var i= $("#count_day").val();
                if(i >= 1){
                    var packId=$(this).attr("data-package");
                    var user=$(this).attr("data-user");
                   if(packId>=1){
                       $.ajax({
                           type: "POST",
                           url:"/admin/users/addDay",
                           data: {countDay:i,userId:user}
                       }).done(function( msg ) {
                           if(msg.result) {
                               window.location.href="/admin/users/orders/"+user;
                           }
                       });

                   }else{
                       alert("Please first Add Membership");
                   }

                }else{
                    alert("Please enter a number greater than zero ")
                }

            });

        });
    </script>

@stop
