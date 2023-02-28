



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

   <?php if(session()->has('message')): ?>
    <div class="alert alert-<?php echo e(session()->get('status')); ?>">
        <?php echo e(session()->get('message')); ?>

    </div>
    <?php endif; ?>
    
    </div>

    <div class="clearfix"></div>

    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">
            <tr>
                <th class="exclude-search">ID</th>
                <th><?php echo e(trans('main.User Name')); ?></th>
                <th><?php echo e(trans('main.Mobile')); ?></th>
                <th><?php echo e(trans('main.Active')); ?></th>
            </tr>

            </thead>
            <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($user->id); ?></td>
                    <td><?php echo e($user->username); ?></td>
                    <td><?php echo e($user->mobile_number); ?></td>
                    <td>
                        <a href="/admin/sendusernotification?id=<?php echo e($user->id); ?>" class="nwrap btn btn-xs green btn-block "><i class="fa fa-envelope"></i> Send Message</button>
                        
         <!--modal-->
         <div class="modal fade" id="pushmodal<?php echo e($user->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Push Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="row">
       <div class="col-lg-6"><label>Title(En)</label><input type="text" class="form-control" name="pushtitleEn<?php echo e($user->id); ?>" id="pushtitleEn<?php echo e($user->id); ?>"></div>
       <div class="col-lg-6"><label>Title(Ar)</label><input type="text" class="form-control" name="pushtitleAr<?php echo e($user->id); ?>" id="pushtitleAr<?php echo e($user->id); ?>"></div>
       </div>
       <div class="row mt-2">
       <div class="col-lg-6"><label>Message(En)</label><textarea class="form-control" name="pushmessageEn<?php echo e($user->id); ?>" id="pushmessageEn<?php echo e($user->id); ?>"></textarea></div>
       <div class="col-lg-6"><label>Message(Ar)</label><textarea class="form-control" name="pushmessageAr<?php echo e($user->id); ?>" id="pushmessageAr<?php echo e($user->id); ?>"></textarea></div>
       </div>
       <p><span id="responsetxt<?php echo e($user->id); ?>"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="<?php echo e($user->id); ?>" class="btn btn-primary sendNowPush">Send Now</button>
      </div>
    </div>
  </div>
</div>
         <!--end modal -->
         
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

                "initComplete": function (oSettings, json) {

                }

            });
            $("#grid .search-field").on('keyup change', function () {

                var colIdx = $(this).parent().parent().index();

                table.column(colIdx).search(this.value).draw();

            });
          
          $(document).on('click','.sendNowPush',function(){
          var id = $(this).attr('id');
          var message_en = $('#pushmessageEn'+id).val();
          var message_ar = $('#pushmessageAr'+id).val();
          var title_en = $('#pushtitleEn'+id).val();
          var title_ar = $('#pushtitleAr'+id).val();
          if(message_en==''){
          $("#responsetxt"+id).html('<div class="alert alert-danger">Please write some text message(En)</div>');
          }
          if(message_ar==''){
          $("#responsetxt"+id).html('<div class="alert alert-danger">Please write some text message(Ar)</div>');
          }
          $.ajax({
				 type: "GET",
				 url: "/admin/notificationuserpost",
				 data: "id="+id+"&message_en="+message_en+"&message_ar="+message_ar+"&title_en="+title_en+"&title_ar="+title_ar,
				 dataType: "json",
				 cache: false,
				 processData:false,
				 success: function(msg){
				 if(msg.status==200){
                 $("#responsetxt"+id).html('<div class="alert alert-success">'+msg.message+'</div>');
                 }else{
                 $("#responsetxt"+id).html('<div class="alert alert-danger">'+msg.message+'</div>');
                 }
				 },
				 error: function(xhr, status, error){
				 }
			 });
          });

        });
    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/activeNotificationUsers.blade.php ENDPATH**/ ?>