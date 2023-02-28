<!-- END FOOTER -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

<!-- BEGIN CORE PLUGINS -->

	<!-- START OLD SZ4H -->

	<!-- END OLD SZ4H -->

<?php echo e(HTML::script('cpassets/plugins/jquery-migrate-1.2.1.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/jquery-migrate-1.2.1.min.js')); ?>


<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

<?php echo e(HTML::script('cpassets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/bootstrap/js/bootstrap-rtl.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js')); ?>


<!--[if lt IE 9]>

<?php echo e(HTML::script('cpassets/plugins/excanvas.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/respond.min.js')); ?>


<![endif]-->

<?php echo e(HTML::script('cpassets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/jquery.blockui.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/jqco.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/uniform/jquery.uniform.min.js')); ?>


<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->

<?php if($_adminLang == 'arabic'): ?>

<?php echo e(HTML::script('cpassets/plugins/jquery-multi-select/js/jquery.multi-select-rtl.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/chosen-bootstrap/chosen/chosen.jquery-rtl.min.js')); ?>


<?php else: ?>

<?php echo e(HTML::script('cpassets/plugins/jquery-multi-select/js/jquery.multi-select.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js')); ?>


<?php endif; ?>



<?php echo e(HTML::script('cpassets/plugins/flot/jquery.flot.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/fancybox/source/jquery.fancybox.pack.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/flot/jquery.flot.resize.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/jquery.pulsate.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/bootstrap-daterangepicker/date.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/bootstrap-daterangepicker/daterangepicker-rtl.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/gritter/js/jquery.gritter.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/fullcalendar/fullcalendar/fullcalendar.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/jquery.sparkline.min.js')); ?>


<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->

<?php echo e(HTML::script('cpassets/scripts/app.js')); ?>


<?php echo e(HTML::script('cpassets/scripts/index.js')); ?>


<?php echo e(HTML::script('cpassets/scripts/tasks.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/select2/select2.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/uniform/jquery.uniform.min.js')); ?>


<?php echo e(HTML::script('cpassets/plugins/bootstrap-switch/static/js/bootstrap-switch.js')); ?>


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
<?php /**PATH /home/dietfix/private_fix/resources/views/admin/partials/foot.blade.php ENDPATH**/ ?>