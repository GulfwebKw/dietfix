<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\MainApiController;
use App\Models\App\AppAdvertising;
use App\Models\App\AppSlideShow;
use App\Models\Clinic\Package;
use App\Models\Clinic\Gift;
use App\Models\Delivery;
use App\Models\Frontend\UserContact;
use App\Models\Setting;
use App\Models\Discount;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Clinic\Payment;

class GeneralController extends MainApiController
{


    //apply coupon
    public function apply_coupon(Request $request)
    {
        $data = [];

        $total       = !empty($request->total_amount) ? $request->total_amount : '0';
        $coupon_code = !empty($request->coupon_code) ? $request->coupon_code : '0';

        if (empty($coupon_code)) {
            return  $this->sendResponse(400, ['data' => $data, 'message' => "Coupon code is missing"]);
        }
        if (empty($total)) {
            return  $this->sendResponse(400, ['data' => $data, 'message' => "Amount is missing"]);
        }

        $curDate = date("Y-m-d");
        $coupon = Discount::where('active', 1)
            ->where('code', $coupon_code)
            ->first();
        if (empty($coupon->id)) {
            return  $this->sendResponse(400, ['data' => $data, 'message' => "Invalid coupon code"]);
        }
        if (!empty($coupon->id) && strtotime($curDate) < strtotime($coupon->start)) {
            return  $this->sendResponse(400, ['data' => $data, 'message' => "Coupon can be started from " . $coupon->start]);
        }
        if (!empty($coupon->id) && strtotime($curDate) > strtotime($coupon->end)) {
            return  $this->sendResponse(400, ['data' => $data, 'message' => "Coupon code is already expired on " . $coupon->end]);
        }

        if (!empty($coupon->id) && empty($coupon->count_limit)) {
            return  $this->sendResponse(400, ['data' => $data, 'message' => "Usage counts is exceeded.Please try another coupon code."]);
        }

        if ($coupon->package > 0 and $coupon->package != $request->package_id ) {
            return  $this->sendResponse(400, ['data' => $data, 'message' => "The coupon code is not valid for this package."]);
        }
        if ($coupon->package_duration > 0 and $coupon->package_duration != $request->package_duration_id ) {
            return  $this->sendResponse(400, ['data' => $data, 'message' => "The coupon code is not valid for this package duration."]);
        }



        if (!empty($coupon->id) && $coupon->type == "KD") {
            $discountAmt    = $coupon->value;
            $discountAmttxt = number_format($discountAmt, 3) . 'KD';
        } else {
            $discountAmt    = round(($total * $coupon->value) / 100, 3);
            $discountAmttxt = number_format($discountAmt, 3) . 'KD';
        }

        $total = $total - $discountAmt;

        $data[] = [
            'coupon_code' => $coupon->code,
            'coupon_type' => $coupon->type,
            'coupon_discount' => $discountAmt,
            'coupon_discount_text' => $discountAmttxt,
            'amount_after_coupon' => $total
        ];
        return  $this->sendResponse(200, ['data' => $data, 'message' => "You have valid coupon code"]);
    }


