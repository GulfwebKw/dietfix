<div class="col-md-3 col-sm-12 search">
{{ Form::open(array('url' => url('search'))) }}
	{{ Form::text('keyword',(Input::get('keyword')) ? Input::get('keyword') : null, array('class' => 'pull-left flip','placeholder' => 'كلمة البحث')) }}
	{{ Form::button('<i class="fa fa-search"></i>', array('type' => 'submit', 'class' => 'pull-right flip')) }}
{{ Form::close() }}
</div>