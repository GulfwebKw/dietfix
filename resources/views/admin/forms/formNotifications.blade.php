@extends('admin.layouts.'.$extender)



@section('content')

@section('custom_foot')

    @parent

    <script>

        $('.control-group.col-sm-6').each(function (i) {

            var z = i + 1;

            if(z % 2 == 0) {

                $(this).after('<div class="clearfix"></div>');

            }

        });

        $('.control-group.col-sm-4').each(function (i) {

            if((i+1) % 3 == 0) {

                $(this).after('<div class="clearfix"></div>');

            }

        });

        var control = $(".control-group.form-group:not(.col-sm-6,.col-sm-4)");

        $(control).find('label.col-sm-4').removeClass('col-sm-4').addClass('col-sm-2');

        $(control).find('div.col-sm-8').removeClass('col-sm-8').addClass('col-sm-10');

    </script>

@stop


@if($errors->count()>=1)

    <div class="alert alert-block alert-danger fade in">

        <ul>

            @foreach ($errors->all('<li>:message</li>') as $k => $message)

                {!! $message  !!}

            @endforeach

        </ul>

    </div>

@endif


@if(session()->has('message'))
    <div class="alert alert-{{session()->get('status')}}">
        {{session()->get('message')}}
    </div>
@endif

@if($uploadable)

    {{ Form::open(array('url' => $url, 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form')) }}

@else

    {{ Form::open(array('url' => $url, 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form')) }}

@endif



@yield('forms1')



@foreach($fields as $field)



    <?php $name = $field['name']; ?>

    <?php $val = Input::old($name); ?>



    @if (isset($val) && !empty($val))

        <?php $value = $val; ?>

    @elseif (isset($item->$name))

        <?php $value = $item->$name; ?>

    @elseif (isset($field['value']))

        <?php $value = $field['value']; ?>

    @else

        <?php $value = null; ?>

    @endif





    <div class="control-group form-group @if($field['type'] == 'password')password-strength @endif @if(isset($field['col']))col-sm-{{ (12/$field['col']) }}@endif" id="{{ $field['name'] }}_holder">





        @if($field['type'] == 'text')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                {{ Form::text($field['name'] , $value , array('class' => 'form-control')) }}

            </div>



        @elseif($field['type'] == 'textarea')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                {{ Form::textarea ($field['name'] , $value , array('class' => 'form-control')) }}

            </div>



        @elseif($field['type'] == 'div')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                <div class="form-control autoheight">{{ $value }}</div>

            </div>



        @elseif($field['type'] == 'color')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                <div class="input-append color colorpicker-default" data-color="{{ $value }}" data-color-format="hex">

                    {{ Form::text ($field['name'] , $value , array('class' => 'form-control colorpicker')) }}

                    <span class="add-on"><i style="background-color: {{ $value }};"></i></span>

                </div>

            </div>



            @if(!isset($colorLoadedBefore))

        @section('custom_foot')

            @parent

            {{ HTML::script('cpassets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}

            <script>$('.colorpicker-default').colorpicker();</script>

        @stop

        @else

            <?php $colorLoadedBefore = true; ?>

        @endif





        @elseif($field['type'] == 'many2many')

            <?php $parent_id = (isset($field['parent_id'])) ? $field['parent_id'] : 'id'; ?>

            <?php $parent_title = $field['parent_title']; ?>

            <?php $fName = $field['name']; ?>

            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                <select id="{{ $fName }}" multiple="multiple" class="form_many_2_many">

                    @if (isset($item))

                        <?php $value = optional($item->$fName)->pluck('id')->toArray(); ?>

                    @endif

                    @foreach ($field['data'] as $i)

                        @if (!$value)

                            <option value="{{ $i->$parent_id }}">{{ $i->$parent_title }}</option>

                        @else

                            <option value="{{ $i->$parent_id }}" @if (in_array($i->$parent_id,$value)) selected="selected" @endif>{{ $i->$parent_title }}</option>

                        @endif

                    @endforeach

                </select>

            </div>



            @if(!isset($many2ManyBefore))

        @section('custom_foot')

            @parent

            {{ HTML::style('cpassets/plugins/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css') }}

            {{ HTML::script('cpassets/plugins/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js') }}

            {{ HTML::script('cpassets/plugins/bootstrap-multiselect-master/dist/js/bootstrap-multiselect-collapsible-groups.js') }}

        @stop

        @else

            <?php $many2ManyBefore = true; ?>

        @endif



        @section('custom_foot')

            @parent

            <script>

                jQuery(document).ready(function($) {

                    $("#{{ $fName }}").multiselect({

                        maxHeight: 200,

                        checkboxName: '{{ $field['name'] }}[]',

                        buttonContainer: '<div class="btn-group btn-block" />',

                        buttonClass: 'btn btn-block',

                        disableIfEmpty: true,

                        dropRight: true,

                        delimiterText: ', ',

                        includeSelectAllOption: true,

                        enableFiltering: true,

                        enableClickableOptGroups: true,

                        enableCaseInsensitiveFiltering: true,

                        nonSelectedText: '{{ trans('main.Choose') }}',

                        allSelectedText: '{{ trans('main.All') }}',

                        selectAllText: '{{ trans('main.All') }}',

                        filterPlaceholder: '{{ trans('main.Search') }}'

                    });

                });

            </script>

        @stop



        @elseif($field['type'] == 'datetime')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                <div class="input-append">

                    {{ Form::text ($field['name'] , $value , array('class' => 'form-control form_datetime', 'readonly' => '')) }}

                    <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                    <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                </div>

            </div>



            @if(!isset($datetimeLoadedBefore))

        @section('custom_foot')

            @parent

            {{ HTML::style('cpassets/plugins/bootstrap-datetimepicker/css/datetimepicker.css') }}

            {{ HTML::script('cpassets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}

            <script>

                jQuery(document).ready(function($) {

                    $(".form_datetime").datetimepicker({

                        // isRTL: App.isRTL(),

                        format: "dd-mm-yyyy hh:ii",

                        pickerPosition:"bottom-right" ,

                        language: "en"

                    });

                });

            </script>

        @stop

        @else

            <?php $datetimeLoadedBefore = true; ?>

        @endif



        @elseif($field['type'] == 'time')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                <div class="input-append">

                    {{ Form::text ($field['name'] , $value , array('class' => 'form-control form_time', 'readonly' => '')) }}

                    <span class="add-on"><i class="icon-time fa fa-clock-o "></i></span>

                </div>

            </div>



            @if(!isset($timeLoadedBefore))

        @section('custom_foot')

            @parent

            {{ HTML::style('cpassets/plugins/bootstrap-timepicker/compiled/timepicker.css') }}

            {{ HTML::script('cpassets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') }}

            <script>

                jQuery(document).ready(function($) {

                    $(".form_time").timepicker({

                        // showSeconds: true,

                        showMeridian: false

                    });

                });

            </script>

        @stop

        @else

            <?php $timeLoadedBefore = true; ?>

        @endif



        @elseif($field['type'] == 'datetimesql')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                <div class="input-append">

                    {{ Form::text ($field['name'] , $value , array('class' => 'form-control form_datetime', 'readonly' => '')) }}

                    <span class="add-on"><i class="icon-remove fa fa-times"></i></span>

                    <span class="add-on"><i class="icon-calendar fa fa-calendar "></i></span>

                </div>

            </div>



            @if(!isset($datetimesqlLoadedBefore))

        @section('custom_foot')

            @parent

            {{ HTML::style('cpassets/plugins/bootstrap-datetimepicker/css/datetimepicker.css') }}

            {{ HTML::script('cpassets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}

            <script>

                jQuery(document).ready(function($) {

                    $(".form_datetime").datetimepicker({

                        // isRTL: App.isRTL(),

                        format: "yyyy-mm-dd hh:ii:ss",

                        pickerPosition:"bottom-right" ,

                        language: "en",

                        todayBtn: true,

                        todayHighlight: true

                    });

                });

            </script>

        @stop

        @else

            <?php $datetimesqlLoadedBefore = true; ?>

        @endif



        @elseif($field['type'] == 'date')



            @if(!isset($dateLoadedBefore))



        @section('custom_foot')

            @parent

            {{ HTML::style('cpassets/plugins/bootstrap-datepicker/css/datepicker.css') }}

            {{ HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}

            <script>

                jQuery(document).ready(function($) {

                    $(".form_date.date").datepicker({

                        format: "yyyy-mm-dd",

                        todayBtn: "linked",

                        orientation: "auto left",

                        autoclose: true,

                        todayHighlight: true

                    });

                });

            </script>

        @stop

        @else

            <?php $dateLoadedBefore = true; ?>

        @endif



        {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

        <div class="controls col-sm-8">

            <div class="input-append date form_date">

                {{ Form::text ($field['name'] , $value , array('class' => 'form-control form_date', 'data-date' => $value, 'data-date-format' => 'yyyy-mm-dd' ,'data-date-viewmode' => 'years')) }}

                <span class="add-on"><i class="icon-calendar fa fa-calendar"></i></span>

            </div>

        </div>



        @elseif($field['type'] == 'email')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                {{ Form::email($field['name'] , $value , array('class' => 'form-control')) }}

            </div>





        @elseif($field['type'] == 'number')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                {{ Form::input('number', $field['name'] , $value , array('class' => 'form-control')) }}

            </div>



        @elseif($field['type'] == 'password')



            @if(!isset($passwordLoadedBefore))

        @section('custom_foot')

            @parent

            {{ HTML::script('cpassets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js') }}

        @stop

        @else

            <?php $passwordLoadedBefore = true; ?>

        @endif



        {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

        <div class="controls col-sm-8">

            {{ Form::password($field['name'], array('class' => 'form-control')) }}

        </div>



        @elseif($field['type'] == 'hidden')



            {{ Form::hidden($field['name'] , $value) }}



        @elseif($field['type'] == 'switcher')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">



                <div class="switch" data-on="success" data-off="danger">

                    {{ Form::checkbox($field['name'] , 1, ($value) ? true : null , array('class' => 'toggle')) }}

                </div>

            </div>



        @elseif($field['type'] == 'radio')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                {{ Form::radio($field['name'], $value ) }}

            </div>



        @elseif($field['type'] == 'radios')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                @foreach ($field['data'] as $k => $v)

                    {{ Form::radio($field['name'],$k) }} {{ $v }}

                @endforeach

            </div>



        @elseif($field['type'] == 'select')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                @if (isset($field['chained']))

                    <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="chosen">
                      
                        @foreach ($field['data'] as $row)

                            <option value="{{isset($field['select_key'])?$row->{$field['select_key']} : $row->id }}" class="{{ $row->{$field['chained']} }}">

                                {{ dd($row->{$field['select_value']}) }}

                            </option>

                        @endforeach

                    </select>

                    @if(!isset($chainedLoadedBefore))

                @section('custom_foot')

                    @parent

                    {{ HTML::script('cpassets/js/jquery.chained.min.js') }}

                    <script>

                        $("#{{ $field['name'] }}").chained("#{{ $field['chained'] }}");

                        $('#{{ $field['name'] }}, #{{ $field['chained'] }}').on('change', function(){

                            $('#{{ $field['name'] }}, #{{ $field['chained'] }}').trigger('liszt:updated');

                        });

                    </script>

                @stop

                @else



                @endif

                @else

                    @if (isset($field['noChosen']))
                        @if(array_key_exists('valOptions',$field))

                            <select name="{{$field['name']}}" id="{{$field['name']}}" class="nochosen">

                                @if(isset($field['data']))
                                    @foreach($field['data'] as $itemS)
                                        <option @if($itemS[$field['valOptions']]==$value)  selected @endif value="{{$itemS[$field['valOptions']] }}">{{$itemS[$field['keyOptionsSelect']]}}</option>
                                    @endforeach

                                @endif

                            </select>

                        @else
                            {!! Form::select($field['name'], @$field['data'] , $value, array('class' => 'nochosen')) !!}


                        @endif


                    @else

                        @if($field['valOptions']=='otherType')


                            {!!   Form::select($field['name'], @$field['data'] , $value, array('class' => 'chosen')) !!}

                        @elseif($field['valOptions']=='keyVal')
                            <select name="{{$field['name']}}" id="{{$field['name']}}" class="chosen">


                                @if(isset($field['data']))
                                    @foreach($field['data'] as $itemKey=>$itemVal)
                                        <option @if($itemKey==$value)  selected @endif value="{!! $itemKey !!}">{!!  $itemVal !!}</option>
                                    @endforeach
                                @endif

                            </select>

                        @else
                            <select name="{{$field['name']}}" id="{{$field['name']}}" class="chosen">

                                @if(isset($field['data']))
                                    @foreach($field['data'] as $itemS)
                                        @if(is_array($itemS))
                                            <option @if($itemS[$field['valOptions']]==$value)  selected @endif value="{{$itemS[$field['valOptions']] }}">{{$itemS[$field['keyOptionsSelect']]}}</option>
                                        @else
                                            <option @if($itemS->{$field['valOptions']}==$value)  selected @endif value="{{$itemS->{$field['valOptions']} }}">{{$itemS->{$field['keyOptionsSelect']} }}</option>

                                        @endif
                                    @endforeach

                                @endif

                            </select>

                        @endif





                    @endif

                @endif

            </div>



        @elseif($field['type'] == 'multiSelect')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                {{ Form::select($field['name'].'[]', @$field['data'] , $value, array('class' => 'multiple-select', 'multiple' => true)) }}

            </div>

        @elseif($field['type'] == 'rating')
            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}
            <div class="controls col-sm-8">
                <select name="{{$field['name']}}" class="example_rating">
                    @foreach($field['data'] as $itemd)
                        <option @if($value==$itemd) selected @endif  value="{{$itemd}}">{{$itemd}}</option>
                    @endforeach
                </select>
            </div>


        @elseif($field['type'] == 'selectRange')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                {{ Form::selectRange($field['name'], $field['start'], $field['end']) }}

            </div>



        @elseif($field['type'] == 'file')

            @if(!isset($fileLoadedBefore))



        @section('custom_foot')

            @parent



            {{ HTML::style('/cpassets/uploadifive/uploadifive.css') }}

            {{ HTML::script('/cpassets/uploadifive/jquery.uploadifive.min.js') }}



            <script>

                $('#{{ $field['name'] }}').uploadifive({

                    'auto' : true,

                    @if ($field['multi'])

                    'multi' : true,

                    @else

                    'multi' : false,

                    @endif

                    'formData' : {

                        'timestamp' : '{{ time() }}',

                        'token' : '{{ md5('SecUre!tN0w' . time()) }}',

                        'folder' : 'media/{{ $field['folder'] }}/',

                        'fileExt' : '{{ isset($field['ext']) ? $field['ext'] : 'jpeg,jpg,bmp,gif,png' }}',

                    },

                    'queueID' : 'queue{{ $field['name'] }}',

                    'uploadScript' : '{{ url('/upload_files') }}',

                    'onUploadComplete' : function(file, data) {

                        var rname=data;

                        var fullpath= '{{ url("resize?w=30&h=30&src=media/".$field['folder']) }}'+"/"+rname;

                        var link= '{{ url("resize?w=500&r=1&src=media/".$field['folder']) }}'+"/"+rname;



                        // Photo Handling Vars

                                @if ($field['photo'])

                        var linkhrefstart = '<a class="fancybox" href="' + link + '">';

                        var linkhrefend = '</a>';

                                @else

                        var linkhrefstart = '';

                        var linkhrefend = '';

                        @endif



                        // Multi Field Name Handling

                                @if ($field['multi'])

                        var fieldname = '{{ $field['name'] }}[]';

                                @else

                        var fieldname = '{{ $field['name'] }}';

                        @endif



                        // Paragraph Setup

                        var p = '';

                        var p=p+'<div class="uploadedImg">';

                        var p=p+'<input type="checkbox" checked="checked" value="'+rname+'" name="' + fieldname + '" >';

                        var p=p+linkhrefstart;

                        var p=p+'<img src="'+fullpath+'">';

                        var p=p+linkhrefend;

                        var p=p+'<br /><small>'+rname+"</small>";

                        var p=p+'</div>';



                        @if ($field['multi'])

                        $('#{{ $field['name'] }}-thumbnails').append(p);

                        @else

                        $('#{{ $field['name'] }}-thumbnails').html(p);

                        @endif



                        $('.mesg').fadeOut('slow');	}

                });

                @if ($field['photo'])

                $('.fancybox').attr('rel', 'media-gallery').fancybox();

                @endif



            </script>

        @stop

        @else

            <?php $fileLoadedBefore = true; ?>

        @endif



        {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

        <div class="controls col-sm-8">

            @if ($field['multi'])

                {{ Form::file($field['name'] , array('multiple' => 'true')) }}

            @else

                {{ Form::file($field['name']) }}

            @endif

            <div id="{{ $field['name'] }}-thumbnails">

                @if(!empty($value))

                    @if ($field['multi'])

                        @foreach ($value as $v)

                            <div class="uploadedImg">

                                <input type="checkbox" checked="checked" value="{{ $v }}" name="{{ $field['name'] }}[]" >

                                @if ($field['photo'])

                                    <a href="{{ url('resize?w=500&r=1&src=media/'.$field['folder'].'/'.$v) }}" class="fancybox">

                                        @endif

                                        <img src="{{ url('resize?w=30&h=30&src=media/'.$field['folder'].'/'.$v) }}">

                                        @if ($field['photo'])

                                    </a>

                                @endif

                                <br />

                                <small>{{ $v }}</small>

                            </div>

                        @endforeach

                    @else

                        <div class="uploadedImg">

                            <input type="checkbox" checked="checked" value="{{ $value }}" name="{{ $field['name'] }}" >

                            @if ($field['photo'])

                                <a href="{{ url('resize?w=500&r=1&src=media/'.$field['folder'].'/'.$value) }}" class="fancybox">

                                    @endif

                                    <img src="{{ url('resize?w=30&h=30&src=media/'.$field['folder'].'/'.$value) }}">

                                    @if ($field['photo'])

                                </a>

                            @endif

                            <br />

                            <small>{{ $value }}</small>

                        </div>

                    @endif

                @endif

            </div>

            <div class="clearfix"></div>

            <div id="queue{{ $field['name'] }}" class="help-inline"></div>

            <div class="mesg">

                @lang('main.Please wait untill upload completing otherwise the photo will not appear')

            </div>



        </div>



        @elseif($field['type'] == 'timestampDisplay')



            {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

            <div class="controls col-sm-8">

                <div class="form-control">{{ $value }}</div>

            </div>



        @elseif($field['type'] == 'wysiwyg')



            @if(!isset($tinymceLoadedBefore))



        @section('custom_foot')

            @parent

            {{ HTML::script('/cpassets/plugins/tinymce/js/tinymce/tinymce.min.js') }}

            {{ HTML::script('/cpassets/plugins/tinymce/js/tinymce/jquery.tinymce.min.js') }}

        @stop

        @else

            <?php $tinymceLoadedBefore = true; ?>

        @endif



        @section('custom_foot')

            @parent

            <script>

                jQuery(document).ready(function($) {

                    $("#{{$field['name']}}").tinymce({

                        selector: "textarea",
                        plugins: [

                            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",

                            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",

                            "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"

                        ],
                        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
                        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
                        menubar: false,
                        toolbar_items_size: 'small',
                        style_formats: [

                            {title: 'Bold text', inline: 'b'},

                            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},

                            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                            {title: 'Example 1', inline: 'span', classes: 'example1'},
                            {title: 'Example 2', inline: 'span', classes: 'example2'},
                            {title: 'Table styles'},
                            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}

                        ],



                        templates: [

                            {title: 'Slideshow Content', content: '<p>Insert Info Here</p><a href="#" class="but">Insert Url Here</a>'},

                            {title: 'Test', content: 'Test'}

                        ],
                        forced_root_block : 'p'

                    });



                });

            </script>

        @stop



        {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

        <div class="controls col-sm-8">

            {{ Form::textarea($field['name'] , $value , array('class' => 'form-control wysiwyg editor', 'id' => $field['name'])) }}

        </div>



        @elseif($field['type'] == 'wysiwyg2')



            @if(!isset($ckeditorLoadedBefore))



        @section('custom_foot')

            @parent

            <script>

                CKEDITOR_BASEPATH  = "{{ url('cpassets/plugins/ckeditor') }}/";

            </script>

            {{ HTML::script('/cpassets/plugins/ckeditor/ckeditor.js') }}

            {{ HTML::script('/cpassets/plugins/ckeditor/adapters/jquery.js') }}

            {{ HTML::script('/cpassets/plugins/ckeditor/own.js') }}



        @stop

        @else

            <?php $ckeditorLoadedBefore = true; ?>

        @endif



        @section('custom_foot')

            @parent

            <script>

                jQuery(document).ready(function($) {

                    $("#{{$field['name']}}").ckeditor({

                        toolbarGroups: CKEDITOR_Style{{ (isset($field['size'])) ? $field['size'] : 1 }}

                    });



                });

            </script>

        @stop





        {{ Form::label($field['name'], $field['title'] , array('class' => 'control-label col-sm-4')) }}

        <div class="controls col-sm-8">

            {{ Form::textarea($field['name'] , $value , array('class' => 'form-control wysiwyg editor', 'id' => $field['name'])) }}

        </div>





        @endif



    </div>



@endforeach



@yield('forms2')




@if(isset($item->$_pk))
    {{Form::hidden($_pk, $item->$_pk)}}
@endif



@if(Request::segment(3) !== 'view')

    <div class="form-actions text-center" style="justify-content: center;align-content: center;">

        {{ Form::button('<i class="fa fa-reply"></i> '.'Send', array('type' => 'submit', 'name' => 'save_return', 'value' => '1' , 'class' => 'btn col-md-4 blue')) }}

    </div>

@else

    <div class="form-actions">

        <a href="{{ url($menuUrl) }}" class="btn blue"><i class="fa fa-chevron-left"></i> {{ trans('main.Back') }}</a>

    </div>

@endif

{{ Form::close() }}



@stop
