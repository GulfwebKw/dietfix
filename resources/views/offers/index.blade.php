@extends('layouts.main')

@section('contents')
  <?php /* @include('partials.slideshow') */ ?>
    
    <div class="block-content directory-carousel-wrapper fullwidth background-gray">
    <div class="block-content-inner carousel">
        
        @if (!$offers->isEmpty())
        <div class="directory-carousel bxslider">

            @foreach ($offers as $offer)
              {{-- expr --}}
            
            <div class="box background-white">
                <div class="box-picture">
                    <a href="{{ url('offers/view/'.$offer->id) }}">
                        @if ($offer->photos->isEmpty())
                        <img src="{{ url('resize?w=500&amp;h=500&amp;r=1&amp;c=1&amp;src=media/default.png')}}" alt="">
                        @else
                        <img src="{{ url('resize?w=500&amp;h=500&amp;r=1&amp;c=1&amp;src=media/offers/'.$offer->photos->first()->photo)}}" alt="">
                        @endif
                        <span></span>
                    </a>
                </div><!-- /.box-picture -->

                <div class="box-body">
                    <h2 class="box-title-plain">
                        <a href="{{ url('offers/view/'.$offer->id) }}">{{ $offer->title }}</a>
                        <small class="clearfix">
                          {{ trans('main.Views') }}: {{ $offer->views }}
                          {{ trans('main.Phone') }}: <span class="ltr">{{ $offer->phone }}</span>
                        </small>
                    </h2><!-- /.box-title-plain -->

                    <div class="box-content">
                        <div class="box-meta">
                            <img src="{{ url('resize?w=30&amp;h=30&amp;r=1&amp;c=1&amp;src=media/companies/'.$offer->companyLogo)}}" alt="">
                            {{ $offer->companyName }}
                        </div>
                    </div><!-- /.box-content -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            @endforeach

        </div><!-- /.bxslider -->
        @else 
        <div class="contents center">{{ trans('main.No Data Avilable') }}</div>
        @endif
    </div><!-- /.block-content-inner -->
</div><!-- /.block-content -->
@stop