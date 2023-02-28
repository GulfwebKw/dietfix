@extends('emails.layouts.main')

@section('contents')

<h2>A new submission</h2>



<div>


	<p>
		{{ trans('main.Arabic Title') }}: {{ $item->titleAr }}
	</p>

	<p>
		{{ trans('main.English Title') }}: {{ $item->titleEn }}
	</p>

	<p>
		{{ trans('main.Email') }}: {{ $item->email }}
	</p>

	<p>
		{{ trans('main.Phone') }}: {{ $item->phone }}
	</p>

	<p>

		{{ trans('main.More info') }} : 
		<br>
		{{ trans('main.User') }} :
		<a href="{{ url(ADMIN_FOLDER.'/users/edit/'.$item->user_id) }}">{{ trans('main.Click Here') }}</a>
		<br>
		{{ trans('main.Item') }} : <a href="{{ url(ADMIN_FOLDER.'/' . $type . 's/edit/'.$item->id) }}">{{ trans('main.Click Here') }}</a>

	</p>

	<p>

		{{ trans('main.Thank you') }}

	</p>

</div>

@stop