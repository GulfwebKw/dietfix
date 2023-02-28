<?php $__env->startSection('custom_head_include'); ?>



    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js')); ?>

    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js')); ?>

    <?php echo e(HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css')); ?>

    <?php echo e(HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css')); ?>

    <?php echo e(HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css')); ?>


    <?php echo e(HTML::style('cpassets/css/pqgrid.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/pqgrid.min.js')); ?>

    <!--<?php echo e(HTML::style('cpassets/css/jquery.dataTables.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/datatables.min.js')); ?>-->
    <!--<?php echo e(HTML::style('cpassets/css/jquery.dataTables.min.css')); ?>-->
    <!--<?php echo e(HTML::script('cpassets/js/datatables.min.js')); ?>-->
   <!-- <?php echo e(HTML::style('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css')); ?>-->
    <?php echo e(HTML::style('//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css')); ?>


    <?php if(!empty($customJS)): ?>
        <?php echo e(HTML::script($customJS)); ?>

    <?php endif; ?>


    <?php if($_adminLang == 'arabic'): ?>

        <?php echo e(HTML::style('cpassets/plugins/data-tables/DT_bootstrap_rtl.css')); ?>


    <?php else: ?>

        <?php echo e(HTML::style('cpassets/plugins/data-tables/DT_bootstrap.css')); ?>


    <?php endif; ?>

<?php $__env->stopSection(); ?>







<?php $__env->startSection('content'); ?>

    <div class="table-toolbar">

        <div class="btn-group pull-left">

            <?php $__currentLoopData = $buts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php if($b['name'] == 'Paid'): ?>

                    <a  class="btn green" href="/admin/payments/paid">

                        Paid <i class="fa fa-list"></i>

                    </a>
                
                <?php endif; ?>
                <?php if($b['name'] == 'UnPaid'): ?>

                    <a  class="btn red" href="/admin/payments/unpaid">

                        UnPaid <i class="fa fa-list"></i>

                    </a>
                
                <?php endif; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    </div>

    <div class="clearfix"></div>

    <br>
    <style>.nwrap{width:100px; text-align:left;}</style>
    <div class="flip-scroll">

        <table class="table table-striped table-bordered" style="width:100%"  id="grid">

            <thead class="flip-content">

           
            <?php if($menuUrl=="admin/membership"){$sdate = Session::get('sdate');?>
            Filter by Date: <input id="startdate" value="<?php if(!empty($sdate)){ echo $sdate;}?>" class="form-control input-sm" style="width:150px;">
            <?php }?>
            <tr>

                <th class="exclude-search table-checkbox">

                    <input type="checkbox" class="group-checkable checkall" data-set="#grid .checkboxes"/>

                </th>

                <th class="exclude-search">

                    ID

                </th>

                <?php $__currentLoopData = $gridFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php if(array_key_exists('width',$field)): ?>

                        <th width="<?php echo e($field['width'] or 10); ?>%">

                            <?php echo e($field['title']); ?>


                        </th>
                    <?php else: ?>

                        <th width="10%">
                            <?php echo e($field['title']); ?>

                        </th>
                    <?php endif; ?>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <th class="exclude-search nwrap">

                </th>

            </tr>

            </thead>


            <tbody>

            </tbody>

        </table>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>

    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js')); ?>


    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js')); ?>


    <script>

        jQuery(document).ready(function($) {

            var table = $('#grid').DataTable({
                "ajax":{
                    "url": "<?php echo e(url($menuUrl.'/ajax?type='.Request()->type)); ?>",

                    "data": function(d, settings){
                        var api = new $.fn.dataTable.Api(settings);
                        var page=0;
                        var start=d.start;
                        if(start==0){
                            page  =1;
                        }else{
                            page=(start/20)+1
                        }




                        // console.log(api.page.len());


                        // Convert starting record into page number
                        d.page =page;
                    }

                },

                "columns": [
                    { "sortable": false, "data": "checkboxCol" } ,

                    { "sortable": true, "data": "<?php echo e($_pk); ?>" },

                        <?php $__currentLoopData = $gridFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    { "sortable": true, "data": "<?php echo e($field['name']); ?>" },

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    { "sortable": false, "data": "butsCol" }
//          { "sortable": false, "data": "mainGridButsCol" } //MH
                ],"lengthMenu": [

                    [10, 20, 50, 100, -1],

                    [10, 20, 50, 100, "All"] // change per page values here

                ],

                "processing" : true,
                "serverSide": true,

                // set the initial value

                "displayLength": <?php echo e($noOfItems); ?>,

                "paginationType": "bootstrap_full_numbers",

                "pagingType": "full_numbers",

                "language": {

                    "lengthMenu": "_MENU_ <?php echo app('translator')->getFromJson('main.Records'); ?> ",

                    "search": '<?php echo app('translator')->getFromJson('main.Search'); ?> : ',

                    "bInfo":false,
                    

                    "processing": '<img src="http://dietfix.com/images/progress.gif" width="20">&nbsp;<?php echo app('translator')->getFromJson('main.Please wait'); ?>...',

                    "paginate": {

                        "last": '<i class="fa fa-arrow-circle-left"></i>',

                        "first": '<i class="fa fa-arrow-circle-right"></i>',

                        "previous": '<i class="fa fa-angle-left"></i>',

                        "next": '<i class="fa fa-angle-right"></i>'

                    }

                },

                "columnDefs": [{
                    "defaultContent": "",
                    "targets": "_all",
                }

                ],
                "paging": true,
                "initComplete": function (oSettings, json) {
                },

            });





            $("#grid .search-field").on( 'keyup change', function  () {

                var colIdx = $(this).parent().parent().index();

                table.column( colIdx ).search( this.value ).draw();

            });


            $('.checkall').click(function(event) {

                if(this.checked) {

                    // Iterate each checkbox

                    $('.table tbody input:checkbox').each(function() {

                        this.checked = true;

                    });

                }

                else {

                    $('.table tbody input:checkbox').each(function() {

                        this.checked = false;

                    });

                }

            });

        });
        //
        

        $(function(){
            $("#startdate").datepicker({
                autoclose:!0,
                format:"yyyy-mm-dd",
                startDate:new Date
            }),
                $("#enddate").datepicker({
                    autoclose:!0,
                    format:"yyyy-mm-dd",
                    startDate:new Date
                })
        });
        $("#startdate").change(function(){
            var dval = $(this).val();
            var mydate = new Date(dval);
            var str = mydate.toString("yyyy-MM-dd");
            $.ajax({
                type: "GET",
                url:  '<?php echo e(url($menuUrl.'s_date/get_dates/')); ?>/'+str,
                data: "",
                dataType: "json",
                contentType: false,
                cache: false,
                processData:false,
                success: function(msg){
                    location.reload();
                },
                error: function(msg){
                    alert("Error found !!!");
                }
            });
        });

        function myFunction(txtValue) {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = txtValue;
            //filter = input.value.toUpperCase();
            table = document.getElementById("grid");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/paymentGrid.blade.php ENDPATH**/ ?>