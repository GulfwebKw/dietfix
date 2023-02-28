<?php $__env->startSection('custom_head_include'); ?>
<?php echo e(HTML::style('assets/datepicker/css/datepicker.css')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_foot'); ?>
<?php echo e(HTML::script('http://momentjs.com/downloads/moment.js')); ?>

<?php echo e(HTML::script('assets/datepicker/js/bootstrap-datepicker.js')); ?>


<script>
		var date = $('#date').datepicker({
		  onRender: function(date) {
		  	return '';
		    // return (date.valueOf() >= moment()) ? '' : 'disabled';

		  },
		  format: 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
		  date.hide();
		}).data('datepicker');

</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
	<?php echo e(Form::open(array('url' => url('kitchen/get-'.$type), 'method' => 'get', 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form'))); ?>

		<div>
		<a href="<?php echo e(url('kitchen_summary/preparation')); ?>"><?php echo e(trans('main.Preparation Summary')); ?></a>
		</div>
		<div class="control-group form-group">
			<?php echo e(Form::label('date', trans('main.Date') , array('class' => 'control-label col-sm-4'))); ?>

			<div class="controls col-sm-8">
				<?php echo e(Form::text('date', null, array('class' => 'datepicker form-control','id' => 'date'))); ?>

			</div>
		</div>


		<div class="control-group form-group">
			<?php echo e(Form::button('<i class="fa fa-search"></i> '.trans('main.Search'), array('class' => 'btn btn-primary col-sm-6 col-sm-push-3','id' => 'submit','type' => 'submit'))); ?>

		</div>


	<?php echo e(Form::close()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/kitchen/select_date.blade.php ENDPATH**/ ?>