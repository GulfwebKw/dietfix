<h3>{{ trans('main.My Submissions') }}</h3>

<a href="{{ url('teachers/add') }}" class="pull-right flip btn btn-primary btn-success">{{ trans('main.Add New') }}</a>
<div class="clearfix"></div>

<table class="table table-condensed table-hover table-bordered table-striped">
	<thead>
		<tr>
			<th>{{ trans('main.Photo') }}</th>
			<th>{{ trans('main.Arabic Title') }}</th>
			<th>{{ trans('main.English Title') }}</th>
			<th>{{ trans('main.Email') }}</th>
			<th>{{ trans('main.Phone') }}</th>
			<th>{{ trans('main.Manage') }}</th>
		</tr>
	</thead>
	<tbody>
		@if (Auth::user()->teachers->isEmpty())
		<tr>
			<td colspan="6" class="text-center">{{ trans('main.No Results') }}</td>
		</tr>
		@else
		@foreach (Auth::user()->teachers as $item)
		<tr>
			<td>
			@if ($item->logo)
                <img src="{{ url('resize?w=80&h=60&r=1&c=1&src=media/teachers/' . $item->logo) }}" alt="{{ $item->{'title'.LANG} }}">
            @else
                <img src="{{ url('resize?w=80&h=60&r=1&c=1&src=media/default.png') }}" alt="{{ $item->{'title'.LANG} }}">
            @endif
            </td>
			<td>{{ $item->titleAr }}</td>
			<td>{{ $item->titleEn }}</td>
			<td>{{ $item->email }}</td>
			<td>{{ $item->phone }}</td>
			<td>
				<a href="{{ url('teachers/edit/'. $item->id) }}" class="btn btn-xs btn-block btn-info">{{ trans('main.Edit') }}</a>
				<a href="{{ url('teachers/delete/'. $item->id) }}" onclick="return confirm('{{ trans('main.Are you sure?') }}');" class="btn btn-xs btn-block btn-danger">{{ trans('main.Delete') }}</a>
				<a href="{{ url('packages/list/teachers/'. $item->id) }}" class="btn btn-xs btn-block btn-warning">{{ trans('main.Packages') }}</a>
				<a href="{{ url('teachers/activites-list/'. $item->id) }}" class="btn btn-xs btn-block btn-primary">{{ trans('main.Subjects') }}</a>
			</td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>

<div class="clearfix"></div>
<div class="line"></div>
