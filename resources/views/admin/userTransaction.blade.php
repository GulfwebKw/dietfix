@extends('admin.layouts.main')



@section('custom_head_include')



    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js') }}
    {{ HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js') }}
    {{ HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css') }}
    {{ HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css') }}
    {{ HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') }}

    {{ HTML::style('cpassets/css/pqgrid.min.css') }}
    {{ HTML::script('cpassets/js/pqgrid.min.js') }}
    <!--{{ HTML::style('cpassets/css/jquery.dataTables.min.css') }}
    {{ HTML::script('cpassets/js/datatables.min.js') }}-->
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css') }}
    {{ HTML::script('//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css') }}
    
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

    <div class="flip-scroll">

        <table  class="table table-striped table-bordered" style="width:100%" id="grid">

            <thead class="flip-content">

            <tr>

                <th class="exclude-search">ID</th>
                <th>{{trans('main.Payment Type')}}</th>
                <th>{{ trans('main.pay_way_type')}}</th>
                <th>{{ trans('main.total_credit')}}</th>
                <th>{{ trans('main.Total')}}</th>
                <th>{{ trans('main.PaidCurrencyValue')}}</th>
                <th>{{trans('main.DueValue')}}</th>
                <th>{{ trans('main.InvoiceDisplayValue')}}</th>
                <th>{{ trans('main.PaidCurrency')}}</th>
                <th>{{ trans('main.Currency')}}</th>
                <th>{{ trans('main.Paid?')}}</th>
                <th>{{trans('main.RefId')}}</th>
                <th>{{trans('main.Created Date')}}</th>

            </tr>

            </thead>
            <tbody>

            @php
                $i=0;
            @endphp
            @if($payments->count()<=0)
                <tr>
                    <h5>Not Found Payment Record</h5>
                </tr>
            @endif
            @foreach($payments as $payment)
                 <tr>
               <td>{{++$i}}</td>
               <td>{{$payment->type}}</td>
               <td>{{$payment->pay_way_type}}</td>
               <td>{{$payment->total_credit}}</td>
               <td>{{$payment->total}}</td>
               <td>{{$payment->PaidCurrencyValue}}</td>
               <td>{{$payment->DueValue}}</td>
               <td>{{$payment->InvoiceDisplayValue}}</td>
               <td>{{$payment->PaidCurrency}}</td>
               <td>{{$payment->Currency}}</td>
               <td>{{$payment->paid==1?"Yes":"No"}}</td>
               <td>{{$payment->ref}}</td>
               <td>{{$payment->created_at}}</td>
           </tr>
            @endforeach

            </tbody>


        </table>
        {!! $payments->links() !!}
    </div>

@endsection

@section('custom_foot')

    {{ HTML::script('https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js') }}

    {{ HTML::script('https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js') }}

  <script>

           $(document).ready(function($) {
           $('#grid').DataTable("displayLength": "50");
		   });
 </script>

@endsection
