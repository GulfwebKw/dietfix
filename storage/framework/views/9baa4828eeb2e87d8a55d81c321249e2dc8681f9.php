



<?php $__env->startSection('custom_head_include'); ?>



    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js')); ?>

    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js')); ?>

    <?php echo e(HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css')); ?>

    <?php echo e(HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css')); ?>

    <?php echo e(HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css')); ?>


    <?php echo e(HTML::style('cpassets/css/pqgrid.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/pqgrid.min.js')); ?>

    <?php echo e(HTML::style('cpassets/css/jquery.dataTables.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/datatables.min.js')); ?>

    <?php echo e(HTML::script('cpassets/js/mebership_dashboard.js')); ?>

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


    </div>

    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">

            Filter by Date: <input id="startdate" <?php if(isset($date)): ?> value="<?php echo e($date); ?>"  <?php endif; ?>  class="form-control input-sm" style="width:150px;">

            <tr>
                <th class="exclude-search">ID</th>
                <th><?php echo e(trans('main.User Name')); ?></th>
                <th><?php echo e(trans('main.Phone No.')); ?></th>
                <th>EndsBy</th>
                <th>PackageAr</th>
                <th>PackageEn</th>
                <th>RoleAr</th>
                <th>RoleEn</th>
                <th><?php echo e(trans('main.Active')); ?></th>
            </tr>

            </thead>
            <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($user->id); ?></td>
                    <td><?php echo e($user->username); ?></td>
                    <td><?php echo e($user->phone); ?></td>
                    <td><?php echo e(optional($user->lastDay)->date); ?></td>
                    <td><?php echo e(optional($user->package)->titleAr); ?></td>
                    <td><?php echo e(optional($user->package)->titleEn); ?></td>
                    <td><?php echo e(optional($user->role)->roleNameAr); ?></td>
                    <td><?php echo e(optional($user->role)->roleNameEn); ?></td>
                    <td>
                        <button onclick="showPopup(<?php echo e($user->id); ?>)" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs green btn-block ">Follow ups</button>
                        <button onclick="sendEmail(<?php echo e($user->id); ?>)" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs gray btn-block ">Email</button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>

    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js')); ?>


    <?php echo e(HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js')); ?>

    <script>
        jQuery(document).ready(function($) {
            var table = $('#grid').DataTable({
                "processing": true,
                "displayLength": <?php echo e($noOfItems); ?>,
                "paginationType": "bootstrap_full_numbers",
                "pagingType": "full_numbers",
                "language": {
                    "lengthMenu": "_MENU_ <?php echo app('translator')->getFromJson('main.Records'); ?> ",
                    "search": '<?php echo app('translator')->getFromJson('main.Search'); ?> : ',
                    "info": "<?php echo app('translator')->getFromJson('main.Showing _START_ to _END_ of _TOTAL_ entries'); ?>",
                    "processing": '<img src="http://sys.dietfix.com/images/progress.gif" width="20">&nbsp;<?php echo app('translator')->getFromJson('main.Please wait'); ?>...',
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

                "initComplete": function (oSettings, json) {

                }

            });
            $("#grid .search-field").on('keyup change', function () {

                var colIdx = $(this).parent().parent().index();

                table.column(colIdx).search(this.value).draw();

            });

        });
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
            window.location.href='/<?php echo e($menuUrl); ?>/'+str;

        });
    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/user/endDashboard.blade.php ENDPATH**/ ?>