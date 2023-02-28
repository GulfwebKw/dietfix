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


    </div>
    <div class="clearfix"></div>
    <br>

    @if($weeks->count()>=1)
       @foreach($weeks as $colloect)
           <div class="row">
               @foreach($colloect as $item)
                   <div class="col-md-4">
                       <div class="card">
                           <div class="card-header">
                               {{$item->titleEn}}
                           </div>
                           <div class="card-body">
                               <ul class="list-group list-group-flush">
                                   <li class="list-group-item">Water: {{$item->water}}</li>
                                   <li class="list-group-item">Commitment: {{$item->commitment}}</li>
                                   <li class="list-group-item">exercise: {{$item->exercise}}</li>
                                   <li class="list-group-item">average: {{$item->average}}</li>
                                   <li class="list-group-item">Date: {{$item->created_at}}</li>
                               </ul>

                           </div>
                       </div>

                   </div>
               @endforeach
           </div>
           <hr/>
           <br/>
       @endforeach
    @else
    <div class="row"><div class="col-lg-12"><div class="alert alert-info">Progress report is not available for this user.</div></div></div>   
    @endif

@endsection

@section('custom_foot')
    {{ HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js') }}
    {{ HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js') }}

@endsection
