@extends('layouts.main')

@section('contents')
  <h2>اضافة جديد</h2>
  {{ Form::open(array('url' => url('save'), 'class' => 'form-g'))}}
  <div class="form-group">
    <label for="title">التصنيف</label>
    <select name="type_id" id="type_id" class="form-control">
      <option value="0">{{ trans('main.Choose') }}</option>
      <option value="lectures">محاضرات</option>
      <option value="lessons">دروس</option>
    </select>
  </div>
  <div class="form-group">
    <label for="title">العنوان</label>
    {{ Form::text('title',null, array('class' => 'form-control')) }}
  </div>
  <div class="form-group">
    <label for="content">الوصف</label>
    {{ Form::textarea('content',null, array('class' => 'form-control nobg')) }}
  </div>
  <div class="form-group">
    <label for="name">أسمك</label>
    {{ Form::text('name',null, array('class' => 'form-control')) }}
  </div>
  <div class="form-group">
    <label for="address">المكان</label>
    {{ Form::text('address',null, array('class' => 'form-control')) }}
  </div>
  <div class="form-group col-md-6">
    <label for="province_id2">المحافظة</label>
    <select name="province_id" id="province_id2" class="form-control">
      <option value="0">المحافظة</option>
      @foreach ($areas as $province)
      <option value="{{ $province->id }}">{{ $province->title }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group col-md-6">
    <label for="city_id2">المنطقة</label>
    <select name="city_id" id="city_id2" class="form-control">
      <option value="" class="0">المنطقة</option>
      @foreach ($areas as $province)
        @foreach ($province->cities as $city)
        <option value="{{ $city->id }}" class="{{ $province->id }}">{{ $city->title }}</option>
        @endforeach
      @endforeach
    </select>
  </div>
  <div class="form-group">
    <label for="phone">رقم الهاتف</label>
    {{ Form::text('phone',null, array('class' => 'form-control')) }}
  </div>
  <div class="form-group">
    <label for="dated">المواعيد</label>
    {{ Form::text('dated',null, array('class' => 'form-control')) }}
  </div>
  <div class="form-group">
    <label for="photo">الصورة</label>
    <input name="photo" type="file" id="photo">
    <div id="photo-thumbnails">
    </div>
    <div class="clearfix"></div>
    <div id="queuephoto" class="help-inline"></div>
    <div class="mesg">
    برجاء الانتظار حتي اكتمال التحميل او لن تظهر الصوره
    </div>
  </div>
  <div class="form-group col-md-6">
    <label for="date_start">تاريخ الظهور</label>
    {{ Form::text('date_start',(Input::old('date_start')) ? Input::old('date_start') : date('Y-m-d'), array('class' => 'form-control form_date', 'data-date' => (Input::old('date_start')) ? Input::old('date_start') : date('Y-m-d'), 'data-date-format' => 'yyyy-mm-dd' ,'data-date-viewmode' => 'years')) }}
  </div>
  <div class="form-group col-md-6">
    <label for="date_end">تاريخ الانتهاء</label>
    {{ Form::text('date_end',(Input::old('date_end')) ? Input::old('date_start') : date('Y-m-d', strtotime("+5 days")), array('class' => 'form-control form_date', 'data-date' => (Input::old('date_end')) ? Input::old('date_start') : date('Y-m-d', strtotime("+5 days")), 'data-date-format' => 'yyyy-mm-dd' ,'data-date-viewmode' => 'years')) }}
  </div>
  <div class="form-group col-md-6">
    <label for="facebook">رابط Facebook</label>
    {{ Form::text('facebook',null, array('class' => 'form-control')) }}
  </div>

  <div class="form-group col-md-6">
    <label for="twitter">رابط Twitter</label>
    {{ Form::text('twitter',null, array('class' => 'form-control')) }}
  </div>

  <div class="form-group col-md-6">
    <label for="youtube">رابط Youtube</label>
    {{ Form::text('youtube',null, array('class' => 'form-control')) }}
  </div>

  <div class="form-group col-md-6">
    <label for="intagram">رابط Intagram</label>
    {{ Form::text('intagram',null, array('class' => 'form-control')) }}
  </div>
  <div class="form-group">
    <label for="live">رابط مشاهدة مباشرة</label>
    {{ Form::text('live',null, array('class' => 'form-control')) }}
  </div>
  <div class="form-group">
    {{ Form::button(trans('main.Submit'), array('type' => 'submit', 'class' => 'btn btn-primary btn-block')) }}
  </div>
  {{ Form::close() }}


@stop
@section('custom_foot')
  @parent
  {{ HTML::style('/cpassets/uploadifive/uploadifive.css') }}
  {{ HTML::script('/cpassets/uploadifive/jquery.uploadifive.min.js') }}
  {{ HTML::style('cpassets/plugins/bootstrap-datepicker/css/datepicker.css') }}
  {{ HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
  <script>
    jQuery(document).ready(function($) {
      $(".form_date").datepicker({
          format: "yyyy-mm-dd",
          todayBtn: "linked",
          orientation: "auto left",
          autoclose: true,
          todayHighlight: true
      });
    });
    $('#photo').uploadifive({
      'auto' : true,
            'multi' : false,
            'formData' : {
      'timestamp' : '1424833523',
      'token' : 'b460ef208ee38e0d5feecca79d53ea1b',
      'folder' : 'media/uploads/',
      'fileExt' : 'jpg,gif,png,bmp',
      },
      'queueID' : 'queuephoto',
      'uploadScript' : 'http://cloud.dev/q8khir_laravel/public_html/upload_files',
      'onUploadComplete' : function(file, data) { 
        var rname=data;
        var fullpath= 'http://cloud.dev/q8khir_laravel/public_html/resize?w=100&h=100&src=media/uploads'+"/"+rname;
        var link= 'http://cloud.dev/q8khir_laravel/public_html/resize?w=500&r=1&src=media/uploads'+"/"+rname;

        // Photo Handling Vars
        var linkhrefstart = '<a class="fancybox" href="' + link + '">';
        var linkhrefend = '</a>';
        
        // Multi Field Name Handling
        var fieldname = 'photo';
        
        // Paragraph Setup
        var p = '';
        var p=p+'<div class="uploadedImg">';
        var p=p+'<input type="checkbox" checked="checked" value="'+rname+'" name="' + fieldname + '" >';
        var p=p+'<img src="'+fullpath+'">';
        // var p=p+'<br /><small>'+rname+"</small>";
        var p=p+'</div>';

        $('#photo-thumbnails').html(p);
        
        $('.mesg').fadeOut('slow'); }
      });

  </script>
@stop