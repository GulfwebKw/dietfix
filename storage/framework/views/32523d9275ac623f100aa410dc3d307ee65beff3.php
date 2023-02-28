

<?php $__env->startSection('contents'); ?>
	
	<h3 class="hero">
		<?php echo e(trans('main.Users')); ?>

	</h3>
	<?php echo e(HTML::script('cpassets/jquery-ui-1.11.4/external/jquery/jquery.js')); ?>



	
	<?php echo e(Form::open(['url' => 'menu/users','method' => 'get','id'=>'userForm'])); ?>

	<div class="form-group">
		<label for="filterAllUser" class="control-label">Show All Users</label>
		<input type="checkbox" <?php if(session()->get('typeFilterUser',false)): ?> checked <?php endif; ?> value="1" id="filterAllUser" name="filterAllUser" >
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th><i class="fa fa-spoon"></i></th>
					<th><?php echo e(trans('main.ID')); ?></th>
					<th><?php echo e(trans('main.Username')); ?></th>
					<th><?php echo e(trans('main.Note')); ?></th>
					<th><?php echo e(trans('main.Phone')); ?></th>
					<th><?php echo e(trans('main.Clinic')); ?></th>
					<th><?php echo e(trans('main.Height')); ?></th>
					<th><?php echo e(trans('main.Weight')); ?></th>
					<th><?php echo e(trans('main.BMI')); ?></th>
					<th><?php echo e(trans('main.Membership End')); ?></th>
					<th><?php echo e(trans('main.Actions')); ?></th>
				</tr>
				<tr>
					<th></th>
					<th><?php echo e(Form::text('id',Input::get('id'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('username',Input::get('username'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('note',Input::get('note'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('phone',Input::get('phone'),['class' => 'form-control'])); ?></th>
					<th></th>
					<th><?php echo e(Form::text('height',Input::get('height'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('weight',Input::get('weight'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('bmi',Input::get('bmi'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('membership_end',Input::get('membership_end'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::button('<i class="fa fa-search"></i>', array('type' => 'submit', 'class' => 'btn btn-default yellow'))); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if($users->isEmpty()): ?>
				<tr>
					<td colspan="10"><?php echo e(trans('main.No Results')); ?></td>
				</tr>
			<?php else: ?>
			<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td> <?php  // previous code @if ($user->orders->count() > 0) ?>
						<?php if($user->error_mark > 0): ?>
							<a href="<?php echo e(url('menu/user-menu/'.$user->id)); ?>" class="btn btn-danger btn-xs">*</a>
						<?php endif; ?>
					</td>
					<td><?php echo e($user->id); ?></td>
					<td><a href="<?php echo e(url('user/profile/'.$user->id)); ?>"><?php echo e($user->username); ?></a></td>
					<td><?php echo e($user->note); ?></td>
					<td><?php echo e($user->phone); ?></td>
					<td><?php echo e($user->clinic->{'title'.LANG}); ?></td>
					<td><?php echo e($user->height); ?></td>
					<td><?php echo e($user->weight); ?></td>
					<td><?php echo e($user->bmi); ?></td>
					<td><?php echo e($user->membership_end); ?></td> 
					<td nowrap="nowrap">
					<a href="<?php echo e(url('menu/user-menu/'.$user->id)); ?>" class="btn btn-primary btn-xs"><i class="fa fa-spoon"></i></a>
					<a href="<?php echo e(url('user/profile/'.$user->id)); ?>" class="btn btn-primary btn-xs"><i class="fa fa-user"></i></a>
					<?php if(Auth::user()->clinic->can_appointment): ?>
					<a href="<?php echo e(url('appointments?user='.$user->id)); ?>" class="btn btn-primary btn-xs"><i class="fa fa-clock-o"></i></a>
					<?php endif; ?>
					<?php if($user->autoapprove_menus > 0): ?>
					<input type="checkbox" onchange="autoapproveMenu(<?php echo e($user->id); ?>)"  class="userid-<?php echo e($user->id); ?>" checked="true"> </input>
					<?php else: ?>
					<input type="checkbox" onchange="autoapproveMenu(<?php echo e($user->id); ?>)"  class="userid-<?php echo e($user->id); ?>"> </input>
					<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php echo e(Form::close()); ?>

	
	<?php echo e($users->links()); ?>




	<script type="text/javascript">

		function autoapproveMenu(userId) {
			event.preventDefault();
			console.log(userId);
			var isChecked = $(".userid-"+userId).is(":checked");
			$.ajax({
				url: "auto_approve-menu",
				data: {userid : userId, isChecked: isChecked},
				type: "POST"
			}).done(function(msg){
				if(msg != null)
				{
					var json = $.parseJSON(msg);
					if(json["result"]=== "SUCCESS")
					{
						$(".userid-"+userId).parent().siblings().eq(0).empty();
						console.log($(".userid-"+userId).eq(0).val());
					}
				}

			});

		}
		$(document).ready(function(){
			$(".table.table-striped.table-hover.table-bordered > tbody  > tr").each(function(k, v){
				var useridVal = $(this).children().eq(1).text();
				var selectorName = ".userid-"+useridVal;
				if($(this).children().eq(0).text().indexOf("*")>-1)
				{
					// enable the auto approve toggle
					console.log($(this).find(".auto_approve"));
					var useridVal = $(this).children().eq(1).text();
					console.log($(this).children().eq(1).text());
					var selectorName = ".userid-"+useridVal;
					$(selectorName).removeAttr("checked");
				}
			});

			$("#filterAllUser").on("change",function () {
					$("#userForm").submit();
			});
		});
	</script>
	
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/user/grid.blade.php ENDPATH**/ ?>