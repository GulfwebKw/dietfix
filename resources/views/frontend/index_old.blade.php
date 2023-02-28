@extends('layouts.frontend')

@section('top_body')
     @includeIf('frontend.partials.modal')
@endsection

@section('content')

    <div class="row">

        <div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-slider.php">

            <div id="slider-wrapper" class="slider">
                <div class="container">
                    <script type="text/javascript">
                        //    jQuery(window).load(function() {
                        jQuery(function() {

                            var myCamera = jQuery('#camera5a55de724ccd7');

                            if (!myCamera.hasClass('motopress-camera')) {

                                myCamera.addClass('motopress-camera');

                                myCamera.camera({

                                    alignment           : 'topCenter', //topLeft, topCenter, topRight, centerLeft, center, centerRight, bottomLeft, bottomCenter, bottomRight

                                    autoAdvance         : true,   //true, false

                                    mobileAutoAdvance   : true, //true, false. Auto-advancing for mobile devices

                                    barDirection        : 'leftToRight',    //'leftToRight', 'rightToLeft', 'topToBottom', 'bottomToTop'

                                    barPosition         : 'top',    //'bottom', 'left', 'top', 'right'

                                    cols                : 12,

                                    easing              : 'easeOutQuad',  //for the complete list http://jqueryui.com/demos/effect/easing.html

                                    mobileEasing        : '',   //leave empty if you want to display the same easing on mobile devices and on desktop etc.

                                    fx                  : 'random',    //'random','simpleFade', 'curtainTopLeft', 'curtainTopRight', 'curtainBottomLeft',          'curtainBottomRight', 'curtainSliceLeft', 'curtainSliceRight', 'blindCurtainTopLeft', 'blindCurtainTopRight', 'blindCurtainBottomLeft', 'blindCurtainBottomRight', 'blindCurtainSliceBottom', 'blindCurtainSliceTop', 'stampede', 'mosaic', 'mosaicReverse', 'mosaicRandom', 'mosaicSpiral', 'mosaicSpiralReverse', 'topLeftBottomRight', 'bottomRightTopLeft', 'bottomLeftTopRight', 'bottomLeftTopRight'

                                    //you can also use more than one effect, just separate them with commas: 'simpleFade, scrollRight, scrollBottom'

                                    mobileFx            : '',   //leave empty if you want to display the same effect on mobile devices and on desktop etc.

                                    gridDifference      : 250,  //to make the grid blocks slower than the slices, this value must be smaller than transPeriod

                                    height              : '39.1%', //here you can type pixels (for instance '300px'), a percentage (relative to the width of the slideshow, for instance '50%') or 'auto'

                                    imagePath           : 'images/',    //he path to the image folder (it serves for the blank.gif, when you want to display videos)

                                    loader              : 'no',    //pie, bar, none (even if you choose "pie", old browsers like IE8- can't display it... they will display always a loading bar)

                                    loaderColor         : '#ffffff',

                                    loaderBgColor       : '#eb8a7c',

                                    loaderOpacity       : 1,    //0, .1, .2, .3, .4, .5, .6, .7, .8, .9, 1

                                    loaderPadding       : 0,    //how many empty pixels you want to display between the loader and its background

                                    loaderStroke        : 3,    //the thickness both of the pie loader and of the bar loader. Remember: for the pie, the loader thickness must be less than a half of the pie diameter

                                    minHeight           : '50px',  //you can also leave it blank

                                    navigation          : false, //true or false, to display or not the navigation buttons

                                    navigationHover     : false,    //if true the navigation button (prev, next and play/stop buttons) will be visible on hover state only, if false they will be visible always

                                    pagination          : true,

                                    playPause           : false,   //true or false, to display or not the play/pause buttons

                                    pieDiameter         : 33,

                                    piePosition         : 'rightTop',   //'rightTop', 'leftTop', 'leftBottom', 'rightBottom'

                                    portrait            : true, //true, false. Select true if you don't want that your images are cropped

                                    rows                : 8,

                                    slicedCols          : 12,

                                    slicedRows          : 8,

                                    thumbnails          : false,

                                    time                : 7000,   //milliseconds between the end of the sliding effect and the start of the next one

                                    transPeriod         : 1500, //lenght of the sliding effect in milliseconds



                                    ////////callbacks



                                    onEndTransition     : function() {  },  //this callback is invoked when the transition effect ends

                                    onLoaded            : function() {  },  //this callback is invoked when the image on a slide has completely loaded

                                    onStartLoading      : function() {  },  //this callback is invoked when the image on a slide start loading

                                    onStartTransition   : function() {  }   //this callback is invoked when the transition effect starts

                                });

                            }

                        });

                        //    });

                    </script>
                    <div id="camera5a55de724ccd7" class="camera_wrap camera">

                        @foreach( $sliders as $item)
                            <div data-src='/uploads/slideshow/{{$item->photo}}' data-link='{{$item->url}}' data-thumb=''></div>
                        @endforeach

                    </div>
                </div>
            </div><!-- .slider -->

        </div>

    </div>

    <div class="row">

        <div class="span12" data-motopress-type="loop" data-motopress-loop-file="loop/loop-page.php">
            <div id="post-203" class="page post-203 type-page status-publish hentry">
                <div class="service-wrap">
                    <p>&nbsp;</p>
                </div>
                <p>&nbsp;</p>
                <div class="clear"></div>
            </div>
        </div>

    </div>

@endsection

@section('js')
    <script>
        jQuery(document).ready(function(){
            $("#userpostreportformid").on("submit",function (e) {
                  e.preventDefault();
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "/user/registerMessage",
                    data: {name:$("#your-name").val(),phone:$("#your-phone").val(),weight:$("#your-weight").val(),height:$("#your-height").val(),age:$("#your-age").val(),option:$("#build_muscles").val(),details:$("#your-details").val()},
                    cache: false,
                    timeout: 800000,
                    success: function (data) {
                        if(!data.status){
                            $("#formResponse_report").html('<div class="wpcf7-response-output">'+data.errors+'</div>')
                        }else{
                            $("#formResponse_report").html('<div class="wpcf7-response-output">'+data.message+'</div>')
                        }

                    },
                    error: function (e) {
                    }
                });

            });
            if(!device.mobile() && !device.tablet()){

                jQuery('.header .nav__primary').tmStickUp({

                    correctionSelector: jQuery('#wpadminbar')

                    ,	listenSelector: jQuery('.listenSelector')

                    ,	active: false
                    ,	pseudo: true
                });

            }

        });
        /*Open modal box*/
        $(window).load(function () {
            $.magnificPopup.open({
                items: {
                    src: '#test-modal'
                },
                type: 'inline',
                modal: true
                // You may add options here, they're exactly the same as for $.fn.magnificPopup call
                // Note that some settings that rely on click event (like disableOn or midClick) will not work here
            }, 0);
        });
        $(document).on('click', '.popup-modal-dismiss', function (e) {
            e.preventDefault();
            $.magnificPopup.close();
        });




    </script>
 @endsection

