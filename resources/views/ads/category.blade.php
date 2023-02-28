@extends('layouts.main')
@section('custom_foot')
    <script>
        $('a.add-to-fav').click(function(e) {
            e.preventDefault();
            var id = $(this).attr('rel');
            var otherA = '#remove-'+id;
            var a = $(this);
            var i = $(this).find('i');
            var url = '{{ url('api/ads/add-to-fav') }}/'+id+'?user={{ Auth::user()->id }}';
            $.getJSON(url)
                .done(function() {
                    $(a).removeClass('di').addClass('dn');
                    $(otherA).removeClass('dn').addClass('di');
                });
        });
        $('a.remove-from-fav').click(function(e) {
            e.preventDefault();
            var id = $(this).attr('rel');
            var otherA = '#add-'+id;
            var a = $(this);
            var i = $(this).find('i');
            var url = '{{ url('api/ads/remove-from-fav') }}/'+id+'?user={{ Auth::user()->id }}';
            $.getJSON(url)
                .done(function() {
                    $(a).removeClass('di').addClass('dn');
                    $(otherA).removeClass('dn').addClass('di');
                });
        });
    </script>
@stop
@section('contents')
  <?php /* @include('partials.slideshow') */ ?>

    <div class="block-content boxes">
        <div class="row">
            <div class="col-sm-12">

                    @if (!$data->isEmpty())
                    @foreach (break_array($data, 4) as $row)
                    <div class="row">
                    @foreach ($row as $single)
                    <div class="col-sm-4 col-md-3">
                        <div class="box background-white">
                            <div class="box-picture">
                                <a href="{{ url('ads/view/'.$single->id) }}">
                                    @if ($single->photos->isEmpty())
                                    <img src="{{ url('resize?w=320&amp;h=320&amp;r=1&amp;c=1&amp;src=media/default.png')}}" alt="{{ $single->title }}">
                                    @else
                                    <img src="{{ url('resize?w=320&amp;h=320&amp;r=1&amp;c=1&amp;src=media/ads/'.$single->photos->first()->photo)}}" alt="{{ $single->title }}">
                                    @endif
                                    <span></span>
                                </a>
                            </div><!-- /.box-picture -->

                            <div class="box-body">
                                <h2 class="box-title">
                                    @if (Auth::user())
                                    <div class="favourite pull-right flip">
                                        
                                        <a href="#" id="remove-{{ $single->id }}" class="remove-from-fav @if (in_array($single->id, $faved)) di @else dn @endif" rel="{{ $single->id }}"><i class="fa fa-heart"></i></a>
                                        <a href="#" id="add-{{ $single->id }}" class="add-to-fav @if (in_array($single->id, $faved)) dn @else di @endif" rel="{{ $single->id }}"><i class="fa fa-heart-o"></i></a>
                                        
                                    </div>
                                    @endif
                                    <a href="{{ url('ads/view/'.$single->id) }}">
                                        {{ $single->title }}
                                    </a>
                                </h2><!-- /.box-title -->
                                <div class="box-content">
                                    <dl class="dl-horizontal">
                                        <dt class="odd">{{ trans('main.Price') }}</dt>
                                        <dd class="odd">{{ $single->price }}</dd>
                                        <dt>{{ trans('main.Phone') }}</dt>
                                        <dd>{{ $single->phone }}</dd>
                                        <dt class="odd">{{ trans('main.Views') }}</dt>
                                        <dd class="odd">{{ $single->views }}</dd>
                                        <dt>{{ trans('main.Published') }}</dt>
                                        <dd>{{ date('Y-m-d',strtotime($single->published_at)) }}</dd>
                                        @if (Auth::user() && $single->user_id == Auth::user()->id)
                                        <dt class="odd">{{ trans('main.Edit') }}</dt>
                                        <dd class="odd"><a href="{{ url('ads/edit/'.$single->id) }}"><i class="fa fa-pencil"></i></a></dd>
                                        @endif                             
                                    </dl>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>

                    @endforeach
                    </div>
                    @endforeach

                    @else 
                    <div class="row">
                    <div class="contents center">{{ trans('main.No Data Avilable') }}</div>
                    </div>
                    @endif
                    
                                              
            </div>
        </div><!-- /.row -->
    </div><!-- /.boxes -->  

@stop