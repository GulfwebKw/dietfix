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

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">

            <tr>

                <th class="exclude-search">ID</th>
                <th>{{trans('main.Operation')}}</th>
                <th>{{trans('main.Value')}}</th>
                <th>{{trans('main.RefNumber')}}</th>
                <th>{{trans('main.Date')}}</th>

            </tr>

            </thead>
            <tbody>

            @php
                $i=0;
            @endphp
            @if($points->count()<=0)
                <tr>
                    <h5>Not Found Point Record</h5>
                </tr>
            @endif
            @php
              $sum=0;
              $dsum=0;
            @endphp


            @foreach($points as $payment)

                @if($payment->operation=="decrement")
                        @php
                            $dsum+=$payment->value;
                        @endphp
                    @else


                    @php
                        $sum+=$payment->value;
                    @endphp
                 @endif

                <tr>
                    <td>{{++$i}}</td>
                    <td>{{$payment->operation}}</td>
                    <td>{{$payment->value}}</td>


                    @if($payment->referral_number!="" && $payment->referral_number!=null)
                        <td>{{$payment->referral_number}}</td>
                    @else
                        <td>----</td>
                     @endif

                    <td>{{$payment->created_at}}</td>

                </tr>
            @endforeach
            <tr>Sum Balance : <strong>{{$sum-$dsum}}</strong></tr>

            </tbody>



        </table>
        {!! $points->links() !!}
    </div>

@endsection

@section('custom_foot')

    {{ HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js') }}

    {{ HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js') }}



@endsection