    public function getSlideShow(Request $request)
    {

        $whatsApp = Setting::where('key', 'whatsapp')->first();
        $data = AppSlideShow::getList()->toArray();
        if (count($data) >= 1) {
            return  $this->sendResponse(200, ['prefix_slider_photo' => url(env('APP_URL') . '/media/slideshow/'), 'data' => $data, 'message' => "", "whatsapp" => $whatsApp->value]);
        }
        //


        return  $this->sendResponse(205, ['data' => $data, 'message' => "record not found ", "whatsapp" => $whatsApp->value]);
    }
    public function getAdvertising(Request $request)
    {

        $data = AppAdvertising::first();
        if (!empty($data->id)) {
            if ($data->isactive == 'yes') {
                $data->photoEn = url('/media/advertising/' . $data->photoEn);
                $data->photoAr = url('/media/advertising/' . $data->photoAr);
            } else {
                $data->photoEn = '';
                $data->photoAr = '';
            }
            return  $this->sendResponse(200, ['data' => $data, 'message' => 'send single Advertising']);
        } else {
            $data = [];
        }
        return  $this->sendResponse(205, ['data' => $data, 'message' => 'record not found']);
    }
    public function createContact(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fullname' => 'required|max:255',
                'email' => 'required|email',
                'message' => 'required|max:1500',
                'subject' => 'required|max:200',
                'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8',
            ], [
                'fullname.required' => "Please enter your name",
                'email.required' => "Please enter your Email",
                'message.required' => "Please enter your message ",
                'mobile_number.required' => "Please enter your Mobile Number",
                'mobile_number.number' => 'The phone must be a Mobile Number',
                'regex' => 'The Phone format is invalid.',
            ]);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            UserContact::create(['name' => $request->fullname, 'email' => $request->email, 'message' => $request->message, 'phone' => $request->mobile_number, 'subject' => $request->subject, 'send_from_app' => 1]);
            return $this->sendResponse(200, ['data' => [], 'message' => 'Contact information has been sent successfuly']);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => 'Server is Not Stable']);
        }
    }
    public function loginApi(Request $request)
    {
        return $this->sendResponse(401, ['data' => [], 'message' => trans('main.need_login')]);
    }
    public function getPackage(Request $request)
    {

        $package = Package::with(['meals' => function ($r) {
            $r->where('active', 1);
        }, 'packageDurations' => function ($r) {
            $r->where('active', 1)->where('show_mobile', 1);
        }])->where('show_mobile', 1)->where('active', 1)->whereId($request->id)->first();


        if (isset($package)) {
            if (isset($package->photo)) {
                $package->photo = !empty($package->photo) ? url('/media/packages/' . $package->photo) : url('/images/no-image.jpg');
            }
            return  $this->sendResponse(200, ['data' => $package, 'message' => 'send related package']);
        }
        return  $this->sendResponse(205, ['data' => $package, 'message' => 'record not found']);
    }
    public function getPackageList(Request $request)
    {
        $list = Package::with(['meals' => function ($r) {
            $r->where('active', 1);
        }, 'packageDurations' => function ($r) {
            $r->where('active', 1)->where('show_mobile', 1);
        }])->where("show_mobile", 1)->where('active', 1)->get();
        if ($list->count() >= 1) {
            $list->map(function ($item) {
                if (isset($item->photo)) {
                    return $item->photo = !empty($item->photo) ? url('/media/packages/' . $item->photo) : url('/images/no-image.jpg');
                }
            });
            return  $this->sendResponse(200, ['data' => $list, 'message' => 'send all available packages']);
        }
        return  $this->sendResponse(205, ['data' => $list, 'message' => "record not found"]);
    }

    //get new packages
    //get new packages
    public function getNewPackageList(Request $request){
    $arrays = [];
    //recommended
    $list_recommend=[];
    $list_recommend= Package::with(['meals'=>function($r){
        $r->where('active',1);
    },'packageDurations'=>function($r){
        $r->where('active',1)
            ->where('show_mobile',1)
            //->where('count_day','>=',20)
            ->orderBy('count_day','desc');
    }])->where("show_mobile",1)->where('active',1)->where('package_type','recommended')->get();
    if($list_recommend->count()>=1){
        $list_recommend->map(function ($item){
            if(isset($item->photo)){
                return $item->photo = !empty($item->photo)?url('/media/packages/'.$item->photo):url('/images/no-image.jpg');
            }
        });

    $arrays['recommended'] = $list_recommend;
    }else{
    $arrays['recommended'] = [];
    }

    //fast
    $list_fast=[];
    $list_fast= Package::with(['meals'=>function($r){
        $r->where('active',1);
    },'packageDurations'=>function($r){
        $r->where('active',1)->where('show_mobile',1)->orderBy('count_day','desc');
    }])->where("show_mobile",1)->where('active',1)->where('package_type','weightlessness')->get();
    if($list_fast->count()>=1){
        $list_fast->map(function ($item){
            if(isset($item->photo)){
                return $item->photo = !empty($item->photo)?url('/media/packages/'.$item->photo):url('/images/no-image.jpg');
            }
        });

    $arrays['fastweightloss'] = $list_fast;
    }else{
    $arrays['fastweightloss'] = [];
    }


    return  $this->sendResponse(200,['data'=>$arrays,'message'=>"Available record"]);
    }

    public function getGiftList()
    {
        $list = Gift::where('active', 1)->get();
        if ($list->count() >= 1) {
            $list->map(function ($item) {
                if (isset($item->image)) {
                    return $item->image = !empty($item->image) ? url('/media/gifts/' . $item->image) : url('/images/no-image.jpg');
                }
            });
            return  $this->sendResponse(200, ['data' => $list, 'message' => 'send all available gifts']);
        }
        return  $this->sendResponse(205, ['data' => $list, 'message' => "record not found"]);
    }

    public function getProvinces(Request $request)
    {
        $res = DB::table('provinces')->select(['id', 'titleAr', 'titleEn'])->where('active', 1)->orderBy('ordering', 'desc')->get();

        if ($res->count() >= 1) {
            return  $this->sendResponse(200, ['data' => $res, 'message' => '']);
        }
        return  $this->sendResponse(205, ['data' => [], 'message' => ""]);
    }
    public function getProvince(Request $request)
    {
        $res = DB::table('provinces')->select(['id', 'titleAr', 'titleEn'])->where('active', 1)->where('id', $request->id)->first();

        if (isset($res)) {
            return  $this->sendResponse(200, ['data' => $res, 'message' => '']);
        }
        return  $this->sendResponse(205, ['data' => [], 'message' => ""]);
    }
    public function getAreas(Request $request)
    {
        $province_id = $request->province_id;
        $res =  DB::table('areas')->select(['id', 'titleAr', 'titleEn', 'active'])->where('active', 1);
        if (isset($province_id)) {
            $res = $res->where('province_id', $province_id);
        }
        $res = $res->get();
        if ($res->count() >= 1) {
            return  $this->sendResponse(200, ['data' => $res, 'message' => '']);
        }
        return  $this->sendResponse(205, ['data' => [], 'message' => ""]);
    }



    public function getDeliveryType()
    {
        $res=  Delivery::all();
        if($res->count()>=1){
            return  $this->sendResponse(200,['data'=>$res,'message'=>'']);
        }
        return  $this->sendResponse(205,['data'=>[],'message'=>""]);
    }
    public function checkMobileNumberExist(Request  $request)
    {
        $exist =  User::query()->where('mobile_number' , $request->mobile_number)->exists();
        return  $this->sendResponse(200,['data'=>['exist' => $exist],'message'=>""]);
    }

    public function getArea(Request $request)
    {
        $id = $request->id;
        $res =  DB::table('areas')->select(['id', 'titleAr', 'titleEn', 'active'])->where('active', 1)->where('id', $id)->first();
        if (isset($res)) {
            return  $this->sendResponse(200, ['data' => $res, 'message' => '']);
        }
        return  $this->sendResponse(205, ['data' => [], 'message' => ""]);
    }

    public function getTermsAndConditions()
    {
        $settings = DB::table('app_settings')->select('setting_key as key', 'value')->get()->keyBy('key');
        return \response()->json(['terms_conditions' => optional($settings['terms_conditions'])->value]);
    }
    public function getSettings(Request $request)
    {
        $settings = DB::table('app_settings')->select('setting_key as key', 'value')->get()->keyBy('key');
        $data['version_code'] = optional($settings['android_version'])->value;
        $data['content'] = optional($settings['notification_content'])->value;
        return \response()->json(['version_code' => optional($settings['android_version'])->value, 'content' => optional($settings['notification_content'])->value, 'terms_conditions' => optional($settings['terms_conditions'])->value]);
        // return  $this->sendResponse(200,['data'=>['settings'=>$data],'message'=>'']);
    }
    public function temp()
    {
        return bcrypt("123456789");
    }
}
