<?php echo e(HTML::script('assets/js/jquery.validate.min.js')); ?>


<form action="<?php echo e(url('menu/save')); ?>" id="submitday" method="post" accept-charset="utf-8">


   <div class="modal-header">
       <?php if(isset($userDate->package)): ?>
           <h4>Related Package : <?php echo e(optional($userDate->package)->titleEn); ?></h4>
            <?php else: ?>
           <h4>Related Package : <?php echo e(optional($user->package)->titleEn); ?></h4>
       <?php endif; ?>
   </div>
    <div class="modal-body">
        
        <input type="hidden" name="package" value="<?php echo e($user->package_id); ?>">
        <input type="hidden" name="user" value="<?php echo e($user->id); ?>">
        <input type="hidden" name="dateId" value="<?php echo e($dateId); ?>">


        <?php $__currentLoopData = $validItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <h3 class="text-center meal-row"><?php echo e($meal->titleEn); ?></h3>
            <div class="panel-group" id="accordion<?php echo e($i); ?>" role="tablist" aria-multiselectable="true">
                <?php if(isset($meal->categories)): ?>
                    <?php $__currentLoopData = $meal->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ii => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($category->active==1): ?>

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading<?php echo e($category->id); ?>">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion<?php echo e($i); ?>" href="#collapse<?php echo e($category->id); ?>" aria-expanded="false" aria-controls="collapse<?php echo e($category->id); ?>"><?php echo e($category->titleEn); ?></a>
                                    </h4>
                                </div>

                                <div id="collapse<?php echo e($category->id); ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">

                                        <?php $__currentLoopData = $category->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $iii => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <?php if(array_key_exists($meal->id,$selectedItem)): ?>
                                                <?php if($selectedItem[$meal->id]['catId']== $category->id): ?>
                                                    <?php if($selectedItem[$meal->id]['itemId']==$item->id): ?>
                                                        <?php $select = ' checked="checked"'; ?>
                                                    <?php else: ?>
                                                        <?php $select = ''; ?>
                                                    <?php endif; ?>

                                                <?php else: ?>
                                                    <?php $select = ''; ?>

                                                <?php endif; ?>

                                            <?php else: ?>
                                                <?php $select = ''; ?>
                                            <?php endif; ?>




                                            <div class="order-item form-group">
                                                <input type="radio" <?php echo e($select); ?> name="items[<?php echo e($meal->id); ?>][item]" id="item<?php echo e($item->id); ?>" class="pull-left flip item-radio" value="<?php echo e($category->id); ?> | <?php echo e($item->id); ?>" required>
                                                <img src="<?php echo e(url('/resize?w=100&h=100&r=1&c=1&src=/media/items/'.$item->photo)); ?>" class="pull-left flip" alt="<?php echo e($item->titleEn); ?>">
                                                <h3><?php echo e($item->titleEn); ?></h3>
                                                <p><?php echo e($item->detailsEn); ?></p>
                                                <?php if(isset($item->addons)): ?>
                                                    <?php $__currentLoopData = $item->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $iiii => $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                        <?php if(array_key_exists($meal->id,$selectedItem)): ?>
                                                            <?php if($selectedItem[$meal->id]['catId']== $category->id): ?>

                                                                <?php if(in_array($addon->id,$selectedItem[$meal->id]['addons']->toArray())): ?>
                                                                    <?php $select2 = ' checked="checked"'; ?>
                                                                <?php else: ?>
                                                                    <?php $select2 = ''; ?>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php $select2 = ''; ?>
                                                            <?php endif; ?>

                                                        <?php else: ?>
                                                            <?php $select2 = ''; ?>
                                                        <?php endif; ?>

                                                        <input type="checkbox" <?php echo e($select2); ?> name="items[<?php echo e($meal->id); ?>][addons][]" class="item-checks" id="addon<?php echo e($item->id); ?>- <?php echo e($addon->id); ?>" value="<?php echo e($addon->id); ?>">
                                                        <?php echo e($addon->titleEn); ?>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </div>
                                </div>

                            </div>


                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

            </div>



        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



    </div>
    <div class="modal-footer">
        <img src="http://www.dietfix.com/members/public/cpassets/images/load.gif"  id="gifloading" style="display:none;"/>
        <button type="button" style="background-color:#ff0000;color:white" class="btn btn-default" data-dismiss="modal">Close</button>
        
        <input type="submit" style="background-color:#009900;color:white" value="<?php echo e(trans('main.Save')); ?>" class="btn btn-primary saveForm ">
    </div>
</form>

<script>
    $( ".saveForm" ).click(function(event) {
        $("#gifloading").show();
        event.preventDefault();
        var form = $( "#submitday" );
        form.validate();
        if (form.valid()) {
            var form = $("#submitday").serialize();

            $.ajax({
                type: "POST",
                url:"/admin/users/saveOrder",
                data: form
            }).done(function( msg ) {
                if(msg.result) {
                    $('.modal').modal('hide');
                    if ($('.saveForm').hasClass('noactionsave')) {
                        $("#messages").html('<div class="alert alert-info"><?php echo e(trans('main.Saved')); ?><br><?php echo e(trans('main.Your changes will not be effected for this Day')); ?></div>');
                    } else {
                        $("#messages").html('<div class="alert alert-success"><?php echo e(trans('main.Saved')); ?></div>');
                    }
                    window.location.reload(false);

                }
            });
        }else{
            alert('Please Select Item For All Meal')
        }
    });

</script>
<?php /**PATH /home/dietfix/private_fix/resources/views/admin/user/iframe.blade.php ENDPATH**/ ?>