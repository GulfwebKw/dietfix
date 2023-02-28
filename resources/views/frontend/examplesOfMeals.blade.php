@extends('layouts.frontend')

@section('top_js')

    <script type="text/javascript" src="/js/highslide-with-gallery.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/highslide.css" />

    <!--
        2) Optionally override the settings defined at the top
        of the highslide.js file. The parameter hs.graphicsDir is important!
    -->

    <script type="text/javascript">
        hs.graphicsDir = '{{env('APP_URL')}}/highslide/graphics/';
        hs.align = 'center';
        hs.transitions = ['expand', 'crossfade'];
        hs.outlineType = 'rounded-white';
        hs.fadeInOut = true;
        hs.numberPosition = 'caption';
        hs.dimmingOpacity = 0.75;

        // Add the controlbar
        if (hs.addSlideshow) hs.addSlideshow({
            //slideshowGroup: 'group1',
            interval: 5000,
            repeat: false,
            useControls: true,
            fixedControls: 'fit',
            overlayOptions: {
                opacity: .75,
                position: 'bottom center',
                hideOnMouseOut: true
            }
        });
    </script>

@endsection

@section('content')

    @component('frontend.components.breadcrumb')
        @slot('title')
            MEALS SAMPLE
        @endslot
    @endcomponent


    <div class="highslide-gallery">
       @foreach($resultslider as $item)
            <a id="thumb{{$item->id}}" href="/resize?w=600&h=400&src=/media/gallery/{{$item->photo}}" class="highslide" onClick="return hs.expand(this)">
                    <img src="/media/gallery/{{$item->photo}}" alt="Highslide JS" title="Click to enlarge"   style="margin:10px; max-width:265px;"/>
           </a>
       @endforeach
    </div>






@endsection
