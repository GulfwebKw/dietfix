



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
    <div class="table-toolbar">

        <div class="btn-group pull-left">
            <a id="grid_new" class="btn green" href="/admin/invoices/add">
                Add <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>

    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">
            <tr>
                <th class="exclude-search">id</th>
                <th>User</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Mobile</th>
                <th>package</th>
                <th>package duration</th>
                <th>count</th>
                <th>sum </th>
                <th>date </th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if(isset($invoice)): ?>
              <?php $__currentLoopData = $invoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->id); ?></td>
                    <td><?php echo e($item->user->username); ?></td>
                    <td><?php echo e($item->user->email); ?></td>
                    <td><?php echo e($item->user->phone); ?></td>
                    <td><?php echo e($item->user->mobile_number); ?></td>
                    <td><?php echo e(optional($item->package)->titleEn); ?></td>
                    <td><?php echo e(optional($item->packageDuration)->titleEn); ?></td>
                    <td><?php echo e($item->count); ?></td>
                    <td><?php echo e($item->sum); ?></td>
                    <td><?php echo e($item->created_at); ?></td>
                    <td>
                        <a href="/admin/invoices/print/<?php echo e($item->id); ?>" data-id="<?php echo e($item->id); ?>" class="nwrap btn btn-xs red btn-block "><i class="fa fa-print"></i> Print</a>


                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

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
    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/invoices/invoices.blade.php ENDPATH**/ ?>