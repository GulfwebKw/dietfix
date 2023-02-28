@extends('admin.layouts.main')



@section('custom_head_include')



    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js') }}
    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js') }}
    {{ HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css') }}
    {{ HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css') }}
    {{ HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') }}

    {{ HTML::style('cpassets/css/pqgrid.min.css') }}
    {{ HTML::script('cpassets/js/pqgrid.min.js') }}
    {{ HTML::style('cpassets/css/jquery.dataTables.min.css') }}
    {{ HTML::script('cpassets/js/datatables.min.js') }}
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

    <div class="table-toolbar">

   @if(session()->has('message'))
    <div class="alert alert-{{session()->get('status')}}">
        {{session()->get('message')}}
    </div>
    @endif
    
    </div>

    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">
            <tr>
                <th class="exclude-search">ID</th>
                <th>{{trans('main.User Name')}}</th>
                <th>{{trans('main.Mobile')}}</th>
                <th>{{trans('main.Active')}}</th>
            </tr>

            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->mobile_number}}</td>
                    <td>
                        <a href="/admin/sendusernotification?id={{$user->id}}" class="nwrap btn btn-xs green btn-block "><i class="fa fa-envelope"></i> Send Message</button>
                        
         <!--modal-->
         <div class="modal fade" id="pushmodal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Push Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="row">
       <div class="col-lg-6"><label>Title(En)</label><input type="text" class="form-control" name="pushtitleEn{{$user->id}}" id="pushtitleEn{{$user->id}}"></div>
       <div class="col-lg-6"><label>Title(Ar)</label><input type="text" class="form-control" name="pushtitleAr{{$user->id}}" id="pushtitleAr{{$user->id}}"></div>
       </div>
       <div class="row mt-2">
       <div class="col-lg-6"><label>Message(En)</label><textarea class="form-control" name="pushmessageEn{{$user->id}}" id="pushmessageEn{{$user->id}}"></textarea></div>
       <div class="col-lg-6"><label>Message(Ar)</label><textarea class="form-control" name="pushmessageAr{{$user->id}}" id="pushmessageAr{{$user->id}}"></textarea></div>
       </div>
       <p><span id="responsetxt{{$user->id}}"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="{{$user->id}}" class="btn btn-primary sendNowPush">Send Now</button>
      </div>
    </div>
  </div>
</div>
         <!--end modal -->
         
          </td>

                </tr>

            @endforeach

            </tbody>



        </table>

    </div>

@endsection

@section('custom_foot')

    {{ HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js') }}

    {{ HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js') }}
    <script>
        jQuery(document).ready(function($) {
            var table = $('#grid').DataTable({
                "processing": true,
                "displayLength": {{ $noOfItems }},
                "paginationType": "bootstrap_full_numbers",
                "pagingType": "full_numbers",
                "language": {
                    "lengthMenu": "_MENU_ @lang('main.Records') ",
                    "search": '@lang('main.Search') : ',
                    "info": "@lang('main.Showing _START_ to _END_ of _TOTAL_ entries')",
                    "processing": '<img src="http://dietfix.com/images/progress.gif" width="20">&nbsp;@lang('main.Please wait')...',
                    "paginate": {
                        "last": '<i class="fa fa-arrow-circle-left"></i>',
                        "first": '<i class="fa fa-arrow-circle-right"></i>',
                        "previous": '<i class="fa fa-angle-left"></i>',
                        "next": '<i class="fa fa-angle-right"></i>'
                    }

                },

                "columnDefs": [{
                    "defaultContent": "",
                    "targets": "_all",
                }
                ],

                "initComplete": function (oSettings, json) {

                }

            });
            $("#grid .search-field").on('keyup change', function () {

                var colIdx = $(this).parent().parent().index();

                table.column(colIdx).search(this.value).draw();

            });
          
          $(document).on('click','.sendNowPush',function(){
          var id = $(this).attr('id');
          var message_en = $('#pushmessageEn'+id).val();
          var message_ar = $('#pushmessageAr'+id).val();
          var title_en = $('#pushtitleEn'+id).val();
          var title_ar = $('#pushtitleAr'+id).val();
          if(message_en==''){
          $("#responsetxt"+id).html('<div class="alert alert-danger">Please write some text message(En)</div>');
          }
          if(message_ar==''){
          $("#responsetxt"+id).html('<div class="alert alert-danger">Please write some text message(Ar)</div>');
          }
          $.ajax({
				 type: "GET",
				 url: "/admin/notificationuserpost",
				 data: "id="+id+"&message_en="+message_en+"&message_ar="+message_ar+"&title_en="+title_en+"&title_ar="+title_ar,
				 dataType: "json",
				 cache: false,
				 processData:false,
				 success: function(msg){
				 if(msg.status==200){
                 $("#responsetxt"+id).html('<div class="alert alert-success">'+msg.message+'</div>');
                 }else{
                 $("#responsetxt"+id).html('<div class="alert alert-danger">'+msg.message+'</div>');
                 }
				 },
				 error: function(xhr, status, error){
				 }
			 });
          });

        });
    </script>


@endsection
