<link rel="profile" href="//gmpg.org/xfn/11" />
<link rel="icon" href="https://www.dietfix.com/uploads/logo/ico-226701354.png" type="image/x-icon" />


<link rel="stylesheet" type="text/css" media="all" href="/themes/CherryFramework/bootstrap/css/bootstrap.css" />


<link rel="stylesheet" type="text/css" media="all" href="/themes/CherryFramework/bootstrap/css/responsive.css" />

<link rel="stylesheet" type="text/css" media="all" href="/themes/CherryFramework/css/camera.css" />

<link rel="stylesheet" type="text/css" media="all" href="/themes/theme45516/style.css" />

<link rel='dns-prefetch' href='//netdna.bootstrapcdn.com' />
<link rel='dns-prefetch' href='//fonts.googleapis.com' />
<link rel='dns-prefetch' href='//s.w.org' />


<style type="text/css">
    img.wp-smiley,
    img.emoji {
        display: inline !important;
        border: none !important;
        box-shadow: none !important;
        height: 1em !important;
        width: 1em !important;
        margin: 0 .07em !important;
        vertical-align: -0.1em !important;
        background: none !important;
        padding: 0 !important;
    }
</style>
<link rel='stylesheet' id='theme45516-css'  href='/themes/theme45516/main-style.css' type='text/css' media='all' />
<!--<link rel='stylesheet' id='font-awesome-css'  href='//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css?ver=3.2.1' type='text/css' media='all' />
-->
<link rel='stylesheet' id='magnific-popup-css'  href='/themes/CherryFramework/css/magnific-popup.css?ver=0.9.3' type='text/css' media='all' />

<script type='text/javascript' src='/themes/CherryFramework/js/jquery-1.7.2.min.js?ver=1.7.2'></script>
<script type='text/javascript' src='/themes/CherryFramework/js/jquery-migrate-1.2.1.min.js?ver=1.2.1'></script>
<script type='text/javascript' src='/wp-includes/js/swfobject.js?ver=2.2-20120417'></script>
<script type='text/javascript' src='/themes/CherryFramework/js/modernizr.js?ver=2.0.6'></script>
<script type='text/javascript' src='/themes/CherryFramework/js/jflickrfeed.js?ver=1.0'></script>
<script type='text/javascript' src='/themes/CherryFramework/js/custom.js?ver=1.0'></script>
<script type='text/javascript' src='/themes/CherryFramework/bootstrap/js/bootstrap.min.js?ver=2.3.0'></script>

<meta name="generator" content="WordPress 4.9.1" />
<link rel="canonical" href="https://www.dietfix.com/" />
<link rel='shortlink' href='https://www.dietfix.com/' />

<script>
    var system_folder = 'https://www.dietfix.com/themes/CherryFramework/admin/data_management/',
        CHILD_URL ='https://www.dietfix.com/themes/theme45516',
        PARENT_URL = 'https://www.dietfix.com/themes/CherryFramework',
        CURRENT_THEME = 'theme45516'</script>
