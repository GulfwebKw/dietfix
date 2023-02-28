@extends('layouts.main')

@section('contents')
  <h2>تم الاضافة بنجاح</h2>
  <p>
  يرجي العلم بأن طلب الاضافة قيد المراجعة و سيتم تفعيله عن طريق ادارة لموقع
  <br>
  يمكنك حذف اضافتك في اي وقت من خلال الرابط المذكور بالاسفل
  <br>
  فقط قم بنسخ الرابط و ضعه في المتصفح ليتم الحذف
  <br>
  شكراً لك
  </p>
  <div class="alert alert-danger" role="alert">
    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
    <span class="sr-only"></span>
    {{ url('delete/'.$key.'/'.$item->id) }}
  </div>
@stop
