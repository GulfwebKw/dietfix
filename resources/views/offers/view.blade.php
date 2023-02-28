@extends('layouts.main')

@section('contents')
  <?php /* @include('partials.slideshow') */ ?>



            <div class="block-content row">
                <div class="col-sm-6">
                    @if ($offer->photos->isEmpty())
                        <div id="gallery-wrapper">
                            <img src="{{ url('resize?w=600&amp;h=440&amp;r=1&amp;c=1&amp;src=media/default.png')}}" alt="">
                        </div>
                    @else
                    <div id="gallery-wrapper">
                        <div class="gallery">
                            @foreach ($offer->photos as $photo)
                            <div class="slide active">
                                <div class="picture-wrapper">
                                    <a href="{{ url('resize?w=1024&amp;h=1024&amp;r=1&amp;src=media/offers/'.$photo->photo)}}" rel="group" class="fancybox">
                                        <img src="{{ url('resize?w=600&amp;h=440&amp;r=1&amp;c=1&amp;src=media/offers/'.$photo->photo)}}" alt="{{ $offer->title }}">
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
                            @foreach ($offer->photos as $photo)
                            <div class="thumbnail-{{ $i }}">
                                <img src="{{ url('resize?w=80&amp;h=60&amp;r=1&amp;c=1&amp;src=media/offers/'.$photo->photo)}}" alt="{{ $offer->title }}">
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div><!-- /.gallery-thumbnails -->

                     </div> <!-- /#gallery-wrapper -->

                    @endif
                </div>

                <div class="col-sm-6">
                    <div class="row">
                            <div class="col-sm-12">
                                <a class="btn btn-secondary btn-block" href="{{ $offer->url }}" target="_blank"><i class="fa fa-envelope"></i> {{ trans('main.Contact us') }}</a>
                                <br>
                                <table class="table table-attributes">
                                    <tbody>
                                    <tr>
                                        <td class="value center" colspan="2">
                                            <img src="{{ url('resize?w=100&amp;h=100&amp;r=1&amp;c=1&amp;src=media/companies/'.$offer->companyLogo)}}" alt="{{ $offer->title }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Company') }}</td>
                                        <td class="value">{{ $offer->companyName }}</td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Phone') }}</td>
                                        <td class="value"><a href="tel:{{ $offer->phone }}">{{ $offer->phone }}</a></td>
                                    </tr>
                                    <tr>
                                        <td class="property">{{ trans('main.Views') }}</td>
                                        <td class="value">{{ $offer->views }}</td>
                                    </tr>

                                    <tr>
                                        <td class="property">{{ trans('main.Published') }}</td>
                                        <td class="value">{{ $offer->published_at }}</td>
                                    </tr>

                                    </tbody>
                                </table><!-- /.table -->
                            </div><!-- /.col-md-7 -->
                        </div><!-- /.row -->

                </div><!-- /.row -->
            </div><!-- /.row -->



@stop