<?php

define('ADMIN_FOLDER','admin');
define('RESIZE_PATH', 'resize');
define('UPLOAD_PATH', 'upload_files');
define('COPYRIGHTS', '&copy; ' . date('Y') . ' Copyright by <a href="http://www.gulfclick.net/">Gulfweb</a> 	');
define('PRICE_DECIMAL', '3');
define('CURRENCY', 'KD');
define('API_PATH', 'api');
define('PER_PAGE', '9');

define('LANG_SHORT', 'en');

//if (session()->has('lang')) {
//    define('LANG_SHORT', session()->get('lang'));
//} else {
//    define('LANG_SHORT',env('ADMIN_FOLDER'));
//}

if (LANG_SHORT == 'en')
    define('ENG_SHORT', 'en');
else
    define('ENG_SHORT', '');

define("LANG", ucfirst(LANG_SHORT));
function testSess(){
    return session()->has('lang');
}
function getLang(){
    return session()->get('lang','ar');
}