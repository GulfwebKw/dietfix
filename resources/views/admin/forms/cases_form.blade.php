@extends('admin.forms.form')



@section('forms2')

	@if(@in_array('view', $_adminMeta['adminPermission'][22]))

	<div class="portlet box red">

		<div class="portlet-title">

			<div class="caption">

				<i class="fa fa-database"></i>ارشيف التعديلات

			</div>

			<div class="tools">

				<a href="javascript:;" class="collapse"></a>

				<a href="javascript:;" class="remove"></a>

			</div>

		</div>

		<div class="portlet-body">

				<div class="table-responsive">

					<table class="table table-striped table-hover table-condensed table-responsive flip-content">

						<thead class="flip-content">

							<tr>

								<th>الموظف</th>

								<th>التعديل</th>

								<th>الاوامر</th>

							</tr>

						</thead>

						<tbody>

							@if(!isset($item->$_pk))

							<tr>

								<td colspan="3" class="text-center">يجب عليك حفظ القضية كي تتمكن من ادارة التعديلات</td>

							</tr>

							@else

								@if ($item->histories->count() > 0)

								@foreach ($item->histories as $history)

									<tr>

										<td>{{ $history->admin->name }}</td>

										<td>

											@foreach (unserialize($history->data) as $key => $data)

												{{ $fieldsSorted[$key]['title'] }} : 

												@if ($fieldsSorted[$key]['name'] == 'status_id')

													{{ CasesStatus::find($data)->title }}

												@elseif ($fieldsSorted[$key]['name'] == 'admin_id')

													{{ User::find($data)->name }}

												@elseif ($fieldsSorted[$key]['name'] == 'user_id')

													{{ User::find($data)->name }}

												@else

													{{ $data }}

												@endif

												<br>

											@endforeach

										</td>

										<td>

											@if (in_array('edit', $_adminMeta['adminPermission'][22]) && in_array('delete', $_adminMeta['adminPermission'][22]))

											<a href="{{ url(ADMIN_FOLDER.'/cases_history/delete/'.$history->id) }}" class="nwrap btn btn-xs red btn-block"><i class="icon-remove"></i>{{ trans('main.Delete') }}</a>

											@endif

										</td>

									</tr>

								@endforeach

								@else

								<tr>

									<td colspan="3" class="text-center">لم يتم اي تعديل علي القضية الحالية</td>

								</tr>

								@endif

							@endif

						</tbody>

					</table>

				</div>

		</div>

	</div>

	@endif

	@if(@in_array('view', $_adminMeta['adminPermission'][24]))

	<div class="portlet box blue">

		<div class="portlet-title">

			<div class="caption">

				<i class="fa fa-gavel"></i>جلسات المحكمة

			</div>

			<div class="tools">

				<a href="javascript:;" class="collapse"></a>

				<a href="javascript:;" class="remove"></a>

				<a href="javascript:;" class="add"></a>

			</div>

		</div>

		<div class="portlet-body">

				@if(isset($item->$_pk))

				@if (in_array('add', $_adminMeta['adminPermission'][24]))

				<div class="btn-group pull-left">

				<a id="grid_new" class="btn blue" href="{{ url(ADMIN_FOLDER.'/cases_sessions/add/'.$item->$_pk) }}">

				{{ trans('main.Add') }} <i class="icon-plus"></i>

				</a>

				</div>

				@endif

				@endif

				<div class="table-responsive">

					<table class="table table-striped table-hover table-condensed table-responsive flip-content">

						<thead class="flip-content">

							<tr>

								<th>الموظف</th>

								<th>النوع</th>

								<th>الحالة</th>

								<th>التذكير</th>

								<th>الدرجة</th>

								<th>التاريخ</th>

								<th>الاوامر</th>

							</tr>

						</thead>

						<tbody>

							@if(!isset($item->$_pk))

							<tr>

								<td colspan="7" class="text-center">يجب عليك حفظ القضية كي تتمكن من ادارة جلسات الخبراء</td>

							</tr>

							@else

								@if ($item->sessions->count() > 0)

								@foreach ($item->sessions as $session)

									@if ($session->experiance == '0')

									<tr>

										<td>{{ $session->admin->name }}</td>

										<td>{{ $session->type->title }}</td>

										<td>{{ $session->status->title }}</td>

										<td>

											{{ $session->reminder->title }}

										</td>

										<td>

											{{ $session->degree->title }}

										</td>

										<td>

											{{ $session->dated }}

										</td>

										<td rowspan="2">

											@if (in_array('edit', $_adminMeta['adminPermission'][24]))

											<a href="{{ url(ADMIN_FOLDER.'/cases_sessions/edit/'.$session->id) }}" class="nwrap btn btn-xs yellow btn-block"><i class="icon-edit"></i> {{ trans('main.Edit') }}</a>

											@endif

											@if (in_array('view', $_adminMeta['adminPermission'][24]))

											<a href="{{ url(ADMIN_FOLDER.'/cases_sessions/view/'.$session->id) }}" class="nwrap btn btn-xs green btn-block"><i class="icon-camera"></i> {{ trans('main.View') }}</a>

											@endif

											@if (in_array('delete', $_adminMeta['adminPermission'][24]))

											<a href="{{ url(ADMIN_FOLDER.'/cases_sessions/delete/'.$session->id) }}" class="nwrap btn btn-xs red btn-block"><i class="icon-remove"></i> {{ trans('main.Delete') }}</a>

											@endif

										</td>

									</tr>

									<tr>

										<td colspan="6">

											{{ $session->info }}

										</td>

									</tr>

									@endif

								@endforeach

								@else

								<tr>

									<td colspan="7" class="text-center">لا يوجد اي معلومات خاصة بجلسات الخبراء</td>

								</tr>

								@endif

							@endif

						</tbody>

					</table>

				</div>

		</div>

	</div>

	@endif

	@if(@in_array('view', $_adminMeta['adminPermission'][24]))

	<div class="portlet box green">

		<div class="portlet-title">

			<div class="caption">

				<i class="fa fa-gavel"></i>جلسات الخبراء

			</div>

			<div class="tools">

				<a href="javascript:;" class="collapse"></a>

				<a href="javascript:;" class="remove"></a>

				<a href="javascript:;" class="add"></a>

			</div>

		</div>

		<div class="portlet-body">

				@if(isset($item->$_pk))

				@if (in_array('add', $_adminMeta['adminPermission'][24]))

				<div class="btn-group pull-left">

				<a id="grid_new" class="btn green" href="{{ url(ADMIN_FOLDER.'/cases_sessions/add2/'.$item->$_pk) }}">

				{{ trans('main.Add') }} <i class="icon-plus"></i>

				</a>

				</div>

				@endif

				@endif

				<div class="table-responsive">

					<table class="table table-striped table-hover table-condensed table-responsive flip-content">

						<thead class="flip-content">

							<tr>

								<th>الموظف</th>

								<th>النوع</th>

								<th>الحالة</th>

								<th>التذكير</th>

								<th>الدرجة</th>

								<th>التاريخ</th>

								<th>الاوامر</th>

							</tr>

						</thead>

						<tbody>

							@if(!isset($item->$_pk))

							<tr>

								<td colspan="7" class="text-center">يجب عليك حفظ القضية كي تتمكن من ادارة جلسات الخبراء</td>

							</tr>

							@else

								@if ($item->sessions->count() > 0)

								@foreach ($item->sessions as $session)

									@if ($session->experiance == '1')

									<tr>

										<td>{{ $session->admin->name }}</td>

										<td>{{ $session->type->title }}</td>

										<td>{{ $session->status->title }}</td>

										<td>

											{{ $session->reminder->title }}

										</td>

										<td>

											{{ $session->degree->title }}

										</td>

										<td>

											{{ $session->dated }}

										</td>

										<td rowspan="2">

											@if (in_array('edit', $_adminMeta['adminPermission'][24]))

											<a href="{{ url(ADMIN_FOLDER.'/cases_sessions/edit/'.$session->id) }}" class="nwrap btn btn-xs yellow btn-block"><i class="icon-edit"></i> {{ trans('main.Edit') }}</a>

											@endif

											@if (in_array('view', $_adminMeta['adminPermission'][24]))

											<a href="{{ url(ADMIN_FOLDER.'/cases_sessions/view/'.$session->id) }}" class="nwrap btn btn-xs green btn-block"><i class="icon-camera"></i> {{ trans('main.View') }}</a>

											@endif

											@if (in_array('delete', $_adminMeta['adminPermission'][24]))

											<a href="{{ url(ADMIN_FOLDER.'/cases_sessions/delete/'.$session->id) }}" class="nwrap btn btn-xs red btn-block"><i class="icon-remove"></i> {{ trans('main.Delete') }}</a>

											@endif

										</td>

									</tr>

									<tr>

										<td colspan="6">

											{{ $session->info }}

										</td>

									</tr>

									@endif

								@endforeach

								@else

								<tr>

									<td colspan="7" class="text-center">لا يوجد اي معلومات خاصة بجلسات الخبراء</td>

								</tr>

								@endif

							@endif

						</tbody>

					</table>

				</div>

		</div>

	</div>

	@endif

	@if(@in_array('view', $_adminMeta['adminPermission'][23]))

	<div class="portlet box purple">

		<div class="portlet-title">

			<div class="caption">

				<i class="fa fa-group"></i>الاعمال الادارية

			</div>

			<div class="tools">

				<a href="javascript:;" class="collapse"></a>

				<a href="javascript:;" class="remove"></a>

			</div>

		</div>

		<div class="portlet-body">

				@if(isset($item->$_pk))

				@if (in_array('add', $_adminMeta['adminPermission'][23]))

				<div class="btn-group pull-left">

				<a id="grid_new" class="btn purple" href="{{ url(ADMIN_FOLDER.'/management_work/add/'.$item->$_pk) }}">

				{{ trans('main.Add') }} <i class="icon-plus"></i>

				</a>

				</div>

				@endif

				@endif

				<div class="table-responsive">

					<table class="table table-striped table-hover table-condensed table-responsive flip-content">

						<thead class="flip-content">

							<tr>

								<th>الموظف</th>

								<th>النوع</th>

								<th>الحالة</th>

								<th>التذكير</th>

								<th>الدرجة</th>

								<th>التاريخ</th>

								<th>الاوامر</th>

							</tr>

						</thead>

						<tbody>

							@if(!isset($item->$_pk))

							<tr>

								<td colspan="7" class="text-center">يجب عليك حفظ القضية كي تتمكن من ادارة الاعمال الادارية اخاصة بها</td>

							</tr>

							@else

								@if ($item->managements->count() > 0)

								@foreach ($item->managements as $management)

									<tr>

										<td>{{ $management->admin->name }}</td>

										<td>{{ $management->type->title }}</td>

										<td>{{ $management->status->title }}</td>

										<td>

											{{ $management->reminder->title }}

										</td>

										<td>

											{{ $management->degree->title }}

										</td>

										<td>

											{{ $management->dated }}

										</td>

										<td rowspan="2">

											@if (in_array('edit', $_adminMeta['adminPermission'][23]))

											<a href="{{ url(ADMIN_FOLDER.'/management_work/edit/'.$management->id) }}" class="nwrap btn btn-xs yellow btn-block"><i class="icon-edit"></i> {{ trans('main.Edit') }}</a>

											@endif

											@if (in_array('view', $_adminMeta['adminPermission'][23]))

											<a href="{{ url(ADMIN_FOLDER.'/management_work/view/'.$management->id) }}" class="nwrap btn btn-xs green btn-block"><i class="icon-camera"></i> {{ trans('main.View') }}</a>

											@endif

											@if (in_array('delete', $_adminMeta['adminPermission'][23]))

											<a href="{{ url(ADMIN_FOLDER.'/management_work/delete/'.$management->id) }}" class="nwrap btn btn-xs red btn-block"><i class="icon-remove"></i> {{ trans('main.Delete') }}</a>

											@endif

										</td>

									</tr>

									<tr>

										<td colspan="6">

											{{ $management->info }}

										</td>

									</tr>

								@endforeach

								@else

								<tr>

									<td colspan="7" class="text-center">لا يوجد اي بيانات للاعمال الادارية</td>

								</tr>

								@endif

							@endif

						</tbody>

					</table>

				</div>

		</div>

	</div>

	@endif

	@if(@in_array('view', $_adminMeta['adminPermission'][25]))

	<div class="portlet box yellow">

		<div class="portlet-title">

			<div class="caption">

				<i class="fa fa-folder"></i>الملفات

			</div>

			<div class="tools">

				<a href="javascript:;" class="collapse"></a>

				<a href="javascript:;" class="remove"></a>

			</div>

		</div>

		<div class="portlet-body">

				@if(isset($item->$_pk))

				@if (in_array('add', $_adminMeta['adminPermission'][25]))

				<div class="btn-group pull-left">

				<a id="grid_new" class="btn yellow" href="{{ url(ADMIN_FOLDER.'/documents/add/'.$item->$_pk) }}">

				{{ trans('main.Add') }} <i class="icon-plus"></i>

				</a>

				</div>

				@endif

				@endif

				<div class="table-responsive">

					<table class="table table-striped table-hover table-condensed table-responsive flip-content">

						<thead class="flip-content">

							<tr>

								<th>الموظف</th>

								<th>الملف</th>

								<th>النوع</th>

								<th>الحالة</th>

								<th>التذكير</th>

								<th>الدرجة</th>

								<th>التاريخ</th>

								<th>الاوامر</th>

							</tr>

						</thead>

						<tbody>

							@if(!isset($item->$_pk))

							<tr>

								<td colspan="8" class="text-center">يجب عليك حفظ القضية كي تتمكن من ادارة الملفات</td>

							</tr>

							@else

								@if ($item->documents->count() > 0)

								@foreach ($item->documents as $document)

									<tr>

										<td>{{ $document->admin->name }}</td>

										<td><a href="{{ url('media/files/'.$document->file) }}" target="_blank">{{ trans('main.Download') }}</a></td>

										<td>{{ $document->type->title }}</td>

										<td>{{ $document->status->title }}</td>

										<td>

											{{ $document->reminder->title }}

										</td>

										<td>

											{{ $document->degree->title }}

										</td>

										<td>

											{{ $document->dated }}

										</td>

										<td rowspan="2">

											@if (in_array('edit', $_adminMeta['adminPermission'][25]))

											<a href="{{ url(ADMIN_FOLDER.'/documents/edit/'.$document->id) }}" class="nwrap btn btn-xs yellow btn-block"><i class="icon-edit"></i> {{ trans('main.Edit') }}</a>

											@endif

											@if (in_array('view', $_adminMeta['adminPermission'][25]))

											<a href="{{ url(ADMIN_FOLDER.'/documents/view/'.$document->id) }}" class="nwrap btn btn-xs green btn-block"><i class="icon-camera"></i> {{ trans('main.View') }}</a>

											@endif

											@if (in_array('delete', $_adminMeta['adminPermission'][25]))

											<a href="{{ url(ADMIN_FOLDER.'/documents/delete/'.$document->id) }}" class="nwrap btn btn-xs red btn-block"><i class="icon-remove"></i> {{ trans('main.Delete') }}</a>

											@endif

										</td>

									</tr>

									<tr>

										<td colspan="7">

											{{ $document->info }}

										</td>

									</tr>

								@endforeach

								@else

								<tr>

									<td colspan="8" class="text-center">لا يوجد اي ملفات مرتبطة بالقضية</td>

								</tr>

								@endif

							@endif

						</tbody>

					</table>

				</div>

		</div>

	</div>

	@endif





@stop