<!-- END FOOTER -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

<!-- BEGIN CORE PLUGINS -->

	<!-- START OLD SZ4H -->

	<!-- END OLD SZ4H -->

{{ HTML::script('cpassets/plugins/jquery-migrate-1.2.1.min.js') }}

{{ HTML::script('cpassets/plugins/jquery-migrate-1.2.1.min.js') }}

<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

{{ HTML::script('cpassets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js') }}

{{ HTML::script('cpassets/plugins/bootstrap/js/bootstrap-rtl.min.js') }}

{{ HTML::script('cpassets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js') }}

<!--[if lt IE 9]>

{{ HTML::script('cpassets/plugins/excanvas.min.js') }}

{{ HTML::script('cpassets/plugins/respond.min.js') }}

<![endif]-->

{{ HTML::script('cpassets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}

{{ HTML::script('cpassets/plugins/jquery.blockui.min.js') }}

{{ HTML::script('cpassets/plugins/jqco.js') }}

{{ HTML::script('cpassets/plugins/uniform/jquery.uniform.min.js') }}

<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->

@if ($_adminLang == 'arabic')

{{ HTML::script('cpassets/plugins/jquery-multi-select/js/jquery.multi-select-rtl.js') }}

{{ HTML::script('cpassets/plugins/chosen-bootstrap/chosen/chosen.jquery-rtl.min.js') }}

@else

{{ HTML::script('cpassets/plugins/jquery-multi-select/js/jquery.multi-select.js') }}

{{ HTML::script('cpassets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js') }}

@endif



{{ HTML::script('cpassets/plugins/flot/jquery.flot.js') }}

{{ HTML::script('cpassets/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js') }}

{{ HTML::script('cpassets/plugins/fancybox/source/jquery.fancybox.pack.js') }}

{{ HTML::script('cpassets/plugins/flot/jquery.flot.resize.js') }}

{{ HTML::script('cpassets/plugins/jquery.pulsate.min.js') }}

{{ HTML::script('cpassets/plugins/bootstrap-daterangepicker/date.js') }}

{{ HTML::script('cpassets/plugins/bootstrap-daterangepicker/daterangepicker-rtl.js') }}

{{ HTML::script('cpassets/plugins/gritter/js/jquery.gritter.js') }}

{{ HTML::script('cpassets/plugins/fullcalendar/fullcalendar/fullcalendar.min.js') }}

{{ HTML::script('cpassets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js') }}

{{ HTML::script('cpassets/plugins/jquery.sparkline.min.js') }}

<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->

{{ HTML::script('cpassets/scripts/app.js') }}

{{ HTML::script('cpassets/scripts/index.js') }}

{{ HTML::script('cpassets/scripts/tasks.js') }}

{{ HTML::script('cpassets/plugins/select2/select2.min.js') }}

{{ HTML::script('cpassets/plugins/uniform/jquery.uniform.min.js') }}

{{ HTML::script('cpassets/plugins/bootstrap-switch/static/js/bootstrap-switch.js') }}

<!-- END PAGE LEVEL SCRIPTS -->
<script src="/jquery.barrating.js"></script>
<script src="/js/jscolor.js"></script>
<script>

jQuery(document).ready(function() {

   App.init(); // initlayout and core plugins
    $('.example_rating').barrating({
        theme: 'bootstrap-stars',
        showSelectedRating: false
    });

    //$('#background_color').colorpicker({showOn:'focus'});


   // Index.init();

   // Index.initJQVMAP(); // init index page's custom scripts

   // Index.initCalendar(); // init index page's custom scripts

   // Index.initCharts(); // init index page's custom scripts

   // Index.initChat();

   // Index.initMiniCharts();

   // Index.initDashboardDaterange();

   // Index.initIntro();

   // Tasks.initDashboardWidget();

});

</script>

<!-- END JAVASCRIPTS -->
