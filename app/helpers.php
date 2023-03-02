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



/**
 * @param $queryBuilder
 * @param $columns
 * @param $output_title
 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|Response
 */
function getCsvExport($queryBuilder, $columns, $output_title)
{
    $lines[0] = implode(',', array_keys($columns));
    foreach ($queryBuilder->get() as $item) {
        $colValues = [];
        foreach ($columns as $columnId => $column) {
            if (is_string($column))
                $colValues[] = '"' . str_replace([chr(10), "\""], ["\r", "'"], $item[$column]) . '"';
            else
                $colValues[] = '"' . str_replace([chr(10), "\""], ["\r", "'"], $column($item)) . '"';
        }
        $lines[] = implode(',', $colValues);

    }
    $csvBody = implode(chr(10), $lines);
    return response(chr(0xEF) . chr(0xBB) . chr(0xBF) . $csvBody, 200, [
        'Content-Encoding' => 'UTF-8',
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $output_title . date('Y-m-d-s') . '.csv"',
    ]);
}
