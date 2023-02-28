@extends('admin.layouts.main')
@section('content')
	@if ($results->count() > 0)
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>القضية</th>
					<th>النوع</th>
					<th>الحالة</th>
					<th>البيانات</th>
					<th>التذكير</th>
					<th>الدرجة</th>
					<th>التاريخ</th>
					<th>اخر موعد</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($results as $res)
				<tr>
					<td>{{ $res->cases->officeNo }}</td>
					<td>{{ $res->type->title }}</td>
					<td>{{ $res->status->title }}</td>
					<td>{{ $res->info }}</td>
					<td>{{ $res->reminder->title }}</td>
					<td>{{ $res->degree->title }}</td>
					<td>{{ $res->dated }}</td>
					<td>{{ $res->lastDate }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
			{{-- expr --}}
	@endif
@stop

@section('custom_foot')
@parent
<script>
	window.print();

</script>
@stop