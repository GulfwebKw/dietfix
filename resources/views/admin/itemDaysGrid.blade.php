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
                    <th>{{trans('main.Arabic Title')}}</th>
                    <th>{{trans('main.English Title')}}</th>
                    <th>{{trans('main.Category')}}</th>
                    <th>{{trans('main.Days')}}</th>
                    <th>{{trans('main.Active')}}</th>
                </tr>

            </thead>
            <tbody>

            @foreach($items as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->titleAr}}</td>
                    <td>{{$item->titleEn}}</td>
                    <td>{{$item->category->titleEn}}</td>
                    <td>
                         @foreach($item->days as $day)
                             {{$day->titleEn}} @if(!$loop->last) | @endif
                         @endforeach
                    </td>
                    <td><a href="/admin/items/edit/{{$item->id}}" data-id="{{$item->id}}" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-edit"></i> Edit</a></td>
                </tr>

            @endforeach

            </tbody>



        </table>
{{--        {!! $points->links() !!}--}}
    </div>

@endsection

@section('custom_foot')

    {{ HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js') }}

    {{ HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js') }}



@endsection
