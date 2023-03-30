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


    @if($errors->count()>=1)
        <div class="alert alert-block alert-danger fade in">
            <ul>
                @foreach ($errors->all('<li>:message</li>') as $k => $message)
                    {!! $message  !!}
                @endforeach
            </ul>
        </div>
    @endif



    <div class="container-fluid">

        <!-- BEGIN PAGE HEADER-->

        <div class="row-fluid">
            <form method="POST" action="/admin/users/save" accept-charset="UTF-8" class="form-horizontal form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data">
                                 {{csrf_field()}}

                            <div class="row">
                                <div class="control-group form-group  col-sm-6" id="username_holder">

                                    <label for="username" class="control-label col-sm-4">User Name</label>

                                    <div class="controls col-sm-8">

                                        <input class="form-control" name="username" value="{{$item->username}}" type="text" id="username">

                                    </div>

                                </div>

                                <div class="control-group form-group  col-sm-6" id="email_holder">








                                    <label for="email" class="control-label col-sm-4">Email Address</label>

                                    <div class="controls col-sm-8">
                                        <input class="form-control" name="email" type="email" value="{{$item->email}}" id="email">

                                    </div>








                                </div>
                                <!--<div class="clearfix"></div>-->
                               <!--
                                <div class="control-group form-group  col-sm-6" id="phone_holder">
                                    <label for="phone" class="control-label col-sm-4">Phone No.</label>
                                    <div class="controls col-sm-8">
                                        <input class="form-control" name="phone" type="text" value="{{$item->phone}}" id="phone">
                                    </div>
                                </div>
                                -->

                                <div class="control-group form-group  col-sm-6" id="mobile_number_holder">



                                    <label for="mobile_number" class="control-label col-sm-4">Mobile</label>

                                    <div class="controls col-sm-8">

                                        <input class="form-control" name="mobile_number" type="text" value="{{$item->mobile_number}}" id="mobile_number">

                                    </div>

                                </div>

                                <!--<div class="clearfix"></div>-->


                                <div class="control-group form-group password-strength  col-sm-6" id="password_holder">

                                    <label for="password" class="control-label col-sm-4">Password</label>

                                    <div class="controls col-sm-8">

                                        <input class="form-control" name="password" type="password" value="" id="password">

                                    </div>

                                </div>
                                <div class="control-group form-group password-strength  col-sm-6" id="password_holder">
                                </div>

                            </div>


                        <hr/>
                        <br/>
                            <div class="row">
                                <label for="province_id" class="control-label col-sm-6"> Weekdays address</label>
                            </div>
                        <hr/>
                        <br/>






                        <div class="row">
                            <div class="control-group form-group  col-lg-6" id="country_id_holder">
                                <label for="country_id" class="control-label col-sm-4">Country</label>
                                <div class="controls col-sm-8">
                                    <select name="country_id" id="country_id" class=" chosen-select" >
                                        @foreach($countries as $cou)
                                            <option @if($cou->id==$item->country_id)  selected @endif value="{{$cou->id}}">{{$cou->titleEn}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="control-group form-group  col-lg-6" id="province_id_holder">
                                <label for="province_id" class="control-label col-sm-4">Province</label>
                                <div class="controls col-sm-8">
                                    <select name="province_id" id="province_id" class="chosen-select">
                                        @foreach($provinces as $prov)
                                            <option value="{{$prov->id}}" @if($item->province_id==$prov->id) selected @endif>{{$prov->titleEn}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="control-group form-group  col-lg-6" id="area_id_holder">
                                <label for="area_id" class="control-label col-sm-4">Area</label>
                                <div class="controls col-sm-8">
                                    <select name="area_id" id="area_id" class="chosen-select">
                                        @foreach($areas as $area)
                                            <option value="{{$area->id}}" @if($area->id==$item->area_id)  selected @endif  >{{$area->titleEn}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="control-group form-group  col-lg-6" id="block_holder">
                                <label for="block" class="control-label col-sm-4">Block</label>
                                <div class="controls col-sm-8">
                                    <input class="form-control" name="block" type="text" value="{{$item->block}}" id="block">
                                </div>
                            </div>
                        </div>

                       <div class="row">
                        <div class="control-group form-group  col-lg-6" id="Street_holder">
                            <label for="block" class="control-label col-sm-4">Street</label>
                            <div class="controls col-sm-8">
                                <input class="form-control" name="street" type="text" value="{{$item->street}}" id="street">
                            </div>
                        </div>
                        <div class="control-group form-group  col-lg-6" id="avenue_holder">
                            <label for="avenue" class="control-label col-sm-4">Avenue</label>
                            <div class="controls col-sm-8">
                                <input class="form-control" name="avenue" type="text" value="{{$item->avenue}}" id="avenue">

                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="control-group form-group  col-lg-6" id="house_number_holder">
                            <label for="house_number" class="control-label col-sm-4">House/Flat No</label>
                            <div class="controls col-sm-8">
                                <input class="form-control" name="house_number" type="text" value="{{$item->house_number}}" id="house_number">
                            </div>

                        </div>
                            <div class="control-group form-group  col-lg-6" id="floor_holder">
                                <label for="floor" class="control-label col-sm-4">Floor</label>
                                <div class="controls col-sm-8">
                                    <input class="form-control" name="floor" type="text" value="{{$item->floor}}" id="floor">
                                </div>

                            </div>

                        </div>
                <div class="row">
                            <div class="control-group form-group  col-lg-6" id="building_holder">
                                <label for="building_number" class="control-label col-sm-4">Building/Villa Number</label>
                                <div class="controls col-sm-8">
                                    <input class="form-control" name="building_number" type="text" value="{{$item->building_number}}" id="building_number">
                                </div>

                            </div>
                            <div class="control-group form-group  col-lg-6" >
                                <label for="house_number_work" class="control-label  col-sm-6">Is Weekend Address Same</label>
                                <div class="controls col-sm-6">
                                    <div class="switch" data-on="success" data-off="danger">
                                        {{ Form::checkbox('is_weekend_address_same',1, $item->is_weekend_address_same ? true : null , array('class' => 'toggle','disabled'=>false)) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div class="row">
                        <div class="control-group form-group  col-sm-12" id="address_holder">
                            <label for="address" class="control-label col-sm-2">Address</label>

                            <div class="controls col-sm-10">
                                <textarea class="form-control" name="address" cols="50" rows="10" id="address">{{$item->address}}</textarea>
                            </div>

                        </div>
                     </div>


                    <hr/>
                    <br/>

                         <div class="row">
                            <label for="province_id" class="control-label col-sm-6">Weekend Address</label>
                        </div>

                    <hr/>
                    <br/>


                <div class="control-group form-group  col-sm-6" id="country_work_id_holder">
                    <label for="country_work_id" class="control-label col-sm-4">Country</label>
                    <div class="controls col-sm-8">
                        <select name="country_work_id" id="country_work_id" class="chosen-select" style="display: none;">

                            @foreach($countries as $cou)
                                <option @if($cou->id==$item->country_id)  selected @endif value="{{$cou->id}}">{{$cou->titleEn}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="control-group form-group  col-sm-6" id="province_work_id_holder">
                    <label for="province_work_id" class="control-label col-sm-4">Province</label>

                    <div class="controls col-sm-8">

                        <select name="province_work_id" id="province_work_id" class="nochosen">
                            @foreach($provinces as $prov)
                                <option value="{{$prov->id}}" @if($item->province_work_id==$prov->id) selected @endif>{{$prov->titleEn}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="control-group form-group  col-sm-6" id="area_work_id_holder">
                    <label for="area_work_id" class="control-label col-sm-4">Area </label>
                    <div class="controls col-sm-8">
                        <select name="area_work_id" id="area_work_id" class="nochosen">

                            @foreach($areas as $area)
                                <option value="{{$area->id}}" @if($area->id==$item->area_work_id)  selected @endif  >{{$area->titleEn}}</option>
                            @endforeach

                        </select>

                    </div>






                </div>
                <div class="control-group form-group  col-sm-6" id="block_work_holder">
                    <label for="block_work" class="control-label col-sm-4">Block</label>
                    <div class="controls col-sm-8">

                        <input class="form-control" name="block_work" type="text" value="{{$item->block_work}}" id="block_work">

                    </div>

                </div>
                <div class="control-group form-group  col-sm-6" id="Street_holder">
                    <label for="block" class="control-label col-sm-4">Street</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="street_work" type="text" value="{{$item->street_work}}" id="street">
                    </div>
                </div>
                <div class="control-group form-group  col-sm-6" id="avenue_work_holder">
                    <label for="avenue_work" class="control-label col-sm-4">Avenue</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="avenue_work" type="text" value="{{$item->avenue_work}}" id="avenue_work">
                    </div>
                </div>
                <div class="control-group form-group  col-sm-6" id="house_number_work_holder">


                    <label for="house_number_work" class="control-label col-sm-4">House Number</label>

                    <div class="controls col-sm-8">
                        <input class="form-control" name="house_number_work" type="text" value="{{$item->house_number_work}}" id="house_number_work">
                    </div>

                </div>


                <div class="control-group form-group  col-sm-6" id="floor_work_holder">
                    <label for="floor_work" class="control-label col-sm-4">Floor</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="floor_work" type="text" value="{{$item->floor_work}}" id="floor_work">
                    </div>

                </div>
                <div class="control-group form-group  col-sm-6" id="building_work_holder">
                    <label for="building_number_work" class="control-label col-sm-4">Building/Villa Number</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="building_number_work" type="text" value="{{$item->building_number_work}}" id="building_number_work">
                    </div>

                </div>





                <div class="control-group form-group  col-sm-12" id="address_work_holder">


                    <label for="address_work" class="control-label col-sm-2">Address </label>

                    <div class="controls col-sm-10">
                        <textarea class="form-control" name="address_work" cols="50" rows="10" id="address_work">{{$item->address_work}}</textarea>
                    </div>

                </div>

                <hr/>
                <br/>

                <div class="row">
                    <label for="province_id" class="control-label col-sm-6">Other</label>
                </div>

                <hr/>
                <br/>

                <input name="role_id" type="hidden" value="{{$item->role_id}}">




                <div class="control-group form-group  col-sm-6" id="clinic_id_holder">

                    <label for="clinic_id" class="control-label col-sm-4">Clinic</label>

                    <div class="controls col-sm-8">



                        <select name="clinic_id" id="clinic_id" class="chosen-select" style="display: none;">
                            @foreach($clinics as $clinic)
                                 <option @if($clinic->id==$item->clinic_id) selected="" @endif value="{{$clinic->id}}">{{$clinic->titleEn}}</option>
                            @endforeach
                        </select>


                    </div>


                </div>
                <div class="control-group form-group  col-sm-6" id="doctor_id_holder">
                    <label for="doctor_id" class="control-label col-sm-4">Doctor</label>
                    <div class="controls col-sm-8">
                        <select name="doctor_id" id="doctor_id" class="chosen-select" style="display: none;">
                            @foreach($doctors as $doctor)
                                <option value="{{$doctor->id}}"  @if($doctor->id==$item->doctor_id)  selected @endif>{{$doctor->username}}</option>
                            @endforeach
                        </select>


                    </div>






                </div>
                <div class="control-group form-group  col-sm-6" id="sex_holder">
                    <label for="sex" class="control-label col-sm-4">Sex</label>
                    <div class="controls col-sm-8">
                        <select class="chosen-select" id="sex" name="sex" style="display: none;">
                            @foreach($sex as $k=>$val)
                                <option value="{{$k}}"  @if($k==$item->sex) selected @endif > {{$val}}</option>
                            @endforeach

                        </select>
                    </div>


                </div>
                <div class="control-group form-group  col-sm-6" id="salt_holder">
                    <label for="salt" class="control-label col-sm-4">Salt</label>

                    <div class="controls col-sm-8">
                        <select class="chosen-select" id="salt" name="salt" style="display: none;">
                            @foreach($salt as $k=>$val)
                                <option value="{{$k}}"  @if($k==$item->salt) selected @endif > {{$val}}</option>
                            @endforeach
                        </select>
                    </div>





                </div>
                <div class="control-group form-group  col-sm-6" id="delivery_holder">
                    <label for="delivery" class="control-label col-sm-4">Delivery Time</label>
                    <div class="controls col-sm-8">
                        <select class="chosen-select" id="delivery" name="delivery" style="display: none;">
                            @foreach($delivery as $k=>$val)
                                <option value="{{$k}}"  @if($k==$item->delivery) selected @endif > {{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="control-group form-group  col-sm-6" id="delivery_type_holder">
                    <label for="delivery_type" class="control-label col-sm-4">Delivery Type</label>
                    <div class="controls col-sm-8">
                        <select class="chosen-select" id="delivery_type" name="delivery_type" style="display: none;">
                            @foreach($deliveryType as $k=>$val)
                                <option value="{{$val->id}}"  @if($val->id==$item->delivery_type) selected @endif > {{$val->type_en}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="control-group form-group  col-sm-6" id="height_holder">

                    <label for="height" class="control-label col-sm-4">Height</label>

                    <div class="controls col-sm-8">

                        <input class="form-control" name="height" type="text" value="{{$item->height}}" id="height">

                    </div>

                </div>
                <div class="control-group form-group  col-sm-6" id="weight_holder">
                    <label for="weight" class="control-label col-sm-4">Weight</label>
                    <div class="controls col-sm-8">

                        <input class="form-control" name="weight" type="text" value="{{$item->weight}}" id="weight">

                    </div>

                </div>
                <div class="control-group form-group  col-sm-6" id="bmi_holder">
                    <label for="bmi" class="control-label col-sm-4">BMI</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="bmi" value="{{$item->bmi}}" type="text" id="bmi">
                    </div>
                </div>

                <div class="control-group form-group  col-sm-6" id="date_t_holder">
                    <label for="date_t" class="control-label col-sm-4">Date of Birth</label>
                    <div class="controls col-sm-8">

                        <input class="form-control" name="date_t" value="{{$item->date_t}}" type="date" id="date_t">

                    </div>
                </div>
                <div class="control-group form-group  col-sm-6" id="bm_holder">
                    <label for="bm" class="control-label col-sm-4">BM</label>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="bm" value="{{$item->bm}}" type="text" id="bm">
                    </div>
                </div>
                <div class="control-group form-group  col-sm-6" id="loss_holder">

                </div>
                <div class="control-group form-group  col-sm-6" id="created_at_holder">


                    <label for="created_at" class="control-label col-sm-4">Created Date</label>

                    <div class="controls col-sm-8">

                        <div class="form-control">{{$item->created_at}}</div>

                    </div>
                </div>
                <div class="control-group form-group  col-sm-6" id="updated_at_holder">

                    <label for="updated_at" class="control-label col-sm-4">Updated Date</label>
                    <div class="controls col-sm-8">
                        <div class="form-control">{{$item->updated_at}}</div>
                    </div>

                </div>
                <div class="control-group form-group  col-sm-12" id="standard_menu_id_holder">
                    <label for="standard_menu_id" class="control-label col-sm-4">Standard Menu</label>
                    <div class="controls col-sm-8">

                        <select name="standard_menu_id" id="standard_menu_id" class="chosen-select" style="display: none;">
                            @foreach($standard_menus as $val)
                                <option value="{{$val->id}}"  @if($val->id==$item->standard_menu_id) selected @endif > {{$val->titleEn}}</option>
                            @endforeach

                        </select>

                    </div>






                </div>

                <div class="control-group form-group  col-sm-4" id="active_holder">
                    <label for="active" class="control-label col-sm-4">Active</label>
                    <div class="controls col-sm-8">
                        <div class="switch" data-on="success" data-off="danger">
                            {{ Form::checkbox('active',1, $item->active ? true : null , array('class' => 'toggle')) }}
                        </div>
                    </div>
                </div>
                <div class="control-group form-group  col-sm-4" id="demo_holder">
                    <label for="demo" class="control-label col-sm-4">Is Demo</label>
                    <div class="controls col-sm-8">
                        <div class="switch" data-on="success" data-off="danger">
                            {{ Form::checkbox('is_demo',1,$item->role_id==20 ? true : null, array('class' => 'toggle')) }}
                        </div>
                    </div>
                </div>
                <div class="control-group form-group  col-sm-4" id="active_holder">
                    <label for="active" class="control-label col-sm-4">Affect Priority in Ordering</label>
                    <div class="controls col-sm-8">
                        <div class="switch" data-on="success" data-off="danger">
                            {{ Form::checkbox('priority_in_ordering',1, $item->priority_in_ordering ? true : null , array('class' => 'toggle')) }}
                        </div>

                    </div>
                </div>



                <div class="control-group form-group  col-sm-12" id="note_holder">
                    <label for="note" class="control-label col-sm-2">Note</label>

                    <div class="controls col-sm-10">

                        <textarea class="form-control" name="note" cols="50" rows="10" id="note">{{$item->note}}</textarea>

                    </div>

                </div>

                <input name="id" type="hidden" value="{{$item->id}}">


                <hr>
                <br>
                <div class="row">
                   <label for="province_id" class="control-label col-sm-6"> Renew/Add Membership</label>
                </div>
                <hr/>
                <br/>

                <div class="row">
                    <div class="control-group form-group col-sm-12" >

                        {{ Form::label('Package','Package' , array('class' => 'control-label col-sm-4')) }}

                        <div class="controls col-sm-8">
                            <select  id="new_package_id" name="package_id" class="form-control"  >
                                <option selected value="0">None</option>
                                @foreach($packages as $pack)
                                    <option @if($item->package_id==$pack->id)  selected @endif value="{{$pack->id}}">{{$pack->titleEn}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="control-group form-group col-sm-12" >

                        {{ Form::label('Package Duration','Package Duration' , array('class' => 'control-label col-sm-4')) }}
                        <div class="controls col-sm-4">
                            <select  id="new_package_duration_id" name="package_duration_id"   class="form-control" >
                                <option  selected value="" >None</option>
                                @foreach($packageDuration as $duration)
                                    <option @if($item->package_duration_id==$duration->id)  selected @endif value="{{$duration->id}}">{{$duration->titleEn}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="controls col-sm-4">
                            {{ Form::label('Attach Day','Attach Day' , array('class' => 'control-label col-sm-2')) }}
                            <div class="controls col-sm-10">
                                <input type="checkbox"  name="attach_day" value="1" class="form-control ">
                            </div>
                        </div>
                    </div>
                    <div class="control-group form-group col-sm-6" >

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


                <div class="control-group form-group col-sm-6" >

                    <div class="col-sm-4">
                        <label for="count_day" class="control-label col-sm-4">CountDay:</label>

                    </div>
                    <div class="controls col-sm-8">
                        <input class="form-control" name="count_day" type="number" max="365" min="0" value="0" id="count_day">
                    </div>


                </div>


                <hr/>
                <br/>

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
    {{ HTML::script('js/moment.js') }}
    {{ HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}

    @parent

    <script>

        CKEDITOR_BASEPATH  = "{{ url('cpassets/plugins/ckeditor') }}/";

        $(function () {
            $(".chosen-select").chosen();


            // if($("#new_package_id").val()!=0){
            //     $.ajax({
            //         type: "POST",
            //         url:"/admin/package/durations",
            //         data: {id:$("#new_package_id").val(),userDurationId:$("#user_duration").val()}
            //     }).done(function( msg ) {
            //         if(msg.result) {
            //             $("#new_package_duration_id").html(msg.view);
            //         }
            //     });
            // }




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
