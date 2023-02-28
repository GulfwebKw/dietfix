@extends('admin.forms.form')



@section('custom_head_include')

    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js') }}
    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js') }}
    {{ HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css') }}
    {{ HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css') }}
    {{ HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') }}
    {{ HTML::style('cpassets/plugins/bootstrap-datepicker/css/datepicker.css') }}


    {{ HTML::style('cpassets/css/pqgrid.min.css') }}
    {{ HTML::script('cpassets/js/pqgrid.min.js') }}


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

    @if(isset($errors))
        @if($errors->count()>=1)
            <div class="alert alert-block alert-danger fade in">
                <ul>
                    @foreach ($errors->all('<li>:message</li>') as $k => $message)
                        {!! $message  !!}
                    @endforeach
                </ul>
            </div>
        @endif
    @endif



    <div class="container-fluid">

        <!-- BEGIN PAGE HEADER-->

        <div class="row-fluid">
            <form method="POST" action="/admin/invoices/save" accept-charset="UTF-8" class="form-horizontal form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data">
                {{csrf_field()}}

                    <div class="row">
                        <div class="control-group form-group col-sm-12" >

                            {{ Form::label('User','User' , array('class' => 'control-label col-sm-4')) }}

                            <div class="controls col-sm-8">
                                <select  id="user_id" name="user" class="form-control"  >
                                    <option selected value="0">User</option>
                                    @foreach($users as $user)
                                        <option @if($user->id==old('user_id'))  selected @endif value="{{$user->id}}" >{{$user->username}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group form-group col-sm-12" >

                            {{ Form::label('Package','Package' , array('class' => 'control-label col-sm-4')) }}

                            <div class="controls col-sm-8">
                                <select  id="new_package_id" name="package" class="form-control"  >
                                    <option selected value="0">None</option>
                                    @foreach($packages as $pack)
                                        <option @if($pack->id==old('package_id'))  selected @endif value="{{$pack->id}}" >{{$pack->titleEn}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group form-group col-sm-12" >

                            {{ Form::label('Package Duration','Package Duration' , array('class' => 'control-label col-sm-4')) }}
                            <div class="controls col-sm-8">
                                <select  id="new_package_duration_id" name="package_duration"   class="form-control"  >
                                    <option  selected value="none" >None</option>
                                    @foreach($packageDuration as $duration)
                                        <option @if($duration->id==old('package_duration_id'))  selected @endif  value="{{$duration->id}}">{{$duration->titleEn}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group form-group col-sm-12" >
                            {{ Form::label('Quantity','Quantity' , array('class' => 'control-label col-sm-4')) }}
                            <div class="controls col-sm-8">
                                <input class="form-control" name="count" type="number" min="1" id="count" value="{{ old('count') }}">
                            </div>
                        </div>
                        <div class="control-group form-group col-sm-12" >
                            {{ Form::label('Description','Description' , array('class' => 'control-label col-sm-4')) }}
                            <div class="controls col-sm-8">
                                <textarea class="form-control" name="description" cols="50" rows="10" id="description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>


                    <hr/>
                    <br/>
                    <div class="form-actions">
                        <button type="submit" class="btn col-md-3 yellow"><i class="fa fa-check"></i> Save</button>
{{--                        <button type="submit" name="save_return" value="1" class="btn col-md-3 blue"><i class="fa fa-reply"></i> Save &amp; Return</button>--}}
{{--                        <button type="submit" name="save_new" value="1" class="btn col-md-3 green"><i class="fa fa-plus"></i> Save &amp; New</button>--}}
                    </div>



            </form>

        </div>

    </div>

@endsection
@section('custom_foot')

    @parent
    {{ HTML::script('js/moment.js') }}
    {{ HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}


    <script>

        CKEDITOR_BASEPATH  = "{{ url('cpassets/plugins/ckeditor') }}/";

        $(function () {
            $(".chosen-select").chosen();


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

            // $('.form_datetime').on('changeDate', function(ev){
            //     $(this).datepicker('hide');
            // });

            $(".form_datetime").datepicker({
                format: "yyyy-mm-dd",
                pickerPosition:"bottom-right" ,
                language: "en",
                todayBtn: true,
                todayHighlight: true
            }).on('changeDate', function(e){
                $(this).datepicker('hide');
            });

        });

    </script>

    {{ HTML::script('/cpassets/plugins/ckeditor/ckeditor.js') }}

    {{ HTML::script('/cpassets/plugins/ckeditor/adapters/jquery.js') }}

    {{ HTML::script('/cpassets/plugins/ckeditor/own.js') }}





@stop
