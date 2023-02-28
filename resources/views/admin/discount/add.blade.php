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
            <form method="POST" action="/admin/discount/save" accept-charset="UTF-8" class="form-horizontal form-bordered form-row-stripped spaceForm" role="form">
                {{csrf_field()}}

                <div class="row">


                    <div class="control-group form-group  col-sm-6" id="username_holder">

                        <label for="username" class="control-label col-sm-4">Arabic Title</label>

                        <div class="controls col-sm-8">

                            <input class="form-control" name="titleAr"  type="text" id="titleAr" value="{{ old('titleAr') }}">

                        </div>

                    </div>


                    <div class="control-group form-group  col-sm-6" id="username_holder">

                        <label for="username" class="control-label col-sm-4">English Title</label>

                        <div class="controls col-sm-8">

                            <input class="form-control" name="titleEn"  type="text" id="titleEn" value="{{ old('titleEn') }}">

                        </div>

                    </div>



                    <div class="control-group form-group  col-sm-6" id="username_holder">
                        <label for="username" class="control-label col-sm-4">Discount Code</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="code"  type="text" id="code" value="{{ old('code') }}">
                        </div>
                    </div>


                    <div class="control-group form-group  col-sm-6" id="username_holder">
                        <label for="username" class="control-label col-sm-4">Value</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="value"  type="text" id="value" value="{{ old('value') }}">
                        </div>
                    </div>


                    <div class="control-group form-group  col-sm-6" id="username_holder">
                        <label for="username" class="control-label col-sm-4">All Limitation</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="count_limit"  type="text" id="count_limit" value="{{ old('count_limit') }}">
                        </div>
                    </div>


                    <div class="control-group form-group  col-sm-6" id="username_holder">
                        <label for="username" class="control-label col-sm-4">User Limitation</label>
                        <div class="controls col-sm-8">
                            <input class="form-control" name="count_limit_user"  type="text" id="count_limit_user" value="{{ old('count_limit_user') }}">
                        </div>
                    </div>

                    <div class="control-group form-group  col-sm-6" id="country_id_holder">
                        <label for="country_id" class="control-label col-sm-4">Type</label>
                        <div class="controls col-sm-8">
                            <select name="type" id="type" class=" chosen-select" >
                                    <option @if("Percent"==old('type'))  selected @endif value="Percent">Percent</option>
                                    <option @if("KD"==old('type'))  selected @endif value="KD">KD</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group form-group col-sm-6" ></div>

                    <div class="control-group form-group col-sm-6" >

                        {{ Form::label('Starting Date','Starting Date' , array('class' => 'control-label col-sm-4')) }}

                        <div class="controls col-sm-8">

                            <div class="input-append">

                                {{ Form::text ('start','', array('class' => 'form-control form_datetime','readonly' => '')) }}

                                <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                                <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                            </div>
                        </div>
                    </div>

                    <div class="control-group form-group col-sm-6" >

                        {{ Form::label('End Date','End Date' , array('class' => 'control-label col-sm-4')) }}

                        <div class="controls col-sm-8">

                            <div class="input-append">

                                {{ Form::text ('end','', array('class' => 'form-control form_datetime','readonly' => '')) }}

                                <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                                <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                            </div>
                        </div>
                    </div>





                    <div class="control-group form-group  col-sm-6" id="active_holder">
                        <label for="active" class="control-label col-sm-4">Active</label>
                        <div class="controls col-sm-8">
                            <div class="switch" data-on="success" data-off="danger">

                                {{ Form::checkbox('active',1, null , array('class' => 'toggle')) }}

                            </div>

                        </div>


                    </div>



                </div>


                <div class="form-actions">
                    <button type="submit" name="save_next" value="1" class="btn col-md-3 green"><i class="fa fa-arrow-right"></i> Save And Next</button>
                    <button type="submit" class="btn col-md-3 yellow"><i class="fa fa-check"></i> Save</button>
                    <button type="submit" name="save_return" value="1" class="btn col-md-3 blue"><i class="fa fa-reply"></i> Save &amp; Return</button>
                    <button type="submit" name="save_new" value="1" class="btn col-md-3 green"><i class="fa fa-plus"></i> Save &amp; Ne</button>
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



            $(".form_datetime").datepicker({
                format: "yyyy-mm-dd",
                pickerPosition:"bottom-right" ,
                language: "en",
                todayBtn: true,
                todayHighlight: true
            }).on('changeDate', function(e){
                $(this).datepicker('hide');
            });

            // $("#new_package_id").on("change",function () {
            //     var packageId=$(this).val();
            //     $.ajax({
            //         type: "POST",
            //         url:"/admin/package/durations",
            //         data: {id:packageId,userDurationId:$("#user_duration").val()}
            //     }).done(function( msg ) {
            //         if(msg.result) {
            //             $("#new_package_duration_id").html(msg.view);
            //         }
            //     });
            // });

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
