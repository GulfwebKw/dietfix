

{{ HTML::style('design/css/bootstrap.css') }}

@if (session()->get('lang') == 'ar')
{{ HTML::style('//cdn.rawgit.com/morteza/bootstrap-rtl/master/dist/cdnjs/3.3.1/css/bootstrap-rtl.min.css') }}

@endif

{{ HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') }}

{{ HTML::style('assets/css/y-style.css') }}

{{ HTML::style('style.css') }}

<style type="text/css" media="print">

    div.page

	{

	page-break-after: always;

	page-break-inside: avoid;

	}

	.page-break	{ display: block; page-break-before: always; page-break-after: always; }

	.labelbox {height:24.5mm;overflow: hidden;line-height: 1.1;}

</style>

<!--[if lt IE 9]>

	{{ HTML::script('//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js') }}

    {{ HTML::script('//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') }}

    {{ HTML::script('//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js') }}

<![endif]-->



<script>

	var APP_URL = '{{ url('') }}';

	var EMPTY_CART = '{{ trans('main.Your cart is empty') }}';

	var CURRENCY = '{{ CURRENCY }}';

	var LANG = '{{ LANG }}';

</script>