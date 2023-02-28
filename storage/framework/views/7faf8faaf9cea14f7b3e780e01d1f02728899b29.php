<?php echo e(HTML::script('assets/js/jquery.validate.min.js')); ?>


<form action="<?php echo e(url('menu/save')); ?>" id="submitday" method="post" accept-charset="utf-8">
    <div class="modal-body">
        
        <input type="hidden" name="dateId" value="<?php echo e($dateId); ?>">
        <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">
        <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">


        <?php $__currentLoopData = $validItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <h3 class="text-center meal-row"><?php echo e($meal->titleEn); ?></h3>
            <div class="panel-group" id="accordion<?php echo e($i); ?>" role="tablist" aria-multiselectable="true">
                <?php if(isset($meal->categories)): ?>
                    <?php $__currentLoopData = $meal->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ii => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($category->active==1): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading<?php echo e($category->id); ?>">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion<?php echo e($i); ?>" href="#collapse-popup<?php echo e($category->id); ?>" aria-expanded="false" aria-controls="collapse-popup<?php echo e($category->id); ?>"><?php echo e($category->titleEn); ?></a>
                                    </h4>
                                </div>

                                <div id="collapse-popup<?php echo e($category->id); ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
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
                                                <input type="radio" <?php echo e($select); ?> name="items[<?php echo e($meal->id); ?>][item]" id="item<?php echo e($item->id); ?>" class="pull-left flip item-radio" value="<?php echo e($category->id); ?>|<?php echo e($item->id); ?>" required>
                                                <img src="<?php echo e(!empty($item->photo)?url('/resize?w=100&h=100&r=1&c=1&src=/media/items/'.$item->photo):url('/images/no-image.jpg')); ?>" class="pull-left flip" alt="<?php echo e($item->titleEn); ?>" style="max-width:100px;max-height:100px;">
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

                                                        <input type="checkbox" <?php echo e($select2); ?> name="items[<?php echo e($meal->id); ?>][addons][]" class="item-checks" id="addon<?php echo e($item->id); ?>-<?php echo e($addon->id); ?>" value="<?php echo e($addon->id); ?>">
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

      <?php if(isset($portions)): ?>
      <select name="portion_id" class="form-control">
      <option value="">Choose Portion</option>
      <?php $__currentLoopData = $portions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $portion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($portion['id']); ?>"><?php echo e($portion['titleEn']); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <?php endif; ?>

    </div>
    <div class="modal-footer">
        <img src="https://www.dietfix.com/images/loader.gif"  id="gifloading" style="display:none;" width="40"/>
        <button type="button"  class="btn btn-danger" data-dismiss="modal">Close</button>
        
        <input type="submit"  value="<?php echo e(trans('main.Save')); ?>" class="btn btn-primary saveForm ">
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
                url:"/menu/order/saveOrderByDoctor",
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
<?php /**PATH /home/dietfix/private_fix/resources/views/menu/newIframeDoctor.blade.php ENDPATH**/ ?>