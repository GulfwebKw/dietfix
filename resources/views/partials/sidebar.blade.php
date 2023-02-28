<div class="col_one_fourth">
	@banners(['name' => 'side','class' => 'center'])
	@if (Request::segment(1) == 'schools' || (Request::segment(1) == 'search' && Request::segment(2) == 'schools'))
		<a href="{{ (Auth::user() && Auth::user()->role_id == 3) ? url('schools/add') : 'user/login?uri=schools/add' }}" class="btn btn-success btn-block"><i class="fa fa-plus"></i> {{ trans('main.Add KG') }}</a>
		<br>
		@include('search.school')
	@endif
	@if (Request::segment(1) == 'teachers' || (Request::segment(1) == 'search' && Request::segment(2) == 'teachers'))
		<a href="{{ (Auth::user() && Auth::user()->role_id == 2) ? url('teachers/add') : 'user/login?uri=teachers/add' }}" class="btn btn-success btn-block"><i class="fa fa-plus"></i> {{ trans('main.Add Teacher') }}</a>
		<br>
		@include('search.teacher')
	@endif
	@if (Request::segment(1) == 'clubs' || (Request::segment(1) == 'search' && Request::segment(2) == 'clubs'))
		<a href="{{ (Auth::user() && Auth::user()->role_id == 4) ? url('clubs/add') : 'user/login?uri=clubs/add' }}" class="btn btn-success btn-block"><i class="fa fa-plus"></i> {{ trans('main.Add Club') }}</a>
		<br>
		@include('search.club')
	@endif
</div>