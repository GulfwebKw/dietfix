<?php

namespace App\Http\Controllers\Admin;
use App\Models\Clinic\Area;
use App\Models\Clinic\Province;
use App\Models\Slideshow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManagerStatic as Image;
class FileController extends  AdminController
{

    var $siteMenu;

    public function __construct()
    {

        parent::__construct();
        // $this->beforeFilter('force.ssl');

        View::share('provinces', Province::where('active',1)->orderBy('ordering')->get());
        View::share('provinces_with_areas', Province::where('active',1)->with('areas')->orderBy('ordering')->get());
        View::share('areas', Area::where('active',1)->orderBy('title'.LANG)->get());
        View::share('slideshow', Slideshow::where('active' ,1)->get());
    }

    public function isAdmin()
    {
        if (!Auth::user() || in_array(Auth::user()->role_id,[10]) || Auth::user()->active === 1) return true;
        else return false;
    }

    public function notKitchen()
    {
        if (!Auth::user() || !in_array(Auth::user()->role_id,[3,10]) || Auth::user()->active != 1) return true;
        else return false;
    }

    public function notDoctor()
    {
        if (!Auth::user() || !in_array(Auth::user()->role_id,[2,10]) || Auth::user()->active != 1) return true;
        else return false;
    }

    public function notUser()
    {
        if (!Auth::user() || !in_array(Auth::user()->role_id,[1,10]) || Auth::user()->active != 1) return true;
        else return false;
    }

    public function dontAllow()
    {
        return Redirect::to('/')->withMessages([trans('You don\'t have access to this resources')]);
    }

    public function resize($folder = '')
    {



        // http://intervention.olivervogel.net/image/getting_started/laravel
        // http://web.dev/Dropbox/Work/Developments/www/system/public/resize?src=assets/img/a.jpg&w=400&h=400&c=1
        // http://web.dev/Dropbox/Work/Developments/www/system/public/resize?src=assets/img/a.jpg&w=400&r=1

        $publicPath= $uploadDir =str_replace("/private",'',base_path(env('PUBLIC_FOLDER'))).'/';
        $publicPath= $uploadDir =str_replace("/private_fix",'',base_path(env('PUBLIC_FOLDER'))).'/';

        // Image Source
        if (!isset($_GET['src']))
            die();

        if (!empty($folder))
            $_GET['src'] = $folder.$_GET['src'];
        // Width
        $w = (isset($_GET['w'])) ? (int) $_GET['w'] : null;
        // Height
        $h = (isset($_GET['h'])) ? (int) $_GET['h'] : null;
        // Ratio
        $r = (isset($_GET['r']) && $_GET['r'] == 1) ? true : false;
        // Watermark
        $wm = (isset($_GET['wm']) && $_GET['wm'] == 1) ? true : false;
        // Crop
        $c = (isset($_GET['c']) && $_GET['c'] == 1) ? true : false;
        // Background
        $bg = (isset($_GET['bg'])) ? $_GET['bg'] : '#ffffff';

        // Make the image
        // dd(public_path($_GET['src']));

       //  dd($publicPath.$_GET['src']);
        //$image = Image::make(public_path($_GET['src']));
        $image = Image::make($publicPath.$_GET['src']);


        // Resize or Crop
        if ($c) {
            $image = Image::canvas($w,$h,$bg);
            //$thumb = Image::make(public_path($_GET['src']));
            $thumb = Image::make($publicPath.$_GET['src']);
            //  $thumb->resize($w,$h,$r);
            $thumb->resize($w,$h);

            $image->insert($thumb,'center',0,0);
            // $image->resize($w,$h,$r)->crop($w,$h);
        }
        else {
            //$image->resize($w,$h,$r);
            $image->resize($w,$h);
        }

        // $path = dirname(__FILE__).'/../';

        // Add Watermark
        if ($wm)
            $image->insert($publicPath.'assets/img/wm.png','bottom-right',0,0);

        // echo $path.'public/assets/img/wm.png';
        // View The Image
        return $image->response();
        // return Response::make($image, 200, array('Content-Type' => 'image/jpeg'));

    }


    public function upload_files()
    {
        //$this->forgetBeforeFilter('csrf');
        if (Auth::guest()) {
            return Redirect::guest('user/login');
        }

        //dd(base_path(env('PUBLIC_FOLDER')));
        // Set the uplaod directory
        $folder = str_replace("..", "", $_POST['folder']);
        //$uploadDir = public_path($folder);
        $uploadDir =str_replace("/private_fix",'',base_path(env('PUBLIC_FOLDER'))).'/'.$folder;

        // Set the allowed file extensions
        $fileTypes = (isset($_POST['fileExt'])) ? explode(',', $_POST['fileExt']) : "jpg,jpeg,png,gif,bmp"; // Allowed file extensions


        $verifyToken = md5('SecUre!tN0w' . $_POST['timestamp']);

        if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $currentExt = strtolower(pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION));
            $newName = date('Y-m-d-H-i-s').'-'.substr(md5($_FILES['Filedata']['name']), 0, 5).'.'.$currentExt;
            $targetFile = $uploadDir . $newName;

            // Validate the filetype
            if (in_array($currentExt, $fileTypes)) {

                // Save the file
                move_uploaded_file($tempFile, $targetFile);
                echo $newName;

            } else {

                // The file type wasn't allowed
                echo false;

            }
        }

    }

}
