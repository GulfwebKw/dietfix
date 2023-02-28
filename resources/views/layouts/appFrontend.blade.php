<!DOCTYPE html>

<!--[if lt IE 7 ]><html class="ie ie6" lang="en-US"> <![endif]-->

<!--[if IE 7 ]><html class="ie ie7" lang="en-US"> <![endif]-->

<!--[if IE 8 ]><html class="ie ie8" lang="en-US"> <![endif]-->

<!--[if IE 9 ]><html class="ie ie9" lang="en-US"> <![endif]-->

<html lang="en-US">

<head>

    <title>Diet Fix | Meal Sample</title>

    <meta name="description" content="Meal Sample" />

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @includeIf('frontend.partials.head')
    @yield('top_js')
    <style>
        .white-popup {
            position: relative;
            background: #FFF;
            padding: 20px;
            width: auto;
            max-width: 500px;
            margin: 20px auto;
        }
    </style>

</head>

<body class="home page-template page-template-page-home page-template-page-home-php page page-id-203 logged-in">
@yield('top_body')

<div id="motopress-main" class="main-holder">

{{--    @includeIf('frontend.partials.header')--}}
    <div class="motopress-wrapper content-holder clearfix">
        <div class="container">
            <div class="row">
                <div class="span12"  data-motopress-wrapper-type="content">
                    <div class="row">
                        <div class="span12" data-motopress-type="static" style="margin-top: 20px;font-size: 14px" >
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    @includeIf('frontend.partials.footer')--}}

</div>
</body>



@includeIf('frontend.partials.scripts')

@yield('js')

</html>