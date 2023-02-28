<!-- BEGIN GLOBAL MANDATORY STYLES -->

<!-- BEGIN OLD SZ4H -->

   	<!-- END OLD SZ4H -->

<link href="{{ url('cpassets/plugins/bootstrap-3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>



 @if ($_adminLang == 'arabic')

<link href="{{ url('cpassets/plugins/bootstrap/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css"/>

<link href="{{ url('cpassets/plugins/bootstrap/css/bootstrap-responsive-rtl.min.css') }}" rel="stylesheet" type="text/css"/>

<link href="{{ url('cpassets/plugins/bootstrap-3/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css"/>

<link href="{{ url('cpassets/css/style-metro-rtl.css') }}" rel="stylesheet" type="text/css"/>

<link href="{{ url('cpassets/css/style-rtl.css') }}" rel="stylesheet" type="text/css"/>

<link href="{{ url('cpassets/css/style-responsive-rtl.css') }}" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="{{ url('cpassets/plugins/jquery-multi-select/css/multi-select-metro-rtl.css') }}" />

<link rel="stylesheet" type="text/css" href="{{ url('cpassets/plugins/chosen-bootstrap/chosen/chosen-rtl.css') }}" />

<!-- COLOR START -->

<link href="{{ url('cpassets/css/themes/light-rtl.css') }}" rel="stylesheet" type="text/css" id="style_color"/>

<!-- COLOR END -->

@else

<link href="{{ url('cpassets/css/style-metro.css') }}" rel="stylesheet" type="text/css"/>

<link href="{{ url('cpassets/css/style.css') }}" rel="stylesheet" type="text/css"/>

<link href="{{ url('cpassets/css/style-responsive.css') }}" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="{{ url('cpassets/plugins/jquery-multi-select/css/multi-select-metro.css') }}" />

<link rel="stylesheet" type="text/css" href="{{ url('cpassets/plugins/chosen-bootstrap/chosen/chosen.css') }}" />

<!-- COLOR START -->

<link href="{{ url('cpassets/css/themes/light.css') }}" rel="stylesheet" type="text/css" id="style_color"/>

<!-- COLOR END -->

@endif


<link href="{{ url('cpassets/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>

<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->

<link href="{{ url('cpassets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" type="text/css"/>

@if ($_adminLang == 'arabic')

<link href="{{ url('cpassets/plugins/bootstrap-daterangepicker/daterangepicker-rtl.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ url('cpassets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-metro-rtl.css') }}" rel="stylesheet" type="text/css" />

@else

<link href="{{ url('cpassets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ url('cpassets/css/pages/login-soft-rtl.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ url('cpassets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-metro.css') }}" rel="stylesheet" type="text/css" />

@endif

<link href="{{ url('cpassets/plugins/fullcalendar/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css"/>

<link href="{{ url('cpassets/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" media="screen"/>

<link href="{{ url('cpassets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.css') }}" rel="stylesheet" type="text/css" media="screen"/>

<link href="{{ url('cpassets/plugins/fancybox/source/jquery.fancybox.css') }}" rel="stylesheet" type="text/css" media="screen"/>

<link href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
<link href="{{ url('cpassets/css/print.css') }}" rel="stylesheet" type="text/css" media="print"/>

{{--<style>--}}
{{--    @font-face {--}}
{{--        font-family: 'Glyphicons Halflings';--}}
{{--        src:url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.eot');--}}
{{--        src:url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'),--}}
{{--        url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.woff') format('woff'),--}}
{{--        url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.ttf') format('truetype'),--}}
{{--        url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');--}}
{{--    }--}}
{{--</style>--}}
<link rel="stylesheet" href="/fontawesome-stars.css">
<link rel="stylesheet" href="/css-stars.css">
<link rel="stylesheet" href="/bootstrap-stars.css">
<link rel="stylesheet" href="/css/evo.css">



<!-- END PAGE LEVEL PLUGIN STYLES -->

<!-- BEGIN PAGE LEVEL STYLES -->

<link href="{{ url('cpassets/css/custom.css') }}" rel="stylesheet" type="text/css" media="screen"/>
<link href="" rel="stylesheet" type="text/css" media="screen"/>

<style>

</style>
<!-- END PAGE LEVEL STYLES -->

<link rel="shortcut icon" href="{{ url('favicon.ico') }}" />

<!-- Main Javascript Load -->

<script src="{{ url('cpassets/plugins/jquery-1.10.1.min.js') }}" type="text/javascript"></script>

<script src="{{ url('cpassets/plugins/jquery-migrate-1.2.1.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    var SITEPATH = '{{ env('APP_URL') }}';
    var APPPATH = '{{ env('APP_URL') }}'+'/app/';
    var APPROOT ='{{ env('APP_URL') }}';
	var ADMINPATH ='{{ env('APP_URL') }}';
	var ADMINROOT ='{{ env('APP_URL') }}';

</script>

{{--<script type="text/javascript" src="{{ url('cpassets/ext.php?type=js&amp;urls=loadmodule.js|custom.js') }}"></script>--}}

<!-- Main Javascript Load -->
