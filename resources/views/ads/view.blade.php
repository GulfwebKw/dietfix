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



            <div class="block-content row">
                <div class="col-sm-6">
                    @if ($ad->photos->isEmpty())
                        <div id="gallery-wrapper">
                            <img src="{{ url('resize?w=600&amp;h=440&amp;r=1&amp;c=1&amp;src=media/default.png')}}" alt="">
                        </div>
                    @else
                    <div id="gallery-wrapper">
                        <div class="gallery">
                            @foreach ($ad->photos as $photo)
                            <div class="slide active">
                                <div class="picture-wrapper">
                                    <a href="{{ url('resize?w=1024&amp;h=1024&amp;r=1&amp;src=media/ads/'.$photo->photo)}}" rel="group" class="fancybox">
                                        <img src="{{ url('resize?w=600&amp;h=440&amp;r=1&amp;c=1&amp;src=media/ads/'.$photo->photo)}}" alt="{{ $ad->title }}">
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div><!-- /.gallery -->

                        <div id="gallery-pager" class="background-white">
                            <div class="prev">
                                <i class="fa fa-angle-left"></i>
                            </div>

                            <div class="pager">
                            </div>

                            <div class="next">
                                <i class="fa fa-angle-right"></i>
                            </div>
                        </div><!-- /#gallery-pager -->


                        <div class="gallery-thumbnails">
                            <?php $i = 0; ?>
                            @foreach ($ad->photos as $photo)
                            <div class="thumbnail-{{ $i }}">
                                <img src="{{ url('resize?w=80&amp;h=60&amp;r=1&amp;c=1&amp;src=media/ads/'.$photo->photo)}}" alt="{{ $ad->title }}">
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div><!-- /.gallery-thumbnails -->

                     </div> <!-- /#gallery-wrapper -->

                    @endif
                </div>

                <div class="col-sm-6">
                    <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <a class="btn btn-secondary btn-block" href="tel:{{ $ad->phone }}"><i class="fa fa-envelope"></i> {{ trans('main.Contact seller') }}</a>
                                <br>
                                <div class="actions">
                                    
                                    <p>
                                        {{ $ad->details }}
                                    </p>
                                </div><!-- /.actions-->
                            </div><!-- /.col-md-5 -->

                            <div class="col-sm-7 col-md-7">
                                <table class="table table-attributes">
                                    <tbody>
                                    <tr>
                                        <td class="property">{{ trans('main.Favoraite') }}</td>
                                        <td class="value">
                                            @if (Auth::user())
                                            <div class="favourite">
                                                
                                                <a href="#" id="remove-{{ $ad->id }}" class="remove-from-fav @if ($faved) di @else dn @endif" rel="{{ $ad->id }}"><i class="fa fa-heart"></i></a>
                                                <a href="#" id="add-{{ $ad->id }}" class="add-to-fav @if ($faved) dn @else di @endif" rel="{{ $ad->id }}"><i class="fa fa-heart-o"></i></a>
                                                
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Price') }}</td>
                                        <td class="value">{{ $ad->price }}</td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Phone') }}</td>
                                        <td class="value">{{ $ad->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Whatsapp') }}</td>
                                        <td class="value"><a href="whatsapp://send" data-text="{{ $ad->title }}" data-href="{{ url('ads/view/'.$ad->title)}}" class="wa_btn wa_btn_s">{{ $ad->whatsapp }}</a></td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Section') }}</td>
                                        <td class="value"><a href="{{ url('ads/section/'.$ad->section->id) }}">{{ $ad->section->title }}</a></td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Category') }}</td>
                                        <td class="value"><a href="{{ url('ads/category/'.$ad->category->id) }}">{{ $ad->category->title }}</a></td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Province') }}</td>
                                        <td class="value">{{ $ad->province->title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Views') }}</td>
                                        <td class="value">{{ $ad->views }}</td>
                                    </tr>

                                    <tr>
                                        <td class="property">{{ trans('main.Published') }}</td>
                                        <td class="value">{{ $ad->published_at }}</td>
                                    </tr>

                                    </tbody>
                                </table><!-- /.table -->
                            </div><!-- /.col-md-7 -->
                        </div><!-- /.row -->

                </div><!-- /.row -->
            </div><!-- /.row -->



@stop