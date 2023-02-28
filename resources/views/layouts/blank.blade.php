<!doctype html>
<html>
<head>

  <title>
    {{ $_setting['siteName'.LANG] }} | {{ isset($title) ? $title : trans('main.Home') }}

  </title>
  <meta charset="utf-8">
  <meta name="description" content="{{ $_setting['metaDescription'] }}">
  <meta name="keywords" content="{{ $_setting['metaKeywords'] }}">
  <meta name="author" content="Website - by SZ4H | http://sz4h.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

  <link rel="shortcut icon" href="{{ url('media/fav.png') }}">
  @include('partials.headinclude')
  @yield('custom_head_include')
  @yield('otherheads')
</head>

<body class="no-transition {{-- stretched --}} {{ str_replace('/', ' ',Request::path()) }} sticky-responsive-menu @if(LANG=='Ar') rtl @else ltr @endif">

    
    <div id="messages">
      @include('partials.messages')
    </div>
    @yield('contents')


  @include('partials.foot_script')
</body>
</html>