@extends('admin.forms.form')


@section('forms2')
    {{ HTML::style('cpassets/plugins/bootstrap-datepicker/css/datepicker.css') }}

    <input type="hidden" value="{{$user->id}}" name="user_id"  />
    <input type="hidden" value="{{$user->package_duration_id}}" id="user_duration">

    <div class="row">
        <div class="control-group form-group col-sm-12" >

            {{ Form::label('User Package    ','User Package' , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">
                <p> @if(isset($user->package)) {{$user->package->titleEn}} @else ------ @endif </p>
            </div>



        </div>
        <div class="control-group form-group col-sm-12" >

            {{ Form::label('User Package Duration','Package Duration' , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">
                <p> @if(isset($user->packageDuration)) {{$user->packageDuration->titleEn}} @else ------ @endif </p>

            </div>



        </div>
        <div class="control-group form-group col-sm-12" >

            {{ Form::label('Start Package','Start Package' , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">
                <p>@if(isset($user->membership_start)){{$user->membership_start}} @else  ------ @endif</p>

            </div>

        </div>
        <div class="control-group form-group col-sm-12" >

            {{ Form::label('End Package','End Package' , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">
                <p>@if(isset($user->membership_end)) {{$user->membership_end}} @else  ------ @endif</p>
            </div>



        </div>
        <div class="control-group form-group col-sm-12" >

            {{ Form::label(' Remaining Days',' Remaining Days' , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">
                <p> {{$remind_day}}  </p>

            </div>

        </div>
    </div>
    <br/><hr/><hr/>
    <div class="row">
        <div class="control-group form-group col-sm-12" >

            {{ Form::label('Package','Package' , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">
                    <select  id="new_package_id" name="package_id" class="form-control"  >
                        <option selected value="0">None</option>
                        @foreach($packages as $item)
                            <option @if($user->package_id==$item->id)  selected @endif value="{{$item->id}}">{{$item->titleEn}}</option>
                        @endforeach
                    </select>
            </div>
        </div>
        <div class="control-group form-group col-sm-12" >

            {{ Form::label('Package Duration','Package Duration' , array('class' => 'control-label col-sm-4')) }}
            <div class="controls col-sm-4">
                <select  id="new_package_duration_id" name="package_duration_id"   class="form-control"  >
                      <option  selected value="none" >None</option>
                        @foreach($packageDuration as $duration)
                          <option @if($user->package_duration_id==$duration->id)  selected @endif value="{{$duration->id}}">{{$duration->titleEn}}</option>
                        @endforeach
                </select>
            </div>
            <div class="controls col-sm-4">
                {{ Form::label('Attach Day','Attach Day' , array('class' => 'control-label col-sm-2')) }}
                <div class="controls col-sm-10">
                   <input type="checkbox" checked name="attach_day" value="1" class="form-control ">
                </div>

            </div>
        </div>
        <div class="control-group form-group col-sm-12" >

            {{ Form::label('Starting Date','Starting Date' , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                <div class="input-append">

                    {{ Form::text ('starting_date','', array('class' => 'form-control form_datetime','readonly' => '')) }}

                    <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                    <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                </div>
            </div>
        </div>
    </div>

    @stop

@section('custom_foot')
    @parent
    {{ HTML::script('js/moment.js') }}
    {{ HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}


    <script>
        jQuery(document).ready(function($) {
            $(".blue").remove();
            $(".green").remove();


            if($("#new_package_id").val()!=0){
                $.ajax({
                    type: "POST",
                    url:"/admin/package/durations",
                    data: {id:$("#new_package_id").val(),userDurationId:$("#user_duration").val()}
                }).done(function( msg ) {
                    if(msg.result) {
                        $("#new_package_duration_id").html(msg.view);
                    }
                });
            }

            $(".form_datetime").datepicker({
                format: "yyyy-mm-dd",
                pickerPosition:"bottom-right" ,
                language: "en",
                todayBtn: true,
                todayHighlight: true
            }).on('changeDate', function(e){
                $(this).datepicker('hide');
            });

            $("#new_package_id").on("change",function () {
                var packageId=$(this).val();
                $.ajax({
                    type: "POST",
                    url:"/admin/package/durations",
                    data: {id:packageId,userDurationId:$("#user_duration").val()}
                }).done(function( msg ) {
                    if(msg.result) {
                      $("#new_package_duration_id").html(msg.view);
                    }
                });
            });

            $('.form_datetime').on('changeDate', function(ev){
                $(this).datepicker('hide');
            });
        });


      function changePackage(){
           // alert('sdsd');
        }
    </script>


     <script>

        function checkboxes () {

            $('#days-table').delegate('.check-date','click',function(ee) {
                var last_row = $('#days-table tbody tr').last();
                var last_row_date = last_row.find('input.check-date').val();
                if($(this).is(':checked')) {
                    var current_row_date = moment(last_row_date,'YYYY-MM-DD').subtract(1,'days').format("YYYY-MM-DD");
                    $('#membership_end').val(current_row_date);
                    last_row.remove();
                } else {
                    var current_row_date = moment(last_row_date,'YYYY-MM-DD').add(1,'days').format("YYYY-MM-DD");
                    var current_row_date_with_week = moment(last_row_date,'YYYY-MM-DD').add(1,'days').format("dddd YYYY-MM-DD");
                    $('#membership_end').val(current_row_date);
                    var row = '<tr><td><input type="checkbox" checked="checked" class="check-date" name="dates[]" value="' + current_row_date + '" id="dates' + current_row_date + '"></td><td>' + current_row_date_with_week + '</td></tr>'
                    $('#days-table tbody').append(row);
                }
            });
        }
        jQuery(document).ready(function($) {
            $('#monthes').change(function () {

                // Add Expire Date
                var number = $(this).val();
                var membership_start = $('#membership_start').val();
                // var days = 28 * parseInt(number);
                var days = number;
                var membership_end = moment(membership_start, "YYYY-MM-DD").add(days,'days').format("YYYY-MM-DD");
                $('#membership_end').val(membership_end);


                // Get Date Diff
                var moment_start = moment(membership_start,'YYYY-MM-DD');
                var moment_end = moment(membership_end,'YYYY-MM-DD');
                var days_between = moment_end.diff(moment_start, 'days');

                $('#days-table tbody').html('');
                for (var i = 0; i < days_between; i++) {
                    var current_row_date = moment(membership_start,'YYYY-MM-DD').add(i,'days').format("YYYY-MM-DD");
                    var current_row_date_with_week = moment(membership_start,'YYYY-MM-DD').add(i,'days').format("dddd YYYY-MM-DD");
                    var row = '<tr><td><input type="checkbox" checked="checked" class="check-date" name="dates[]" value="' + current_row_date + '" id="dates' + current_row_date + '"></td><td>' + current_row_date_with_week + '</td></tr>'
                    $('#days-table tbody').append(row);
                };
            });
            checkboxes();
        });

    </script>
@stop