<style type='text/css'>
    body { background-image:url(https://www.dietfix.com/themes/theme45516/images/body_bg.gif); background-repeat:repeat; background-position:top center; background-attachment:scroll; }
    body { background-color:#f5f5f5 }

</style>
<style type='text/css'>
    h1 { font: normal 36px/30px Anton;  color:#515151; }
    h2 { font: normal 30px/36px Anton;  color:#515151; }
    h3 { font: normal 24px/24px Anton;  color:#515151; }
    h4 { font: normal 20px/24px Anton;  color:#515151; }
    h5 { font: normal 18px/22px Anton;  color:#515151; }
    h6 { font: normal 14px/17px Anton;  color:#515151; }
    body { font-weight: normal;}
    .logo_h__txt, .logo_link { font: normal 70px/71px Anton;  color:#222222; }
    .sf-menu > li > a { font: normal 18px/22px Anton;  color:#373a3f; }
    .nav.footer-nav a { font: normal 12px/18px Arial, Helvetica, sans-serif;  color:#515151; }
</style>


<!--[if lt IE 9]>

<div id="ie7-alert" style="width: 100%; text-align:center;">

    <img src="http://tmbhtest.com/images/ie7.jpg" alt="Upgrade IE 8" width="640" height="344" border="0" usemap="#Map" />

    <map name="Map" id="Map"><area shape="rect" coords="496,201,604,329" href="http://www.microsoft.com/windows/internet-explorer/default.aspx" target="_blank" alt="Download Interent Explorer" /><area shape="rect" coords="380,201,488,329" href="http://www.apple.com/safari/download/" target="_blank" alt="Download Apple Safari" /><area shape="rect" coords="268,202,376,330" href="http://www.opera.com/download/" target="_blank" alt="Download Opera" /><area shape="rect" coords="155,202,263,330" href="http://www.mozilla.com/" target="_blank" alt="Download Firefox" /><area shape="rect" coords="35,201,143,329" href="http://www.google.com/chrome" target="_blank" alt="Download Google Chrome" />

    </map>

</div>

<![endif]-->

<!--[if gte IE 9]><!-->

<script src="/themes/CherryFramework/js/jquery.mobile.customized.min.js" type="text/javascript"></script>

<script type="text/javascript">

    $(function(){
          $('.sf-menu').mobileMenu({defaultText: "Navigate to..."});


        jQuery('ul.sf-menu').superfish({

            delay: 1000, // the delay in milliseconds that the mouse can remain outside a sub-menu without it closing

            animation: {

                opacity: "show",

                height: "show"

            }, // used to animate the sub-menu open

            speed: "normal", // animation speed

            autoArrows: true, // generation of arrow mark-up (for submenu)

            disableHI: true // to disable hoverIntent detection

        });

        //Zoom fix

        //IPad/IPhone

        var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]'),
            ua = navigator.userAgent,
            gestureStart = function () {

                viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0";

            },
            scaleFix = function () {

                if (viewportmeta && /iPhone|iPad/.test(ua) && !/Opera Mini/.test(ua)) {

                    viewportmeta.content = "width=device-width, minimum-scale=1.0, maximum-scale=1.0";

                    document.addEventListener("gesturestart", gestureStart, false);

                }

            };
            scaleFix();

    });

</script>

<!--<![endif]-->

<script type="text/javascript">

    // Init navigation menu

    jQuery(function(){

        // main navigation init


        jQuery('ul.sf-menu').superfish({

            delay: 1000, // the delay in milliseconds that the mouse can remain outside a sub-menu without it closing

            animation: {

                opacity: "show",

                height: "show"

            }, // used to animate the sub-menu open

            speed: "normal", // animation speed

            autoArrows: true, // generation of arrow mark-up (for submenu)

            disableHI: true // to disable hoverIntent detection

        });



        //Zoom fix

        //IPad/IPhone

        var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]'),

            ua = navigator.userAgent,

            gestureStart = function () {

                viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0";

            },

            scaleFix = function () {

                if (viewportmeta && /iPhone|iPad/.test(ua) && !/Opera Mini/.test(ua)) {

                    viewportmeta.content = "width=device-width, minimum-scale=1.0, maximum-scale=1.0";

                    document.addEventListener("gesturestart", gestureStart, false);

                }

            };

        scaleFix();

    })

</script>

<!-- stick up menu -->

<script type="text/javascript">

    jQuery(document).ready(function(){

        if(!device.mobile() && !device.tablet()){

            jQuery('.header .nav__primary').tmStickUp({

                correctionSelector: jQuery('#wpadminbar')

                ,	listenSelector: jQuery('.listenSelector')

                ,	active: false
                ,	pseudo: true
            });

        }

    })
</script>
<link rel="stylesheet" type="text/css" media="all" href="/themes/theme45516/bootstrap/css/font-awesome/css/font-awesome.css" />
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1125071564721960');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1125071564721960&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<meta name="facebook-domain-verification" content="xcwx8zrta9lwj2i4slb1m6azjvwf9f" />

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-L74F7TJ0LL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-L74F7TJ0LL');
</script>
