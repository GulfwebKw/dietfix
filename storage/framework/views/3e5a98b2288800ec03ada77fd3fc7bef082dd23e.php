<?php $__env->startSection('forms2'); ?>
    <?php if(count($days)>=1): ?>
    <div class="control-group permissions">

        		<div class="controls">

					<div class="controls">
						<select name="monthes" id="monthes" class="form-control">
							<option value=""><?php echo e(trans('main.Period')); ?></option>
							<option value="28">1 <?php echo e(trans('main.Month')); ?></option>
							<option value="56">2 <?php echo e(trans('main.Month')); ?></option>
							<option value="84">3 <?php echo e(trans('main.Month')); ?></option>
							<option value="112">4 <?php echo e(trans('main.Month')); ?></option>
							<option value="1">1 <?php echo e(trans('main.Days')); ?></option>
							<option value="2">2 <?php echo e(trans('main.Days')); ?></option>
							<option value="3">3 <?php echo e(trans('main.Days')); ?></option>
							<option value="4">4 <?php echo e(trans('main.Days')); ?></option>
							<option value="5">5 <?php echo e(trans('main.Days')); ?></option>
							<option value="6">6 <?php echo e(trans('main.Days')); ?></option>
							<option value="7">7 <?php echo e(trans('main.Days')); ?></option>
							<option value="8">8 <?php echo e(trans('main.Days')); ?></option>
							<option value="9">9 <?php echo e(trans('main.Days')); ?></option>
							<option value="10">10 <?php echo e(trans('main.Days')); ?></option>
							<option value="11">11 <?php echo e(trans('main.Days')); ?></option>
							<option value="12">12 <?php echo e(trans('main.Days')); ?></option>
							<option value="13">13 <?php echo e(trans('main.Days')); ?></option>
							<option value="14">14 <?php echo e(trans('main.Days')); ?></option>
							<option value="20">20 <?php echo e(trans('main.Days')); ?></option>
							<option value="24">24 <?php echo e(trans('main.Days')); ?></option>
						</select>

						<table class="table table-striped table-bordered table-advance table-hover" id="days-table">
							<tbody>


							<?php for($i = 0; $i < $days_deff; $i++): ?>

								<tr>
									<td>
										<input type="checkbox"
											   <?php if(in_array(date('Y-m-d',strtotime('+ '.$i . ' days',$first)),$days)): ?>
											   checked="checked"
											   <?php endif; ?> class="check-date" name="dates[]"
											   value="<?php echo e(date('Y-m-d',strtotime('+ '.$i . ' days',$first))); ?>"
											   id="dates<?php echo e(date('Y-m-d',strtotime('+ '.$i . ' days',$first))); ?>">
									</td>
									<td>
										<?php echo e(date('l Y-m-d',strtotime('+ '.$i . ' days',$first))); ?>

									</td>
								</tr>
							<?php endfor; ?>
							</tbody>
						</table>

					</div>

                </div>
    </div>
    <?php endif; ?>







<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>
##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##
<?php echo e(HTML::script('js/moment.js')); ?>


<script>
	function checkboxes () {

		$('#days-table').delegate('.check-date','click',function(ee) {
			var last_row = $('#days-table tbody tr').last();
			var last_row_date = last_row.find('input.check-date').val();
			if($(this).is(':checked')) {
				var current_row_date = moment(last_row_date,'YYYY-MM-DD').subtract(1,'days').format("YYYY-MM-DD");
				$('#membership_end').val(current_row_date);
				last_row.remove();
			} else {
				var current_row_date = moment(last_row_date,'YYYY-MM-DD').add(1,'days').format("YYYY-MM-DD");
				var current_row_date_with_week = moment(last_row_date,'YYYY-MM-DD').add(1,'days').format("dddd YYYY-MM-DD");
				$('#membership_end').val(current_row_date);
				var row = '<tr><td ><div ><span class="checker"><input  type="checkbox" checked="checked" class=" check-date" name="dates[]" value="' + current_row_date + '" id="dates' + current_row_date + '"></span></div></td><td>' + current_row_date_with_week + '</td></tr>';
				$('#days-table tbody').append(row);
			}
		});
	}
	jQuery(document).ready(function($) {
		$('#monthes').change(function () {

			// Add Expire Date
			var number = $(this).val();
			var membership_start = $('#membership_start').val();
			// var days = 28 * parseInt(number);
			var days = number;
			var membership_end = moment(membership_start, "YYYY-MM-DD").add(days,'days').format("YYYY-MM-DD");
			$('#membership_end').val(membership_end);


			// Get Date Diff
			var moment_start = moment(membership_start,'YYYY-MM-DD');
			var moment_end = moment(membership_end,'YYYY-MM-DD');
			var days_between = moment_end.diff(moment_start, 'days');

			$('#days-table tbody').html('');
			for (var i = 0; i < days_between; i++) {
				var current_row_date = moment(membership_start,'YYYY-MM-DD').add(i,'days').format("YYYY-MM-DD");
				var current_row_date_with_week = moment(membership_start,'YYYY-MM-DD').add(i,'days').format("dddd YYYY-MM-DD");
				var row = '<tr><td ><div ><span class="checker"><input type="checkbox" checked="checked" class=" check-date" name="dates[]" value="' + current_row_date + '" id="dates' + current_row_date + '"></span></div></td><td>' + current_row_date_with_week + '</td></tr>'
				$('#days-table tbody').append(row);
			}
		});
		checkboxes();
	});

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.forms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/forms/users-days_form.blade.php ENDPATH**/ ?>