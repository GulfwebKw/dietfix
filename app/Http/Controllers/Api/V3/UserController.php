<?php

namespace App\Http\Controllers\Api\V3;

use App\Events\Sms;
use App\Models\App\UserWeekProgress;
use App\Models\CancelFreezeDay;
use App\Models\CashBack;
use App\Models\Clinic\Day;
use App\Models\Clinic\ItemDays;
use App\Models\Clinic\Item;
use App\Models\Clinic\Order;
use App\Models\Clinic\PackageDurations;
use App\Models\Clinic\Payment;
use App\Models\Clinic\UserDate;
use App\Models\Sms as MesageSms;
use App\Http\Controllers\Api\MainApiController;
use App\Models\Clinic\Gift;
use App\Models\Clinic\Package;
use App\Models\ReferralUser;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends MainApiController
{

    use AuthenticatesUsers;

    public function __construct()
    {
        parent::__construct();
    }
    public function username()
    {
        return "phone";
    }
    /**@param Request $request
     * @return \Illuminate\Http\JsonResponse
     * return user object and
     * @api
     * Login user by Mobile Number and Password
     */
    public function login(Request $request)
    {


        $validator = Validator::make($request->only(['mobile_number', 'password']), ['mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8', 'password' => 'required|min:8']);
        if ($validator->fails()) {
            return $this->sendResponse(400, ['message' => $validator->errors()->first(), 'data' => []]);
        }
        $phone = $request->mobile_number;
        if (!auth()->attempt(['mobile_number' => $phone, 'password' => $request->password])) {
            return $this->sendResponse(205, ['data' => [], 'message' => trans('main.not_valid_credentials')]);
        }
        $token = hash('sha256', Str::random(60));
        $user = User::find(auth()->user()->id);
        if (isset($request->fcm_token)) {
            $user->deviceToken = $request->fcm_token;
        }

        $user->api_token = $token;
        $user->save();
        // $sum = $this->getSumPoint($user->id);

        $res = $this->getPackageCountAndReferralPoint($user->id, $user->package_duration_id, $user->membership_start);

        //get last date of subscription
        $datesInfo = DB::table('user_dates')->where('user_id', $user->id)->where('freeze', 0)->orderBy('date', 'desc')->first();
        $lastDate  = !empty($datesInfo->date) ? $datesInfo->date : date('Y-m-d');
        //check future package subscription exist or not
        $is_future_subscription = 0;
        $futureRenewInfo = DB::table('renew_future_package')->where('user_id', $user->id)->first();
        if (!empty($futureRenewInfo->id)) {
            $is_future_subscription = 1;
        }

        $newSubscriptionDate = $this->getValidRenewPackageRequestDate($user->id);
        $newSubscriptionDate = !empty($newSubscriptionDate) ? date('Y-m-d', strtotime($newSubscriptionDate)) : '';
        $cancelDayObject = $user->CancelDay ;
        $cancelDay = $cancelDayObject->toArray() ;
        $cancelDay['freezed_ending_date'] = $cancelDayObject->freezed_ending_date == null ? null : $cancelDayObject->freezed_ending_date->format('Y-m-d');
        $cancelDay['freezed_starting_date'] = $cancelDayObject->freezed_starting_date == null ? null : $cancelDayObject->freezed_starting_date->format('Y-m-d');
        return $this->sendResponse(200, ['data' => ['user' => $user, 'unClaimedGift' => $this->getExisitingGift($user->id), 'remind_day' => $this->getCountRemDaysUser($user->id, $user->membership_start), 'sum_point' => $res[0], 'count_day' => $res[1], 'sum_cash_back' => $res[2], 'subscription_end_date' => $lastDate, 'is_future_subscription' => $is_future_subscription, 'new_starting_date' => $newSubscriptionDate, 'freezeInformation' => $cancelDay ], 'message' => trans("main.login_success")]);
    }
    /**@param Request $request
     * @return \Illuminate\Http\JsonResponse
     * return User Object and return status and message
     * @api
     * create new User
     */

    public function logUserActivity($title, $options = null, $devicetype = null)
    {
        $devicetype = !empty($devicetype) ? $devicetype : 'NA';
        if (is_array($options)) {
            Log::info("UserInApp::{" . $devicetype . ")->" . $title, $options);
        } else {
            Log::info("UserInApp::{" . $devicetype . ")->" . $title);
        }
    }

    public function getUserDetail(Request $request)
    {
        $user = $this->getUser($request);
        $res = $this->getPackageCountAndReferralPoint($user->id, $user->package_duration_id, $user->membership_start);
        //get last date of subscription
        $datesInfo = DB::table('user_dates')->where('user_id', $user->id)->where('freeze', 0)->orderBy('date', 'desc')->first();
        $lastDate  = !empty($datesInfo->date) ? $datesInfo->date : date('Y-m-d');
        //
        //check future package subscription exist or not
        $is_future_subscription = 0;
        $futureRenewInfo = DB::table('renew_future_package')->where('user_id', $user->id)->first();
        if (!empty($futureRenewInfo->id)) {
            $is_future_subscription = 1;
        }
        $newSubscriptionDate = $this->getValidRenewPackageRequestDate($user->id);
        $newSubscriptionDate = !empty($newSubscriptionDate) ? date('Y-m-d', strtotime($newSubscriptionDate)) : '';
        $cancelDayObject = $user->CancelDay ;
        $cancelDay = $cancelDayObject->toArray() ;
        $cancelDay['freezed_ending_date'] = $cancelDayObject->freezed_ending_date == null ? null : $cancelDayObject->freezed_ending_date->format('Y-m-d');
        $cancelDay['freezed_starting_date'] = $cancelDayObject->freezed_starting_date == null ? null : $cancelDayObject->freezed_starting_date->format('Y-m-d');
        return $this->sendResponse(200, ['data' => ['user' => $user, 'unClaimedGift' => $this->getExisitingGift($user->id), 'remind_day' => $this->getCountRemDaysUser($user->id, $user->membership_start), 'sum_point' => $res[0], 'count_day' => $res[1], 'sum_cash_back' => $res[2], 'subscription_end_date' => $lastDate, 'is_future_subscription' => $is_future_subscription, 'new_starting_date' => $newSubscriptionDate, 'freezeInformation' => $cancelDay], 'message' => '']);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->only(['mobile_number', 'password', 'deviceType', 'fcm_token']), ['mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8|unique:users,mobile_number', 'password' => 'required|min:8', 'deviceType' => 'required|in:android,ios,web']);
        if ($validator->fails()) {
            return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
        }
        $user = new User();
        $user->phone = $request->mobile_number;
        $user->clinic_id = 1;
        $user->doctor_id = 9929;
        $user->mobile_number = $request->mobile_number;
        $user->password = bcrypt($request->password);
        $user->country_id = 120;
        $user->deviceType = $request->deviceType;
        $user->building_number = $request->building_number ? $request->building_number : "" ;
        $user->building_number_work = $request->building_number_work ? $request->building_number_work : "";
        if (isset($request->fcm_token)) {
            $user->deviceToken = $request->fcm_token;
        }

        $user->api_token = hash('sha256', Str::random(60));
        $user->save();

        $res = $this->getPackageCountAndReferralPoint($user->id, $user->package_duration_id, $user->membership_start);
        $this->logUserActivity("register User id-->" . $user->id, null, $user->deviceType);
        return $this->sendResponse(200, ['data' => ['user' => User::find($user->id), 'remind_day' => $this->getCountRemDaysUser($user->id, $user->membership_start), 'sum_point' => $res[0], 'count_day' => $res[1], 'sum_cash_back' => $res[2], 'subscription_end_date' => ''], 'message' => trans('main.register_success')]);
    }
    /**@param Request $request
     * @return \Illuminate\Http\JsonResponse
     *return  user Object and status and message
     * @api
     * update  Personal Information  User
     */
    public function update(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), ['password' => 'nullable|min:8', 'email' => 'nullable|unique:users,email,' . auth()->user()->id, 'username' => 'nullable|unique:users,username,' . auth()->user()->id, 'mobile_number' => 'nullable|unique:users,mobile_number,' . auth()->user()->id, 'sex' => 'required|in:Male,Female', 'weight' => 'required', 'height' => 'required']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            $user = User::find(auth()->user()->id);

            if (isset($request->username)) {
                $user->username = $request->username;
            }
            if (isset($request->website)) {
                $user->website = $request->website;
            }
            if (isset($request->email)) {
                $user->email = $request->email;
            }
            if (isset($request->dob)) {
                $user->dob = $request->dob;
            }
            $user->height = $request->height;
            $user->sex = $request->sex;
            $user->weight = $request->weight;

            $user->save();
            $res = $this->getPackageCountAndReferralPoint($user->id, $user->package_duration_id, $user->membership_start);
            $this->logUserActivity("update User id-->" . $user->id, null, $user->deviceType);

            return $this->sendResponse(200, ['data' => ['user' => $user, 'remind_day' => $this->getCountRemDaysUser($user->id, $user->membership_start), 'sum_point' => $res[0], 'count_day' => $res[1], 'sum_cash_back' => $res[2],'unClaimedGift'=>$this->getExisitingGift($user->id)], 'message' => trans('main.update_personal_success')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }
    /**@param Request $request
     * @return \Illuminate\Http\JsonResponse
     *return  user O
     * bject and status and message
     * @api
     * update  Personal Information  User
     */
    public function updateMeta(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), ['username' => 'nullable|unique:users,username,' . auth()->user()->id, 'mobile_number' => 'unique:users,phone,' . auth()->user()->id, 'is_weekend_address_same' => 'required|in:0,1', 'delivery_type' => 'nullable|exists:delivery_type,id']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            $user = User::find(auth()->user()->id);

            $e = intval($request->is_weekend_address_same);
            $user->is_weekend_address_same = $e;
            if ($e == 1) {
                if (isset($request->area)) {
                    $user->area_work_id = $request->area;
                }
                if (isset($request->block)) {
                    $user->block_work = $request->block;
                }
                if (isset($request->street)) {
                    $user->street_work = $request->street;
                }
                if (isset($request->avenue)) {
                    $user->avenue_work = $request->avenue;
                }
                if (isset($request->house_number)) {
                    $user->house_number_work = $request->house_number;
                }
                if (isset($request->phone)) {
                    $user->phone_work = $request->phone;
                }
                if (isset($request->fullname)) {
                    $user->fullname_work = $request->fullname;
                }
                if (isset($request->floor)) {
                    $user->floor_work = $request->floor;
                }
                if (isset($request->building_number)) {
                    $user->building_number_work = $request->building_number;
                }
            } else {

                if (isset($request->area_work)) {
                    $user->area_work_id = $request->area_work;
                }
                if (isset($request->block_work)) {
                    $user->block_work = $request->block_work;
                }
                if (isset($request->street_work)) {
                    $user->street_work = $request->street_work;
                }
                if (isset($request->avenue_work)) {
                    $user->avenue_work = $request->avenue_work;
                }
                if (isset($request->house_number_work)) {
                    $user->house_number_work = $request->house_number_work;
                }
                if (isset($request->phone_work)) {
                    $user->phone_work = $request->phone_work;
                }
                if (isset($request->fullname_work)) {
                    $user->fullname_work = $request->fullname_work;
                }
                if (isset($request->floor_work)) {
                    $user->floor_work = $request->floor_work;
                }
                if (isset($request->building_number_work)) {
                    $user->building_number_work = $request->building_number_work;
                }
            }
            if (isset($request->username)) {
                $user->username = $request->username;
            }
            if (isset($request->phone)) {
                $user->phone = $request->phone;
            }
            if (isset($request->area)) {
                $user->area_id = $request->area;
            }
            if (isset($request->block)) {
                $user->block = $request->block;
            }
            if (isset($request->street)) {
                $user->street = $request->street;
            }
            if (isset($request->avenue)) {
                $user->avenue = $request->avenue;
            }
            if (isset($request->house_number)) {
                $user->house_number = $request->house_number;
            }
            if (isset($request->floor)) {
                $user->floor = $request->floor;
            }
            if (isset($request->building_number)) {
                $user->building_number = $request->building_number;
            }
            //                    if(isset($request->mobile)){
            //                        $user->mobile=$request->mobile;
            //                    }
            if (isset($request->fullname)) {
                $user->fullname = $request->fullname;
            }
            if (isset($request->delivery_type)) {
                $user->delivery_type = $request->delivery_type;
            }

            $user->save();
            $res = $this->getPackageCountAndReferralPoint($user->id, $user->package_duration_id, $user->membership_start);
            $this->logUserActivity("update meta data User id-->" . $user->id, null, $user->deviceType);

            return $this->sendResponse(200, ['data' => ['user' => $user, 'remind_day' => $this->getCountRemDaysUser($user->id, $user->membership_start), 'sum_point' => $res[0], 'count_day' => $res[1], 'sum_cash_back' => $res[2], 'unClaimedGift' => $this->getExisitingGift($user->id)], 'message' => trans('main.update_information')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }
    /**@param Request $request
     * @return \Illuminate\Http\JsonResponse
     *return  user Object and status and message
     * @api
     * update  user profile picture
     */
    public function updatePhoto(Request $request, Filesystem $filesystem)
    {

        try {
            $validator = Validator::make($request->all(), ['profile_picture' => 'required|mimes:jpeg,bmp,png|max:10240']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }

            $file = $request->file('profile_picture');
            $imagePath = public_path("/uploads/users/profile_image/");
            $filename = time() . $file->getClientOriginalName();
            $file->move($imagePath, $filename);
            $user = $this->getUser($request);
            if (isset($user)) {
                $p = "uploads/users/profile_image/" . $filename;
                $user->profile_image = $p;
                $user->save();
                $res = $this->getPackageCountAndReferralPoint($user->id, $user->package_duration_id, $user->membership_start);
                $this->logUserActivity("update photo  User id-->" . $user->id, null, $user->deviceType);

                return $this->sendResponse(200, ['data' => ['user' => $user, 'remind_day' => $this->getCountRemDaysUser($user->id, $user->membership_start), 'sum_point' => $res[0], 'count_day' => $res[1], 'unClaimedGift' => $this->getExisitingGift($user->id)], 'sum_cash_back' => $res[2], 'message' => trans('update_profile')]);
            }
            return $this->sendResponse(205, ['data' => ['user' => null], 'message' => trans('main.user_not_found')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }
    /**@param Request $request
     * @return \Illuminate\Http\JsonResponse
     *return  result
     * @api
     * update  set rating user for food item
     */
    public function setRating(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['item_id' => 'required', 'rating' => 'required|in:1,2,3,4,5']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            $user = $this->getUser($request);
            if (isset($user)) {
                $res = DB::table('rating_food')->where('user_id', $user->id)->where('item_id', $request->item_id)->first();
                if (isset($res)) {
                    return $this->sendResponse(205, ['data' => [], 'message' => trans('main.already_rating')]);
                    // DB::table('rating_food')->where('id',$res->id)->update(['rating'=>intval($request->rating)]);
                    //  $this->updateAvgItem($request->item_id);
                    // return $this->sendResponse(200,['data'=>[],'message'=>'update Rating for item successful']);
                } else {
                    $existOrder = Order::where('user_id', $user->id)->where('item_id', $request->item_id)->where('updated_on', '<', date('Y-m-d', time()))->first();
                    if (isset($existOrder)) {
                        DB::table('rating_food')->insert(['user_id' => $user->id, 'item_id' => intval($request->item_id), 'rating' => intval($request->rating)]);
                        $this->updateAvgItem($request->item_id);
                        $this->logUserActivity("set Rating for food-->" . $request->item_id . " & userId==>" . $user->id, null, $user->deviceType);

                        return $this->sendResponse(200, ['data' => [], 'message' => trans('success_rating')]);
                    } else {
                        return $this->sendResponse(205, ['data' => [], 'message' => trans('main.not_valid_for_set_rating')]);
                    }
                }
            }
            return $this->sendResponse(205, ['data' => [], 'message' => trans('main.user_not_found')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => $e->getMessage()]);
        }
    }
    /**@param Request $request
     * @return \Illuminate\Http\JsonResponse
     *return  result all referral user
     * @api
     *
     */
    public function getReferralList(Request $request)
    {
        $user = $this->getUser($request);
        $res = DB::table('referral_user')->where('user_id', $user->id)->where('status', 0)->limit(5)->get()->toArray();
        return  $this->sendResponse(200, ['data' => ['referralList' => $res], 'message' => ""]);
    }
    public function getReferralPointHistory(Request $request)
    {
        $user = $this->getUser($request);
        $res = DB::table('credit_user')->where('user_id', $user->id)->select('*')->get()->toArray();
        if (count($res) >= 1) {
            return  $this->sendResponse(200, ['data' => ['referralPointHistory' => $res], 'message' => trans('main.success_referral')]);
        }
        return  $this->sendResponse(205, ['data' => [], 'message' => trans("main.empty_credit")]);
    }
    public function setReferralUser(Request $request)
    {

        try {
            // $request->request->set('referral_list',json_decode($request->referral_list));
            $validator = Validator::make($request->all(), ['referral_list' => 'required|array|min:1|max:5', 'referral_list.*' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            $referralList = $request->referral_list;

            $user = $this->getUser($request);
            $count = intval($this->getReferralCount($user->id));

            if ($count >= 5) {
                return $this->sendResponse(205, ['data' => [], 'message' => trans('main.phone_limit')]);
            }
            $e = 5 - $count;

            $sms = MesageSms::first();
            foreach ($referralList as $item) {
                if ($e > 0) {
                    $oldEx = ReferralUser::where('referral_mobile_number', $item)->where('status', 1)->first();
                    $userD = User::where("mobile_number", $item)->first();
                    if (!empty($userD) || !empty($oldEx)) {
                        return $this->sendResponse(205, ['data' => [], 'message' => trans('main.already_registered', ['phone' => $item])]);
                    }

                    if (is_null($oldEx)) {

                        $itemR = ReferralUser::firstOrNew(['user_id' => $user->id, 'referral_mobile_number' => $item, 'status' => 0]);
                        if (is_null($itemR->id)) {
                            $this->logUserActivity("set referral referralNumber-->" . $item . " & userId==>" . $user->id, null, $user->deviceType);

                            $itemR->user_id = $user->id;
                            $itemR->referral_mobile_number = $item;
                            $itemR->status = 0;
                            $itemR->save();
                            $e -= 1;
                            if (isset($sms)) {
                                if ($request->lang == "En") {
                                    $msg = $sms->contentEn;
                                } else {
                                    $msg = $sms->contentAr;
                                }
                                event(new Sms($item, $msg));
                            }
                        }
                    } else {
                        return $this->sendResponse(205, ['data' => [], 'message' => trans('main.phone_limit')]);
                    }
                }
            }
            $res = DB::table('referral_user')->where('user_id', $user->id)->where('status', 0)->limit(5)->get()->toArray();
            return  $this->sendResponse(200, ['data' => ['referralList' => $res], 'message' => "successful update or create referral "]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => $e->getMessage()]);
        }
    }
    public function getReferralCount($userId)
    {
        return  DB::table("referral_user")->where('user_id', $userId)->where('status', 0)->count();
    }



    public function getGuestMeals(Request $request)
    {
        if (empty($request->day)) {
            return  $this->sendResponse(500, ['data' => [], 'message' => "Please choose starting date"]);
        }

        if (empty($request->package_id)) {
            return  $this->sendResponse(500, ['data' => [], 'message' => "Please choose a package"]);
        }

        if (isset($request->day)) {
            $date          = $request->day;
            $unixTimestamp = strtotime($date);
            $dayOfWeek     = date("l", $unixTimestamp);
            $dayNumber     = intval(date("d", $unixTimestamp));
            $validDayName  = $this->selectWeek($date, $dayOfWeek, $dayNumber);
            $day           = $this->getDayId($validDayName);
            if (isset($day)) {
                $dayId = $day->id;
            } else {
                $dayId = null;
            }
        } else {
            $dayId = null;
        }



        if (!empty($request->package_id)) {
            $packId = $request->package_id;
        }


        $cats = Package::with(["categories" => function ($r) {
            $r->where('active', 1);
        }])->whereId($packId)->first();

        if (isset($cats)) {
            $catId = $cats->categories->pluck('id')->toArray();
        } else {
            $catId = [];
        }


        $result = Package::with(['meals' => function ($r) {
            $r->where('meals.active', 1);
        }, 'meals.categories' => function ($e) use ($catId, $dayId) {
            $e->where('active', 1)->whereIn('id', $catId)->whereHas("items", function ($r) use ($dayId) {
                $r->where('items.active', 1);
            });
        }, 'meals.categories.items' => function ($o) use ($dayId) {
            $o->whereHas('days', function ($r2) use ($dayId) {
                $r2->where('days.id', $dayId);
            })->where('active', 1);
        }, 'meals.categories.items.addons' => function ($q) {
            $q->where('active', 1);
        }])->whereId($packId)->first();


        return  $this->sendResponse(200, ['data' => ['prefix_photo_addons' => url(env('APP_URL') . '/media/addons/'), 'prefix_photo_item' => url(env('APP_URL') . '/media/items/'), 'meals' => $result->meals], 'message' => ""]);
    }

    public function getMeals(Request $request)
    {
        $user = $this->getUser($request);

        if (isset($request->day)) {
            $date = $request->day;
            $unixTimestamp = strtotime($date);
            $dayOfWeek = date("l", $unixTimestamp);
            $dayNumber = intval(date("d", $unixTimestamp));
            $validDayName = $this->selectWeek($user->membership_start, $dayOfWeek, $dayNumber);
            $day = $this->getDayId($validDayName);
            if (isset($day)) {
                $dayId = $day->id;
            } else {
                $dayId = null;
            }
        } else {
            $dayId = null;
        }



        $day = UserDate::where('date', $request->day)->where('user_id', $user->id)->first();

        if (isset($day)) {
            if (isset($day->package_id)) {
                $packId = $day->package_id;
            } else {
                $packId = $user->package_id;
            }
        } else {
            $packId = $user->package_id;
        }

        /*
		if(empty($packId)){
		    $packId=$request->package_id;
		}
		*/

        if (!empty($request->package_id)) {
            $packId = $request->package_id;
        }


        $cats = Package::with(["categories" => function ($r) {
            $r->where('active', 1);
        }])->whereId($packId)->first();

        if (isset($cats)) {
            $catId = $cats->categories->pluck('id')->toArray();
        } else {
            $catId = [];
        }


        $result = Package::with(['meals' => function ($r) {
            $r->where('meals.active', 1);
        }, 'meals.categories' => function ($e) use ($catId, $dayId) {
            $e->where('active', 1)->whereIn('id', $catId)->whereHas("items", function ($r) use ($dayId) {
                $r->where('items.active', 1);
            });
        }, 'meals.categories.items' => function ($o) use ($dayId) {
            $o->whereHas('days', function ($r2) use ($dayId) {
                $r2->where('days.id', $dayId);
            })->where('active', 1);
        }, 'meals.categories.items.addons' => function ($q) {
            $q->where('active', 1);
        }])->whereId($packId)->first();


        if (!isset($result->meals)) {
            return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.user_not_subscribe')]);
        }
        return  $this->sendResponse(200, ['data' => ['prefix_photo_addons' => url(env('APP_URL') . '/media/addons/'), 'prefix_photo_item' => url(env('APP_URL') . '/media/items/'), 'meals' => $result->meals], 'message' => ""]);
    }

    public function filterMealsAndCategory($meals)
    {
        $finalMeals = [];
        foreach ($meals as $meal) {
            $selectedCat = [];
            foreach ($meal->categories as $category) {
                if ($category->items->count() >= 1) {
                    array_push($selectedCat, $category);
                }
            }
            if (count($selectedCat) >= 1) {
                $meal->categories = collect($selectedCat);
                array_push($finalMeals, $meal);
            }
        }
        return collect($finalMeals);
    }
    public function getPackageUser(Request $request)
    {
        $user = $this->getUser($request);
        if ($user->package_id != null) {
            $package = Package::with(['meals' => function ($r) {
                $r->where('active', 1);
            }, 'packageDurations' => function ($r) {
                $r->where('active', 1)->where('show_mobile', 1);
            }])->whereId($user->package_id)->first();
            if (isset($package)) {
                return  $this->sendResponse(200, ['data' => ['package' => $package], 'message' => ""]);
            }
            return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.user_not_subscribe')]);
        }
        return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.user_not_subscribe')]);
    }


    public function saveItem(Request $request)
    {

        try {
            $validator = Validator::make($request->only(['day_id', 'males']), ['day_id' => 'required', 'males' => 'required|array', 'males.*' => 'array']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }

            $user = $this->getUser($request);
            $males = $request->males;
            $day = UserDate::where('id', $request->day_id)->where('user_id', $user->id)->first();
            if (isset($day->package_id)) {
                $packId = $day->package_id;
            } else {
                $packId = $user->package_id;
            }


            if (!empty($this->isMealEmpty($males))) {
                return $this->sendResponse(400, ['data' => [], 'message' => 'Make sure you have selected all required meals - 1']);
            }
            //check meals counts & request meals
            $totalMealsByPackage = DB::table('packages_meals')->where('package_id', $packId)->get()->count();
            if (count($males) != $totalMealsByPackage) {
                return $this->sendResponse(400, ['data' => [], 'message' => 'Make sure you have selected all required meals - Chosen Meals = ' . count($males) . '-pakg meal = ' . $totalMealsByPackage . '-pkg id=' . $packId]);
            }

            $change = false;
            if (isset($day)) {

                $today =   date("Y-m-d");
                $date = strtotime("+3 day", strtotime($today));
                $firstValidDay = date("Y-m-d", $date);

                if ($day->date < $firstValidDay) {
                    return $this->sendResponse(400, ['data' => [], 'message' => trans('main.not_valid_change_order')]);
                }

                $unixTimestamp = strtotime($day->date);
                $dayOfWeek = date("l", $unixTimestamp);
                $dayId = Day::where('titleEn', $dayOfWeek)->first();

                //remove previous order
                // DB::table('orders')->where('user_id',$user->id)->where('date_id',$day->id)->delete();

                $this->logUserActivity("orders removed  userId==>" . $user->id, null, $user->deviceType);

                //check valid item for a day

                /*
                $itemstatus = $this->checkvaliddayforitem($males,optional($dayId)->id);

                if(empty($itemstatus['status'])){
                     return $this->sendResponse(400, ['data' => [], 'message' => 'One of the item is found wrong assigned item_id='.$itemstatus['item_id'].',day_id'.$itemstatus['day_id']]);
                }
                */


                foreach ($males as $maleArray) {
                    if (array_key_exists('male_id', $maleArray) && array_key_exists('category_id', $maleArray) && array_key_exists('item_id', $maleArray) && array_key_exists('addons', $maleArray)) {

                        $existOrders = Order::where('meal_id', $maleArray['male_id'])->where('user_id', $user->id)->where('date_id', $day->id)->first();
                        if (!empty($existOrders->id)) {
                            $existOrders->category_id = $maleArray['category_id'];
                            $existOrders->item_id = $maleArray['item_id'];
                            $existOrders->meal_id = $maleArray['male_id'];
                            $existOrders->date_id = $day->id;
                            $existOrders->user_id = $user->id;
                            $existOrders->day_id = optional($dayId)->id;

                            $portion = $this->selectPortion($user->id, $maleArray['male_id'], $packId);
                            if (isset($portion)) {
                                $existOrders->portion_id = $portion;
                            }


                            $existOrders->save();
                            $orderId = $existOrders->id;

                            $this->logUserActivity("set order for day " . $day->date . " userId==>" . $user->id, null, $user->deviceType);
                        } else {
                            $order = new Order();
                            $order->category_id = $maleArray['category_id'];
                            $order->item_id = $maleArray['item_id'];
                            $order->meal_id = $maleArray['male_id'];
                            $order->date_id = $day->id;
                            $order->user_id = $user->id;
                            $order->day_id = optional($dayId)->id;

                            $portion = $this->selectPortion($user->id, $maleArray['male_id'], $packId);
                            if (isset($portion)) {
                                $order->portion_id = $portion;
                            }


                            $order->save();
                            $orderId = $order->id;

                            $this->logUserActivity("set order for day " . $day->date . " userId==>" . $user->id, null, $user->deviceType);
                        }
                        $this->updateAddons($orderId, $maleArray['addons']);
                        $change = true;
                    }
                }
                if ($change) {
                    $day->isMealSelected = 1;
                    $day->update_status = 'user';
                    $day->save();
                }
                $itemDay = $this->itemDay($user->id, $day->id);
                return  $this->sendResponse(200, ['data' => ['dayItems' => $itemDay], 'message' => "successful update or create day items "]);



                //get day item

            }
            return  $this->sendResponse(205, ['data' => [], 'message' => trans("main.Day_Not_Found")]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => $e->getMessage()]);
        }
    }


    //check item assign for the day or not
    public function checkvaliddayforitem($meals,$day_id){
         foreach ($meals as $maleArray) {
                if (array_key_exists('male_id', $maleArray) && array_key_exists('category_id', $maleArray) && array_key_exists('item_id', $maleArray) && array_key_exists('addons', $maleArray)){
                       $days = ItemDays::where('day_id',$day_id)->where('item_id',$maleArray['item_id'])->first();
                       if(empty($days->id)){
                           return ['status'=>false,'day_id'=>$day_id,'item_id'=>$maleArray['item_id']];
                       }
                }
         }

         return ['status'=>true,'day_id'=>'','item_id'=>''];
    }


    public function isMealEmpty($males)
    {
        $flag = 0;
        foreach ($males as $maleArray) {
            if (empty($maleArray['male_id'])) {
                $flag += 1;
            }
        }
        return $flag;
    }

    public function chooseRandomFood(Request $request)
    {

        try {
            $user = $this->getUser($request);
            $days = DB::table('user_dates')->where('date', '>=', $user->membership_start)->where('user_id', $user->id)->where('isMealSelected', 0)->where('freeze', 0)->select('*')->get();
            $package = Package::where('id', $user->package_id)->where('show_mobile', 1)->where('active', 1)->with(['meals' => function ($q) {
                $q->where('active', 1);
            }])->first();

            if (!isset($package) || empty($package)) {
                return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.user_not_subscribe')]);
            }

            foreach ($days as $dayItem) {

                $date = $dayItem;
                $unixTimestamp = strtotime($date->date);
                $dayOfWeek = date("l", $unixTimestamp);
                $dayNumber = date("d", $unixTimestamp);

                $validDayName = $this->selectWeek($user->membership_start, $dayOfWeek, $dayNumber);

                $day = $this->getDayId($validDayName);

                $mainDay = $this->getDayId($dayOfWeek);


                $userDate = UserDate::find($dayItem->id);

                if (isset($dayItem->package_id)) {
                    $packId = $dayItem->package_id;
                } else {
                    $packId = $user->package_id;
                }



                $cats = Package::with(["categories" => function ($r) {
                    $r->where('active', 1);
                }])->whereId($packId)->first();



                if (isset($cats)) {
                    $catId = $cats->categories->pluck('id')->toArray();
                } else {
                    $catId = [];
                }

                // return $package->meals;

                if (isset($package->meals)) {
                    foreach ($package->meals as $meal) {

                        $res = DB::table('meals')
                            ->join('categories', 'meals.id', '=', 'categories.meal_id')
                            ->join('items', 'categories.id', '=', 'items.category_id')
                            ->join('items_days', 'items.id', '=', 'items_days.item_id')
                            ->where('items_days.day_id', $day->id)
                            ->where('categories.active', 1)
                            ->where('items.active', 1)
                            ->where('meals.id', $meal->id)
                            ->whereIn('categories.id', $catId)
                            ->orderBy('items.rating', 'desc')
                            ->select(['items.id as item_id', 'categories.id as category_id'])->first();

                        if (isset($res) && !empty($res)) {
                            $order = Order::firstOrNew(['user_id' => $user->id, 'day_id' => $mainDay->id, 'meal_id' => $meal->id, 'category_id' => $res->category_id, 'item_id' => $res->item_id, 'date_id' => $dayItem->id]);
                            $order->approved = 1;
                            $order->day_id = $mainDay->id;
                            $order->user_id = $user->id;
                            $order->meal_id = $meal->id;
                            $order->category_id = $res->category_id;
                            $order->item_id = $res->item_id;
                            $order->date_id = $dayItem->id;

                            $portion = $this->selectPortion($user->id, $meal->id, $packId);
                            if (isset($portion)) {
                                $order->portion_id = $portion;
                            }


                            $order->save();
                            $userDate->isMealSelected = 1;
                            //update pkg id if empty
                            if(empty($userDate->package_id) && !empty($packId)){
                            $userDate->package_id=$packId;
                            }
                            //end
                            $userDate->save();

                            $this->logUserActivity("V3 choseRandom food  for date " . $userDate->date . " ,PackageID=".$userDate->package_id." and meal==>" . $meal->id . "  userId==>" . $user->id, null, $user->deviceType);
                        } else {
                            $res = DB::table('meals')
                                ->join('categories', 'meals.id', '=', 'categories.meal_id')
                                ->join('items', 'categories.id', '=', 'items.category_id')
                                ->join('items_days', 'items.id', '=', 'items_days.item_id')
                                ->where('items_days.day_id', $mainDay->id)
                                ->where('categories.active', 1)
                                ->where('items.active', 1)
                                ->whereIn('categories.id', $catId)
                                ->orderBy('items.rating', 'desc')
                                ->select(['items.id as item_id', 'categories.id as category_id'])->first();

                            if (isset($res)) {
                                $order = Order::firstOrNew(['user_id' => $user->id, 'day_id' => $mainDay->id, 'meal_id' => $meal->id, 'category_id' => $res->category_id, 'item_id' => $res->item_id, 'date_id' => $dayItem->id]);
                                $order->approved = 1;
                                $order->day_id = $mainDay->id;
                                $order->user_id = $user->id;
                                $order->meal_id = $meal->id;
                                $order->category_id = $res->category_id;
                                $order->item_id = $res->item_id;
                                $order->date_id = $dayItem->id;

                                $portion = $this->selectPortion($user->id, $meal->id, $packId);
                                if (isset($portion)) {
                                $order->portion_id = $portion;
                                }
                                $order->save();
                                $userDate->isMealSelected = 1;
                                //update pkg id if empty
                                if(empty($userDate->package_id) && !empty($packId)){
                                $userDate->package_id=$packId;
                                }
                                //end
                                $userDate->save();
                                $this->logUserActivity("V3 choseRandom food  for date " . $userDate->date . " ,PackageID=".$userDate->package_id." and meal==>" . $meal->id . "  userId==>" . $user->id, null, $user->deviceType);
                            }
                        }
                    }
                }
            }
            $days = $this->getListUserDays($user->id, $user->membership_start);
            return  $this->sendResponse(200, ['data' => ['user_days' => $days], 'message' => trans('main.user_date_update_success')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => $e->getMessage()]);
        }
    }
    public function getDayItem(Request $request)
    {
        $validator = Validator::make($request->only(['date']), ['date' => 'required|date']);
        if ($validator->fails()) {
            return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
        }
        $user = $this->getUser($request);
        $day = UserDate::where('date', $request->date)->where('user_id', $user->id)->first();
        if (isset($day)) {
            $itemDay = $this->itemDay($user->id, $day->id);
            return  $this->sendResponse(200, ['data' => ['dayItems' => $itemDay], 'message' => ""]);
        }
        return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.Day_Not_Found')]);
    }



    public function getUnFreezeDay(Request $request)
    {
        $user = $this->getUser($request);
        $freezed_ending_date = optional($user->CancelDay)->freezed_ending_date ;
        return  $this->sendResponse(200, ['data' => ['freezed_ending_date' => $freezed_ending_date], 'message' => ""]);
    }
    public function updateUnFreezeDay(Request $request)
    {
        $today =   date("Y-m-d");
        $dateEnd = strtotime("+3 day", strtotime($today));
        $firstEndValidDay=date("Y-m-d",$dateEnd);
        $validator = Validator::make($request->only(['end_day']), ['end_day' => "nullable|date|after_or_equal:$firstEndValidDay"]);
        if ($validator->fails()) {
            return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
        }
        $user = $this->getUser($request);
        $cancelDay = optional($user->CancelDay) ;
        if ( $cancelDay->isAutoUnFreezed and $cancelDay->freezed_ending_date->lt( Carbon::createFromFormat('Y-m-d', $request->end_day) ) ) {
            $res= UserDate::where('user_id',$user->id)->where('date','>=',$user->membership_start)
                ->whereDate('date','>=',$cancelDay->freezed_ending_date)
                ->where('date','<=',$request->end_day)
                ->update(['freeze'=>1]);
            $daysId= UserDate::where('user_id',$user->id)->where('date','>=',$user->membership_start)
                ->where('date','<=',$request->end_day)
                ->whereDate('date','>=',$cancelDay->freezed_ending_date)->where('freeze',1)->pluck('id');

            Order::whereIn('date_id',$daysId)->where('user_id',$user->id)->update(['freeze'=>1]);
        } else {
            $res= UserDate::where('user_id',$user->id)->where('date','>=',$user->membership_start)
                ->whereDate('date','<=',$cancelDay->freezed_ending_date)
                ->where('date','>=',$request->end_day)
                ->update(['freeze'=>1]);
            $daysId= UserDate::where('user_id',$user->id)->where('date','>=',$user->membership_start)
                ->where('date','>=',$request->end_day)
                ->whereDate('date','<=',$cancelDay->freezed_ending_date)->where('freeze',1)->pluck('id');

            Order::whereIn('date_id',$daysId)->where('user_id',$user->id)->update(['freeze'=>1]);
        }
        CancelFreezeDay::query()->where('user_id' , $user->id)->update([
            'freezed_ending_date' => $request->end_day,
            'isAutoUnFreezed' => $request->end_day != null,
        ]);
        return  $this->sendResponse(200, ['data' => ['freezed_ending_date' => $request->end_day], 'message' => ""]);
    }

    public function freezeDay(Request $request)
    {

        try {
            $today =   date("Y-m-d");
            $date = strtotime("+3 day", strtotime($today));
            $firstValidDay = date("Y-m-d", $date);
            $dateEnd = strtotime("+4 day", strtotime($today));
            $firstEndValidDay=date("Y-m-d",$dateEnd);

            $validator = Validator::make($request->only(['start_day', 'end_day']), ['start_day' => "required|date|after_or_equal:$firstValidDay", 'end_day' => "nullable|date|after_or_equal:$firstEndValidDay"]);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            $firstValidDay = $request->start_day;

            $user = $this->getUser($request);

            CancelFreezeDay::query()->updateOrCreate(['user_id' => $user->id] , [
                'freezed_ending_date' => $request->end_day,
                'isFreezed' => true,
                'isAutoUnFreezed' => $request->end_day != null,
                'freezed_starting_date' => $firstValidDay,
            ]);
            $res = UserDate::where('user_id', $user->id)->where('date', '>=', $user->membership_start)
                ->when($request->end_day != null , function ($query ) use($request) {
                    return $query->where('date','<=',$request->end_day);
                })->where('date', '>=', $firstValidDay)->update(['freeze' => 1]);
            $daysId = UserDate::where('user_id', $user->id)->where('date', '>=', $user->membership_start)
                ->when($request->end_day != null , function ($query ) use($request) {
                    return $query->where('date','<=',$request->end_day);
                })->where('date', '>=', $firstValidDay)->where('freeze', 1)->pluck('id');

            if (count($daysId)) {
                $this->logUserActivity("App:freeze days  userId==>" . $user->id, $daysId, $user->deviceType);
            }

            Order::whereIn('date_id', $daysId)->where('user_id', $user->id)->update(['freeze' => 1]);
            if ($res) {
                return  $this->sendResponse(200, ['data' => ['user_days' => $this->getListUserDays($user->id, $user->membership_start)], 'message' => "successful update  user date "]);
            }
            return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.Day_Not_Found')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }
    public function cancelFreezeDay(Request $request)
    {
        try {

            $today         = date("Y-m-d");
            $date          = strtotime("+3 day", strtotime($today));
            $firstValidDay = date("Y-m-d", $date);
            $firstDay      = date("Y-m-d", $date);
            $user          = $this->getUser($request);
            $userId        = $user->id;

            $validator     = Validator::make($request->only(['resume_date']), ['resume_date' => "required|date|after_or_equal:$firstValidDay"]);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }

            $firstValidDay = $request->resume_date;
            if (empty($firstValidDay)) {
                return $this->sendResponse(400, ['data' => [], 'message' => 'Resume date is missing']);
            }
            //check resume date should not override the available date
            $resumeDay = UserDate::where('user_id', $user->id)->where('freeze', 1)->orderBy('date', 'asc')->first();
            if (!empty($resumeDay->id)) {
                $date1  = Carbon::createFromFormat('Y-m-d', $resumeDay->date);
                $date2  = Carbon::createFromFormat('Y-m-d', $firstValidDay);
                $reDate = $date1->lte($date2);
                if (empty($reDate)) {
                    return  $this->sendResponse(205, ['data' => [], 'message' => "Invalid resume date"]);
                }
            }

            $countExistFreeze = UserDate::where('user_id', $user->id)->where('date', '>=', $user->membership_start)->where('freeze', 1)->get();
            $count = $countExistFreeze->count();
            if ($count <= 0) {
                return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.Not_Found_Freeze_Day')]);
            }
            /*
			foreach($countExistFreeze as $key=>$freezDate){
			$newDay = date("Y-m-d",strtotime("+$key day", strtotime($firstValidDay)));
			$existDay=UserDate::where('id',$freezDate->id)->first();

			//check date exist
			$existDayE = UserDate::where('date',$newDay)->where('user_id',$user->id)->first();
			if(!empty($existDayE->id)){
			$existDayE->date           = NULL;
			$existDayE->save();
			}

			$existDay->date           = $newDay;
			$existDay->freeze         = 0;
			$existDay->package_id     = $freezDate->package_id;
			$existDay->isMealSelected = $freezDate->isMealSelected;
			$existDay->save();
			$this->logUserActivity("App:update day status and  cancel freeze after cancelFreeze day   date==>".$newDay."  userId==>".$user->id,null,$user->deviceType);
			}
            */

            CancelFreezeDay::query()->updateOrCreate(['user_id' => $user->id] , [
                'freezed_ending_date' => null,
                'isFreezed' => false,
                'isAutoUnFreezed' => false,
                'freezed_starting_date' => null,
            ]);
            foreach ($countExistFreeze as $key => $freezDate) {
                $i = 0 ;
                do {
                    $addDay = $i + $key ;
                    $newDay = date("Y-m-d", strtotime("+$addDay day", strtotime($firstValidDay)));
                    //check date exist
                    $i++;
                    $existDayE = UserDate::where('date', $newDay)->where('user_id', $userId)->first();
                } while ( ! empty($existDayE->id) and $existDayE->freeze == 1 );
                if (!empty($existDayE->id)) {
                    $existDayE->freeze         = 0;
                    if (!empty($this->isOrderExist($existDayE->id, $userId))) {
                        $existDayE->isMealSelected = 1;
                    } else {
                        $existDayE->isMealSelected = 0;
                    }
                    if(!empty($user->package_id) && empty($existDayE->package_id)){
                        $existDayE->package_id = $user->package_id;
                        }

                    $existDayE->save();
                } else {
                    $existDayE = UserDate::where('id', $freezDate->id)->where('user_id', $userId)->first();
                    $existDayE->date           = $newDay;
                    $existDayE->freeze         = 0;
                    $existDayE->update_status  = 'user';
                    if (!empty($this->isOrderExist($existDayE->id, $userId))) {
                        $existDayE->isMealSelected = 1;
                    } else {
                        $existDayE->isMealSelected = 0;
                    }

                    if(!empty($user->package_id) && empty($existDayE->package_id)){
                        $existDayE->package_id = $user->package_id;
                        }

                    $existDayE->save();
                }

                $this->makeAdminLog("V3:unfreez by user-app ,PackageID=".$user->package_id." date==>" . $newDay . "-----" . $freezDate->id . "  userId==>" . $userId, null, $userId, "unfreeze days  and attach days");
            }

            $this->checkpendingdays($firstValidDay, $userId);

            return  $this->sendResponse(200, ['data' => ['user_days' => $this->getListUserDays($user->id, $user->membership_start)], 'message' => trans('User date update successfully completed')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }

    public function isOrderExist($id, $userId)
    {
        $order = Order::where('date_id', $id)->where('user_id', $userId)->get();
        if (!empty($order) && count($order) > 0) {
            Order::where('date_id', $id)->where('user_id', $userId)->update(['freeze' => 0]);
            return true;
        } else {
            return false;
        }
    }

    public function checkpendingdays($firstValidDay, $userId)
    {
        $countExistFreeze = UserDate::where('user_id', $userId)->where('date', '<', $firstValidDay)->where('freeze', 1)->get();
        if (!empty($countExistFreeze) && count($countExistFreeze) > 0) {
            $lastDay = UserDate::where('date', '>', $firstValidDay)->where('user_id', $userId)->where('freeze', 0)->orderBy('date', 'desc')->first();
            foreach ($countExistFreeze as $key => $freezDate) {
                $keys = $key + 1;
                $newDay = date("Y-m-d", strtotime("+" . $keys . " day", strtotime($lastDay->date)));
                $existDayE = UserDate::where('id', $freezDate->id)->where('user_id', $userId)->first();
                $existDayE->date           = $newDay;
                $existDayE->freeze         = 0;
                $existDayE->update_status  = 'admin';
                $existDayE->save();
            }
        }
    }

    public function requestCashBack(Request $request)
    {
        try {
            $user = $this->getUser($request);
            // $sumPoint=DB::table("user_point")->where('user_id',$user->id)->where('status',0)->sum('value');
            $decrement = DB::table("credit_user")->where('user_id', $user->id)->where("operation", "decrement")->sum("value");
            $increment = DB::table("credit_user")->where('user_id', $user->id)->where("operation", "increment")->sum("value");

            $sumPoint = floatval($increment) - ($decrement);

            if ($sumPoint <= 0) {
                return $this->sendResponse(205, ['data' => [], 'message' => trans('main.Your_inventory_is_not_enough')]);
            }

            //condition for min value accept cash back


            $beginOfDay = date('Y-m-d H:i:s', strtotime("midnight", time()));
            $endOfDay   = date('Y-m-d H:i:s', strtotime("tomorrow", time()) - 1);

            $limitation = DB::table('cash_back')->where('user_id', $user->id)->where('status', 3)->where('updated_at', '>=', $beginOfDay)->where('updated_at', '>=', $endOfDay)->count();

            if ($limitation >= 1) {
                return  $this->sendResponse(205, ['data' => [], 'message' => trans("main.cash_back_limit")]);
            }

            //send email for admin request cash back

            $existCashBack = DB::table('cash_back')->where('user_id', $user->id)->where('status', 0)->update(['status' => 3, 'updated_at' => date('Y-m-d H:i:s')]);

            // DB::table("user_point")->where('user_id',$user->id)->where('status',0)->update(['status'=>1]);
            //ned changing

            //$insertedId= DB::table('cash_back')->insert(['user_id'=>$user->id,'value'=>$sumPoint/2]);
            $cashBack = new CashBack();
            $cashBack->user_id = $user->id;
            //$cashBack->value=$sumPoint/2;
            $cashBack->value = $sumPoint;
            $cashBack->save();
            $this->logUserActivity("request Cash back value===>$sumPoint UserId==>" . $user->id, null, $user->deviceType);


            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new \App\Mail\CashBack($user, $cashBack));

            return  $this->sendResponse(200, ['data' => [], 'message' => trans('main.success_cash_back')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }
    public function setDays(Request $request)
    {
        //return $request->days;


        $device_type = $request->device_type;
        try {
            // $request->request->set('days',json_decode($request->days));
            $today =   date("Y-m-d");
            $date = strtotime("+3 day", strtotime($today));
            $firstDate = date("Y-m-d", $date);


            // $validator = Validator::make($request->all(),['days' => 'required|array','days.*'=>"date|after_or_equal:$firstDate"]);
            $validator = Validator::make($request->all(), ['days' => 'required|array', 'days.*' => "date"]);

            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            $user = $this->getUser($request);

            if (empty($user->package_id)) {
                return $this->sendResponse(400, ['data' => [], 'message' => "Package is not available"]);
            }


            $days = $request->days;

            $hasDupes = $this->hasDupes($days);

            if ($hasDupes) {
                return $this->sendResponse(400, ['data' => [], 'message' => trans('main.duplicates_days')]);
            }





            $this->logUserActivity("device_type===>$device_type   main request select days by user userId===>$user->id", $days);



            if (is_null($user->membership_start)) {
                $user->membership_start = $days[0];
                $user->save();
            }
            $duration = PackageDurations::where('id', $user->package_duration_id)->first();
            $count = optional($duration)->count_day;
            //$days=array_slice($days,0,$count);

            $countExistDays = DB::table('user_dates')->where('user_id', $user->id)->where('freeze', 0)->where('date', '>=', $user->membership_start)->pluck('id');
            $res = DB::table('user_dates')->whereNotIn('date', $days)->where('user_id', $user->id)->where('freeze', 0)->where('date', '>=', $firstDate)->pluck('id');
            $countDeleteDay = count($res);

            if ($countDeleteDay >= 1) {
                $orders =  DB::table('orders')->whereIn('date_id', $res)->pluck('id');
                DB::table('orders_addons')->whereIn('order_id', $orders)->delete();
                DB::table('orders')->whereIn('id', $orders)->delete();
                DB::table('user_dates')->whereIn('id', $res)->delete();
            }

            $this->logUserActivity("count deleted days count===> $countDeleteDay , userId==>$user->id", null, $user->deviceType);

            //  $remCountDay=$this->getRemDayPackage($user->id,$user->package_duration_id,$user->membership_start);

            $res = DB::table('user_dates')->select(['id'])->where('user_id', $user->id)->where('freeze', 0)->where('date', '>=', $user->membership_start)->where('date', '<', $firstDate)->count();

            if ($count >= 1) {

                $limit = $count - $res;
                if ($limit < 1) {
                    $limit = count($countExistDays) - $res;
                }

                $this->logUserActivity("count valid days for create :   count:===> $limit     userId==>$user->id ", null, $user->deviceType);
                $this->logUserActivity("select days by user userId===>$user->id", $days, $user->deviceType);
                foreach ($days as $day) {
                    if ($day >= $firstDate) {
                        $userDate = UserDate::firstOrNew(['user_id' => $user->id, 'date' => $day]);
                        if (is_null($userDate->id)) {
                            if ($limit >= 1) {
                                $limit -= 1;
                                $userDate->user_id       = $user->id;
                                $userDate->package_id    = $user->package_id;
                                $userDate->update_status = 'admin';
                                $userDate->date          = $day;
                                $userDate->save();
                            }
                        }
                    }
                }
                return $this->sendResponse(200, ['data' => ['user_days' => $this->getListUserDays($user->id, $user->membership_start)], 'message' => trans('main.user_date_update_success')]);
            }
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }
    public function getUserDays(Request $request)
    {
        $user = $this->getUser($request);
        $days = $this->getListUserDays($user->id,$user->membership_start) ;
        $cancelDay = $user->CancelDay ;
        if ( $cancelDay->isFreezed and $cancelDay->isAutoUnFreezed ) {
            $time = $cancelDay->freezed_ending_date->timestamp;
            foreach ( $days as $i => $day  ){
                if ( strtotime($day->date) >= $time )
                    $days[$i]->freeze = "0";
            }
        }
        return  $this->sendResponse(200,['data'=>['user_days'=>$days],'message'=>""]);
    }
    public function getPoints(Request $request)
    {
        $user = $this->getUser($request);

        $sum = $this->getSumPoint($user->id);
        return  $this->sendResponse(200, ['data' => ['sum_point' => $sum], 'message' => ""]);
    }
    public function changePassword(Request $request)
    {


        try {
            $validator = Validator::make($request->only(['password', 'current_password']), ['password' => 'required|min:8', 'current_password' => 'required|min:8']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            if (!(Hash::check($request->get('current_password'), auth()->user()->password))) {
                return $this->sendResponse(400, ['data' => [], 'message' => trans("main.password_not_valid")]);
            }


            $user = User::find(auth()->user()->id);
            if (isset($request->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
            }

            return $this->sendResponse(200, ['data' => ['user' => $user], 'message' => trans('main.update_personal_success')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }
    public function verify(Request $request)
    {
        $user = $this->getUser($request);
        $validator = Validator::make($request->all(), ['mobile_number' => 'required', 'is_verify_mobile' => 'required|in:0,1']);
        if ($validator->fails()) {
            return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
        }
        if ($user->mobile_number != $request->mobile_number) {
            return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.mobile_number_is_not_yours')]);
        }
        $is_verify_mobile = intval($request->is_verify_mobile);
        if ($is_verify_mobile == 1) {
            $user = User::find($user->id);
            $user->is_verify_mobile = 1;
            $user->save();
            return  $this->sendResponse(200, ['data' => ['user' => $user], 'message' => trans('main.user_verifed')]);
        }
        return  $this->sendResponse(200, ['data' => ['user' => $user], 'message' => ""]);
    }
    public function getNotificationList(Request $request)
    {
        $user = $this->getUser($request);
        $notifyList = DB::table('notifications')->select('*')->where('user_id', $user->id)->orWhere('user_id', 0)->orderBy('id', 'desc')->limit(100)->get()->toArray();
        if (count($notifyList) >= 1) {
            return  $this->sendResponse(200, ['data' => ['notifications' => $notifyList], 'message' => ""]);
        }
        return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.empty_notification_list')]);
    }
    public function getProgressList(Request $request)
    {
        $user = $this->getUser($request);

        if (isset($user)) {

            if (isset($user->membership_start)) {

                $countUsage = UserDate::where('user_id', $user->id)
                    ->where('date', '>=', $user->membership_start)
                    ->where('date', '<=', date("Y-m-d", time()))
                    ->where('freeze', 0)
                    ->where('isMealSelected', 1)->count();


                if ($countUsage <= 0) {
                    return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.empty_progress_list')]);
                } else {
                    $c = floor($countUsage / 7);
                    if ($c >= 1) {
                        $userProgress = DB::table('user_week_progress')->select('*')->where('user_id', $user->id)->orderBy('id', 'asc')->limit($c)->get()->toArray();
                        if (count($userProgress) >= 1) {
                            return  $this->sendResponse(200, ['data' => ['userProgress' => $userProgress], 'message' => ""]);
                        }
                    } else {
                        return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.empty_progress_list')]);
                    }
                }
            }
        }


        return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.empty_progress_list')]);
    }
    public function setProgress(Request $request)
    {
        try {
            $user = $this->getUser($request);
            $validator = Validator::make($request->all(), ['week_progress_id' => 'required', 'water' => 'required|digits_between:1,100', 'commitment' => 'required|digits_between:1,100', 'exercise' => 'required|digits_between:1,100']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            $userWeek =  UserWeekProgress::where('id', $request->week_progress_id)->where('user_id', $user->id)->first();

            if (isset($userWeek)) {

                $res = str_replace("Week ", "", $userWeek->titleEn);
                $res = str_replace("Progress ", "", $res);
                $re = intval($res);
                $minCountDay = $re * 7;

                $countUsage = UserDate::where('user_id', $user->id)
                    ->where('date', '>=', $user->membership_start)
                    ->where('date', '<=', date("Y-m-d", time()))
                    ->where('freeze', 0)
                    ->where('isMealSelected', 1)->count();
                $c = floor($countUsage / 7);
                if (intval($countUsage) >= $minCountDay) {
                    $userWeek->water = $request->water;
                    $userWeek->commitment = $request->commitment;
                    $userWeek->exercise = $request->exercise;
                    $sum = floatval($request->water) + floatval($request->commitment) + floatval($request->exercise);
                    $userWeek->average = floatval($sum / 3);
                    $userWeek->save();
                    $userProgress = DB::table('user_week_progress')->select('*')->where('user_id', $user->id)->orderBy('id', 'asc')->limit($c)->get()->toArray();
                    $this->logUserActivity("set progress user userId===>$user->id", null, $user->deviceType);

                    return  $this->sendResponse(200, ['data' => ['userProgress' => $userProgress], 'message' => ""]);
                }
                return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.Week_Progress_Not_Past')]);
            }
            return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.Week_Progress_Not_Past')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }

    public function helpMeChoosePackage(Request $request)
    {

        try {

            $lisPackage = Package::whereIn('titleEn', ['Full Plus', 'Full', 'Full Plus with Drinks', 'Fitness', 'LoCarb', 'Maternity', 'Kids', 'Diabetes', 'Weight Gain', 'Vegan', 'Detox', 'Keto', 'Lunch Meal', 'Dinner Meal', 'Breakfast and Lunch', 'Lunch and Dinner', 'Focus Breakfast', 'Youth Breakfast', '4 High Protein Meals', '3 High Protein Meals'])->where('active', 1)->where('show_mobile', 1)->get()->keyBy('titleEn')->toArray();
            $questionCat1 = DB::table('questions')->where('catName', 'Gradual_Weight_Loss')->select(['id', 'titleEn', 'titleAr', 'yes_pack', 'no_pack'])->get()->toArray();
            $questionCat2 = DB::table('questions')->where('catName', 'Fast_Weight_Loss')->select(['id', 'titleEn', 'titleAr', 'yes_pack', 'no_pack'])->get()->toArray();
            $questionCat3 = DB::table('questions')->where('catName', 'Weight_Gain')->select(['id', 'titleEn', 'titleAr', 'yes_pack', 'no_pack'])->get()->toArray();

            $final1 = [];
            foreach ($questionCat1 as $item) {
                $yesPackName = explode(',', $item->yes_pack);
                $noPackName  = explode(',', $item->no_pack);

                $pac['yes']      = $this->selectPack($lisPackage, $yesPackName);
                $pac['no']       = $this->selectPack($lisPackage, $noPackName);;
                $pac['question'] = $item;
                $final1['Gradual_Weight_Loss'][$item->id] = $pac;
            }

            $final2 = [];
            foreach ($questionCat2 as $item2) {
                $yesPackName = explode(',', $item2->yes_pack);
                $noPackName  = explode(',', $item2->no_pack);

                $pac['yes']      = $this->selectPack($lisPackage, $yesPackName);
                $pac['no']       = $this->selectPack($lisPackage, $noPackName);;
                $pac['question'] = $item2;
                $final2['Fast_Weight_Loss'][$item2->id] = $pac;
            }

            $final3 = [];
            foreach ($questionCat3 as $item3) {
                $yesPackName = explode(',', $item3->yes_pack);
                $noPackName  = explode(',', $item3->no_pack);

                $pac['yes']      = $this->selectPack($lisPackage, $yesPackName);
                $pac['no']       = $this->selectPack($lisPackage, $noPackName);;
                $pac['question'] = $item3;
                $final3['Weight_Gain'][$item3->id] = $pac;
            }

            $final4 = [];
            $specialArray = ['Maternity', 'Kids', 'Diabetes', 'LoCarb'];
            $final4['Special_Cases'] = $this->selectPack($lisPackage, $specialArray);

            $final5 = [];
            $singelMeal   = ['Lunch Meal', 'Dinner Meal', 'Breakfast and Lunch', 'Lunch and Dinner', 'Focus Breakfast', 'Youth Breakfast'];
            $final5['Single_Meals']  = $this->selectPack($lisPackage, $singelMeal);

            $res = array_merge($final1, $final2, $final3, $final4, $final5);
            if (count($res) >= 1) {
                return  $this->sendResponse(200, ['data' => ['questionsAndPackage' => $res], 'message' => ""]);
            }
            return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.empty_progress_list')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }
    public function temp(Request $request)
    {











        dd('ss');

        $device_type = "android";
        try {
            // $request->request->set('days',json_decode($request->days));
            $today =   date("Y-m-d");
            $date = strtotime("+3 day", strtotime($today));
            $firstDate = date("Y-m-d", $date);


            // $validator = Validator::make($request->all(),['days' => 'required|array','days.*'=>"date|after_or_equal:$firstDate"]);
            //            $validator = Validator::make($request->all(),['days' => 'required|array','days.*'=>"date"]);
            ////
            ////            if ($validator->fails()) {
            ////                return $this->sendResponse(400,['data'=>[],'message'=>$validator->errors()->first()]);
            ////            }
            $user = User::whereId(6045)->first();
            $days = ["2020-6-13", "2020-6-14", "2020-6-15", "2020-6-16", "2020-6-17", "2020-6-18", "2020-6-20", "2020-6-21", "2020-6-22", "2020-6-23", "2020-6-24", "2020-6-25", "2020-6-26", "2020-6-27", "2020-6-28", "2020-6-29", "2020-6-30", "2020-7-1", "2020-7-2", "2020-7-3", "2020-7-4", "2020-7-5", "2020-7-6", "2020-7-7", "2020-7-8", "2020-7-9", "2020-7-10", "2020-7-11", "2020-7-14", "2020-7-15", "2020-7-16", "2020-7-17", "2020-7-18", "2020-7-19", "2020-7-20", "2020-7-21", "2020-7-22", "2020-7-23", "2020-7-24", "2020-7-25", "2020-7-26", "2020-7-27", "2020-7-28", "2020-7-29", "2020-7-30", "2020-7-31", "2020-8-1", "2020-8-2", "2020-8-3", "2020-8-4", "2020-8-5", "2020-8-6", "2020-8-7", "2020-8-8", "2020-8-9", "2020-8-10", "2020-8-11", "2020-8-12", "2020-8-13", "2020-8-14", "2020-8-15", "2020-8-16", "2020-8-17", "2020-8-18", "2020-8-19", "2020-8-20"];

            $hasDupes = $this->hasDupes($days);

            if ($hasDupes) {
                return $this->sendResponse(400, ['data' => [], 'message' => trans('main.duplicates_days')]);
            }





            $this->logUserActivity("device_type===>$device_type   main request select days by user userId===>$user->id", $days);



            if (is_null($user->membership_start)) {
                $user->membership_start = $days[0];
                $user->save();
            }
            $duration = PackageDurations::where('id', $user->package_duration_id)->first();
            $count = optional($duration)->count_day;
            //$days=array_slice($days,0,$count);

            $countExistDays = DB::table('user_dates')->where('user_id', $user->id)->where('freeze', 0)->where('date', '>=', $user->membership_start)->pluck('id');
            $res = DB::table('user_dates')->whereNotIn('date', $days)->where('user_id', $user->id)->where('freeze', 0)->where('date', '>=', $firstDate)->pluck('id');
            $countDeleteDay = count($res);


            if ($countDeleteDay >= 1) {
                $orders =  DB::table('orders')->whereIn('date_id', $res)->pluck('id');
                DB::table('orders_addons')->whereIn('order_id', $orders)->delete();
                DB::table('orders')->whereIn('id', $orders)->delete();
                DB::table('user_dates')->whereIn('id', $res)->delete();
            }

            $this->logUserActivity("count deleted days count===> $countDeleteDay , userId==>$user->id", null, $user->deviceType);

            //  $remCountDay=$this->getRemDayPackage($user->id,$user->package_duration_id,$user->membership_start);

            $res = DB::table('user_dates')->select(['id'])->where('user_id', $user->id)->where('freeze', 0)->where('date', '>=', $user->membership_start)->where('date', '<', $firstDate)->count();

            if ($count >= 1) {

                $limit = $count - $res;
                if ($limit < 1) {
                    $limit = count($countExistDays) - $res;
                }

                $this->logUserActivity("count valid days for create :   count:===> $limit     userId==>$user->id ", null, $user->deviceType);
                $this->logUserActivity("select days by user userId===>$user->id", $days, $user->deviceType);
                foreach ($days as $day) {
                    if ($day >= $firstDate) {
                        $userDate = UserDate::firstOrNew(['user_id' => $user->id, 'date' => $day]);
                        if (is_null($userDate->id)) {
                            if ($limit >= 1) {
                                $limit -= 1;
                                $userDate->user_id = $user->id;
                                $userDate->date = $day;
                                $userDate->save();
                            }
                        }
                    }
                }
                dd('aa');
                return $this->sendResponse(200, ['data' => ['user_days' => $this->getListUserDays($user->id, $user->membership_start)], 'message' => trans('main.user_date_update_success')]);
            } else {
                dd($count, 1, $duration, $user->package_duration_id);
            }
        } catch (\Exception $e) {
            dd('ss');
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }

        dd('ssss');




        $date = UserDate::whereId(696478)->with(["package", 'user.package.meals'])->first();
        $cats = Package::with(["categories" => function ($r) {
            $r->where('active', 1);
        }])->whereId(60)->first();
        dd(is_null($date->package));
        dd($date, $cats);
        //        $date=$request->day;
        //        $user=$this->getUser($request);
        //        $userDate=UserDate::where("user_id",$user->id)->where("date",$date)->where('freeze',0)->orderBy('id','desc')->first();
        //
        //        try{
        //            $arraYorder=[];
        //            if(isset($userDate)){
        //                $orders=Order::where('date_id',$userDate->id)->where('user_id',$user->id)->get();
        //                if(isset($orders)){
        //                    foreach ($orders as $order) {
        //                        $addOnIds=DB::table('orders_addons')->where('order_id',$order->id)->pluck('addon_id');
        //
        //                        $nOrder=Order::with(['meal.categories.items.addons','meal.categories'=>function($r)use($order){
        //                            $r->where('categories.id',$order->category_id);
        //                        },'meal.categories.items'=>function($e)use($order){
        //                            $e->where('items.id',$order->item_id);
        //                        },'meal.categories.items.addons'=>function($f)use($order,$addOnIds){
        //                            $f->whereIn('addons.id',$addOnIds);
        //                        },'meal'=>function($r){
        //                            $r->orderBy('ordering','asc');
        //                        }])->where('id',$order->id)->first();
        //                        array_push($arraYorder,$nOrder->meal);
        //
        //                    }
        //                }
        //
        //
        //                if(count($arraYorder)>=1){
        //                    $newArr=[];
        //                    foreach (collect($arraYorder)->sortBy('ordering')->all() as $item) {
        //                        array_push($newArr,$item);
        //                    }
        //                    return  $this->sendResponse(200,['data'=>['meals'=>$newArr],'message'=>""]);
        //                }
        //                return  $this->sendResponse(205,['data'=>[],'message'=>trans("main.empty_reserved_item_in_day")]);
        //
        //
        //            }
        //
        //        }catch (\Exception $e){
        //            return $this->sendResponse(500,['data'=>[],'message'=>trans('main.Server_Error')]);
        //
        //        }
        //





        dd('ss');







        $user = User::find(4280);
        $days = ["2019-10-23", "2019-10-24", "2019-10-25", "2019-10-26", "2019-10-27", "2019-10-28", "2019-10-29", "2019-10-30", "2019-10-31", "2019-11-01", "2019-11-02", "2019-11-03", "2019-11-04", "2019-11-05", "2019-11-06", "2019-11-07", "2019-11-08", "2019-11-09", "2019-11-10", "2019-11-11", "2019-11-12", "2019-11-13", "2019-11-14", "2019-11-15", "2019-11-16"];


        $today =   date("Y-m-d");
        $date = strtotime("+3 day", strtotime($today));
        $firstDate = date("Y-m-d", $date);



        $res = DB::table('user_dates')->whereNotIn('date', $days)->where('user_id', $user->id)->where('freeze', 0)->where('date', '>=', $firstDate)->pluck('id');
        if (count($res) >= 1) {
            $orders =  DB::table('orders')->whereIn('date_id', $res)->pluck('id');
            DB::table('orders_addons')->whereIn('order_id', $orders)->delete();
            DB::table('orders')->whereIn('id', $orders)->delete();
            DB::table('user_dates')->whereIn('id', $res)->delete();
        }

        //  $remCountDay=$this->getRemDayPackage($user->id,$user->package_duration_id,$user->membership_start);

        $duration = PackageDurations::where('id', $user->package_duration_id)->first();
        $count = optional($duration)->count_day;

        $days = array_slice($days, 0, $count);
        dd($days, count($days));


        $res = DB::table('user_dates')->select(['id'])->where('user_id', $user->id)->where('freeze', 0)->where('date', '>=', $user->membership_start)->where('date', '<', $firstDate)->count();

        if ($count >= 1) {
            $limit = $count - $res;

            foreach ($days as $day) {
                if ($day >= $firstDate) {
                    $userDate = UserDate::firstOrNew(['user_id' => $user->id, 'date' => $day]);
                    if (is_null($userDate->id)) {
                        if ($limit >= 1) {
                            $limit -= 1;
                            $userDate->user_id = $user->id;
                            $userDate->date = $day;
                            $userDate->save();
                        }
                    }
                }
            }
        }

        $res = UserDate::where('user_id', $user->id)->where('date', '>=', $user->membership_start)->get();
        dd($res);



        dd(trans("main.update_information"));
        dd(event(new Sms("6566444569", "test dietfix.net")));

        dd('d');

        $user = User::find(4001);
        $day = "2019-10-09";


        $days = DB::table('user_dates')->where('date', $day)->where('user_id', $user->id)->where('isMealSelected', 0)->where('freeze', 0)->select('*')->get();
        $package = Package::where('id', $user->package_id)->where('show_mobile', 1)->where('active', 1)->with(['meals' => function ($q) {
            $q->where('active', 1);
        }])->first();



        if (!isset($package) || empty($package)) {
            return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.User Package Is Not Valid')]);
        }

        foreach ($days as $dayItem) {

            $date = $dayItem;
            $unixTimestamp = strtotime($date->date);
            $dayOfWeek = date("l", $unixTimestamp);
            $dayNumber = date("d", $unixTimestamp);

            $validDayName = $this->selectWeek($user->membership_start, $dayOfWeek, $dayNumber);

            $day = $this->getDayId($validDayName);

            $mainDay = $this->getDayId($dayOfWeek);


            $userDate = UserDate::find($dayItem->id);

            if (isset($dayItem->package_id)) {
                $packId = $dayItem->package_id;
            } else {
                $packId = $user->package_id;
            }



            $cats = Package::with(["categories" => function ($r) {
                $r->where('active', 1);
            }])->whereId($packId)->first();




            if (isset($cats)) {
                $catId = $cats->categories->pluck('id')->toArray();
            } else {
                $catId = [];
            }

            // return $package->meals;

            if (isset($package->meals)) {
                foreach ($package->meals as $meal) {




                    $res = DB::table('meals')
                        ->join('categories', 'meals.id', '=', 'categories.meal_id')
                        ->join('items', 'categories.id', '=', 'items.category_id')
                        ->join('items_days', 'items.id', '=', 'items_days.item_id')
                        ->where('items_days.day_id', $day->id)
                        ->where('categories.active', 1)
                        ->where('items.active', 1)
                        ->where('meals.id', $meal->id)
                        ->whereIn('categories.id', $catId)
                        ->orderBy('items.rating', 'desc')
                        ->select(['items.id as item_id', 'categories.id as category_id'])->first();

                    if (isset($res) && !empty($res)) {
                        $order = Order::firstOrNew(['user_id' => $user->id, 'day_id' => $mainDay->id, 'meal_id' => $meal->id, 'category_id' => $res->category_id, 'item_id' => $res->item_id, 'date_id' => $dayItem->id]);
                        $order->approved = 1;
                        $order->day_id = $mainDay->id;
                        $order->user_id = $user->id;
                        $order->meal_id = $meal->id;
                        $order->category_id = $res->category_id;
                        $order->item_id = $res->item_id;
                        $order->date_id = $dayItem->id;

                        $portion = $this->selectPortion($user->id, $meal->id, $packId);
                        if (isset($portion)) {
                            $order->portion_id = $portion;
                        }



                        $order->save();
                        $userDate->isMealSelected = 1;
                        //update pkg id if empty
                        if(empty($userDate->package_id) && !empty($packId)){
                        $userDate->package_id=$packId;
                        }
                        //end
                        $userDate->save();
                    } else {
                        $res = DB::table('meals')
                            ->join('categories', 'meals.id', '=', 'categories.meal_id')
                            ->join('items', 'categories.id', '=', 'items.category_id')
                            ->join('items_days', 'items.id', '=', 'items_days.item_id')
                            ->where('items_days.day_id', $mainDay->id)
                            ->where('categories.active', 1)
                            ->where('items.active', 1)
                            ->whereIn('categories.id', $catId)
                            ->orderBy('items.rating', 'desc')
                            ->select(['items.id as item_id', 'categories.id as category_id'])->first();


                        if (isset($res)) {
                            $order = Order::firstOrNew(['user_id' => $user->id, 'day_id' => $mainDay->id, 'meal_id' => $meal->id, 'category_id' => $res->category_id, 'item_id' => $res->item_id, 'date_id' => $dayItem->id]);
                            $order->approved = 1;
                            $order->day_id = $mainDay->id;
                            $order->user_id = $user->id;
                            $order->meal_id = $meal->id;
                            $order->category_id = $res->category_id;
                            $order->item_id = $res->item_id;
                            $order->date_id = $dayItem->id;

                            $portion = $this->selectPortion($user->id, $meal->id, $packId);
                            if (isset($portion)) {
                                $order->portion_id = $portion;
                            }


                            $order->save();
                            $userDate->isMealSelected = 1;
                            //update pkg id if empty
                            if(empty($userDate->package_id) && !empty($packId)){
                            $userDate->package_id=$packId;
                            }
                            //end
                            $userDate->save();
                        }
                    }
                }
            }
        }
        $days = $this->getListUserDays($user->id, $user->membership_start);
    }
    public function checkValidMobileNumber(Request $request)
    {

        $validator = Validator::make($request->only(['mobile_number']), ['mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8']);
        if ($validator->fails()) {
            return $this->sendResponse(400, ['message' => $validator->errors()->first(), 'data' => []]);
        }
        return  $this->sendResponse(200, ['data' => ['validMobile' => true], 'message' => ""]);
    }
    public function restPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->only(['password', 'password_confirmation', 'mobile_number']), ['password' => 'required|confirmed|min:6', 'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            $user = User::where('mobile_number', $request->mobile_number)->first();
            if (isset($user)) {
                $user->password = bcrypt($request->password);
                $user->api_token = hash('sha256', Str::random(60));
                $user->save();
                return $this->sendResponse(200, ['data' => ['user' => $user], 'message' => trans('main.reset_password_successfully')]);
            } else {
                return $this->sendResponse(400, ['data' => [], 'message' => trans('main.mobile_number_is_not_valid')]);
            }
        } catch (\Exception $r) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }

    /**
     * @param $user
     * @return int|mixed
     */
    private function getSumPoint($id)
    {
        $inc = DB::table('credit_user')->where('operation', 'increment')->where('user_id', $id)->sum('value');
        $dec = DB::table('credit_user')->where('operation', 'decrement')->where('user_id', $id)->sum('value');
        $sum = $inc - $dec;
        if ($sum < 0) {
            $sum = 0;
        }
        return $sum;
    }
    //deprecate
    public function setDay(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['day' => 'required|date']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }
            $user = $this->getUser($request);

            if (empty($user->package_id) || empty($user->membership_start)) {
                return $this->sendResponse(400, ['data' => [], 'message' => "Package is not available"]);
            }

            $today_date1 = date("Y-m-d");
            $today_date2 = strtotime("+3 day", strtotime($today_date1));
            $today_date3 = date("Y-m-d", $today_date2);

            //if date is les than starting date
            if (!empty($user->membership_start) && !empty($request->day)) {
                $date1  = Carbon::createFromFormat('Y-m-d', $request->day);
                $date2  = Carbon::createFromFormat('Y-m-d', $user->membership_start);
                $reDate = $date1->lt($date2);
                if (!empty($reDate)) {
                    return $this->sendResponse(400, ['data' => [], 'message' => "Chosen date is invalid-1"]);
                }
            }

            //if date is les than starting date
            if (!empty($user->membership_start) && !empty($request->day)) {
                $date1  = Carbon::createFromFormat('Y-m-d', $request->day);
                $date2  = Carbon::createFromFormat('Y-m-d', $today_date3);
                $reDate = $date1->lte($date2);
                if (!empty($reDate)) {
                    return $this->sendResponse(400, ['data' => [], 'message' => "Chosen date is invalid-2"]);
                }
            }



            $day          = $request->day;
            $userDateInfo = UserDate::where(['id' => $request->old_day_id])->first();
            $userDate = UserDate::firstOrNew(['user_id' => $user->id, 'date' => $day]);
            if (is_null($userDate->id)) {
                $this->logUserActivity("New Date is added-->" . $user->id . '(' . $day . ')', null, $user->deviceType);
                $userDate->package_id = $user->package_id;
                $userDate->update_status = 'user';
                $userDate->save();
            }
            //remove old date - imtiaz
            if (!empty($request->old_day_id)) {
                UserDate::where('user_id', $user->id)->where('id', $request->old_day_id)->delete();
                $this->logUserActivity("Old Date is removed-->" . $user->id, null, $user->deviceType);
            }

            return  $this->sendResponse(200, ['data' => ['user_days' => $this->getListUserDays($user->id), 'day_id' => $userDate->id], 'message' => trans('main.user_date_update_success')]);
        } catch (\Exception $e) {

            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }
    public function getTransactionList(Request $request)
    {
        $user = $this->getUser($request);
        $payments = DB::table("payments")
            ->join("packages", "payments.package_id", "=", "packages.id")
            ->join("package_duration", "payments.package_duration_id", "=", "package_duration.id")
            ->where('user_id', $user->id)
            ->select(['payments.starting_date', 'package_duration.count_day', 'packages.titleEn', 'packages.titleAr', 'package_duration.titleEn as packageDurationTitleEn', 'package_duration.titleAr as packageDurationTitleAr', 'payments.id', 'paymentID', 'ref_id', 'paid', 'package_duration_id', 'PaidCurrencyValue', 'DueValue', 'total', 'PaidCurrency', 'Currency', 'CustomerServiceCharge', 'type', 'status', 'payments.created_at', 'payments.updated_at'])
            ->orderBy('id', 'desc')->get();
        //        $payments=Payment::where('user_id',$user->id)->with(['package.packageDurations'=>function($r){
        //            $r->keyBy('id');
        //        },'packaged'])->select(['id','paymentID','ref_id','paid','package_id','package_duration_id','PaidCurrencyValue','DueValue','total','PaidCurrency','Currency','CustomerServiceCharge','type','status','created_at','updated_at'])->orderBy('id','desc')->limit(100)->get();
        if ($payments->count() <= 0) {
            return  $this->sendResponse(205, ['data' => [], 'message' => trans("main.empty_payment_list")]);
        }
        $result = [];
        foreach ($payments as $item) {
            $new['id'] = $item->id;
            $new['paymentID'] = $item->paymentID;
            $new['ref_id'] = $item->ref_id;
            $new['paid'] = $item->paid;
            $new['PaidCurrencyValue'] = $item->PaidCurrencyValue;
            $new['DueValue'] = $item->DueValue;
            $new['total'] = $item->total;
            $new['PaidCurrency'] = $item->PaidCurrency;
            $new['Currency'] = $item->Currency;
            $new['CustomerServiceCharge'] = $item->CustomerServiceCharge;
            $new['type'] = $item->type;
            if ($item->status == 0) {
                $new['status'] = "init";
            } elseif ($item->status == 1) {
                $new['status'] = "success";
            } elseif ($item->status == 2) {
                $new['status'] = "failed";
            }
            $new['created_at'] = $item->created_at;
            $new['updated_at'] = $item->updated_at;
            $new['packageTitleEn'] = $item->titleEn;
            $new['packageTitleAr'] = $item->titleAr;
            $new['packageDurationTitleEn'] = $item->packageDurationTitleEn;
            $new['packageDurationTitleAr'] = $item->packageDurationTitleAr;
            $new['count_day'] = $item->count_day;
            $new['starting_date'] = $item->starting_date;
            array_push($result, $new);
        }
        return  $this->sendResponse(200, ['data' => ['payment_list' => $result], 'message' => ""]);
    }
    public function getValidDay()
    {
        $today =   date("Y-m-d");
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDay = date("Y-m-d", $date);
        return $firstValidDay;
    }
    public function getOrderUser(Request $request)
    {
        $date = $request->day;
        $user = $this->getUser($request);
        $userDate = UserDate::where("user_id", $user->id)->where("date", $date)->where('freeze', 0)->orderBy('id', 'desc')->first();

        try {
            $arraYorder = [];
            if (isset($userDate)) {
                $orders = Order::where('date_id', $userDate->id)->where('user_id', $user->id)->get();
                if (isset($orders)) {
                    foreach ($orders as $order) {
                        $addOnIds = DB::table('orders_addons')->where('order_id', $order->id)->pluck('addon_id');

                        $nOrder = Order::with(['meal.categories.items.addons', 'meal.categories' => function ($r) use ($order) {
                            $r->where('categories.id', $order->category_id);
                        }, 'meal.categories.items' => function ($e) use ($order) {
                            $e->where('items.id', $order->item_id);
                        }, 'meal.categories.items.addons' => function ($f) use ($order, $addOnIds) {
                            $f->whereIn('addons.id', $addOnIds);
                        }, 'meal' => function ($r) {
                            $r->orderBy('meals.ordering', 'asc');
                        }])->where('id', $order->id)->first();
                        array_push($arraYorder, $nOrder->meal);
                    }
                }
                if (count($arraYorder) >= 1) {
                    $newArr = [];
                    foreach (collect($arraYorder)->sortBy('ordering')->all() as $item) {
                        array_push($newArr, $item);
                    }
                    return  $this->sendResponse(200, ['data' => ['meals' => $newArr], 'message' => ""]);
                }
                return  $this->sendResponse(205, ['data' => [], 'message' => trans("main.empty_reserved_item_in_day")]);
            }
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => trans('main.Server_Error')]);
        }
    }

    public function getListValidFoodForRating(Request $request)
    {
        $user = $this->getUser($request);
        $userDate = UserDate::where('user_id', $user->id)->where('date', '<=', date('Y-m-d', time()))->pluck('id');
        if (count($userDate) >= 1) {
            $itemId = Order::whereIn('date_id', $userDate)->pluck('item_id');
            $items =   Item::whereIn('id', $itemId)->select(['*'])->where('active', 1)->paginate(20);
            if ($items->count() <= 0) {
                return  $this->sendResponse(205, ['data' => [], 'message' => trans("main.empty_items_list")]);
            }
            return  $this->sendResponse(200, ['data' => ['prefix_photo_item' => url(env('APP_URL') . '/media/items/'), 'items' => $items], 'message' => ""]);
        }
        return  $this->sendResponse(205, ['data' => [], 'message' => trans("main.empty_items_list")]);
    }


    private function selectPack($listPack, $arrayPak)
    {

        $array = [];
        foreach ($arrayPak as $item) {
            if (array_key_exists($item, $listPack)) {
                array_push($array, $listPack[$item]);
            }
        }
        return $array;
    }
    public function selectPortion($userId, $mealId, $packageId)
    {
        $res = DB::table("portion_log")->where("package_id", $packageId)->where('user_id', $userId)->where('meal_id', $mealId)->select(['portion'])->first();
        return optional($res)->portion;
    }
    private function getListUserDays($userId, $statingDate = null)
    {
        $udays = [];

        $res = DB::table('user_dates')->select(['id', 'date', 'freeze', 'isMealSelected'])->where('user_id', $userId);
        if ($statingDate != null) {
            $res = $res->where('date', '>=', $statingDate);
        }
        return $res->orderBy('date', 'asc')->get()->toArray();
    }
    private function updateAvgItem($id)
    {
        $res = DB::table('rating_food')->where('item_id', $id)->avg('rating');
        $res = floatval($res);
        return   DB::table('items')->whereId($id)->update(['rating' => $res]);
    }
    private function getDayId($dayName)
    {
        return  DB::table("days")->where('titleEn', $dayName)->select(['id'])->first();
    }
    private function getRemDayPackage($id, $packageDurationId, $date)
    {

        $res = DB::table('user_dates')->select(['id'])->where('user_id', $id)->where('freeze', 0);
        if ($date != null) {
            $res = $res->where('date', '>=', $date);
        }
        $count = $res->count();

        $packageDuration =  PackageDurations::where('id', $packageDurationId)->first();
        if (isset($packageDuration)) {
            if ($count == 0) {
                return $packageDuration->count_day;
            } else {
                return   intval($packageDuration->count_day) - $count;
            }
        }
        return 0;
    }
    private function updateAddons($orderId, $addons)
    {
        DB::table('orders_addons')->where('order_id', $orderId)->delete();
        if (count($addons) >= 1) {
            foreach ($addons as $item) {
                DB::table('orders_addons')->insert(['order_id' => $orderId, 'addon_id' => $item]);
            }
        }
    }
    private function itemDay($userId, $dayId)
    {
        return DB::table('orders')->where('day_id', $dayId)->where('user_id', $userId)->select('*')->get()->toArray();
    }
    private function getPackageCountAndReferralPoint($userId, $packageDurationId = null, $membership_start = null)
    {

        $sumPoint = $this->getSumPoint($userId);
        $sumCashBak = $this->getSumCashBack($userId);

        //change logic count day package
        if (isset($packageDurationId)) {
            // $pack=PackageDurations::find($packageDurationId);
            //  $count=intval($pack->count_day);
        } else {
            $count = 0;
        }




        if ($membership_start != null) {
            $count = UserDate::where('user_id', $userId)->where('date', '>=', $membership_start)->count();
        } elseif (isset($packageDurationId)) {
            $pack = PackageDurations::find($packageDurationId);
            $count = intval($pack->count_day);
        } else {
            $count = 0;
        }



        return [$sumPoint, $count, $sumCashBak];
    }
    private function getSumCashBack($userId)
    {
        return  DB::table("cash_back")->where('user_id', $userId)->where('status', 1)->sum('value');
    }
    private function selectWeek($startingDay, $dayName, $dayNumber)
    {

        $now = time();
        $your_date = strtotime($startingDay);
        $daysWeek1 = [1, 2, 3, 4, 5, 6, 7, 15, 16, 17, 18, 19, 20, 21, 29, 30, 31];
        $daysWeek2 = [8, 9, 10, 11, 12, 13, 14, 22, 23, 24, 25, 26, 27, 28];
        if (in_array($dayNumber, $daysWeek1)) {
            return $dayName;
        } else {
            return $dayName . " 2";
        }

        //        $datediff = $now - $your_date;
        //        $countDay= round($datediff / (60 * 60 * 24));
        //        if($countDay<=7){
        //            return $dayName;
        //        }else{
        //            $val=ceil($countDay/7);
        //            if($val%2==0){
        //               return $dayName." 2";
        //            }
        //            return $dayName;
        //        }

    }
    private function getCountRemDaysUser($userId, $memberShipStart = null)
    {
        $today = date('Y-m-d');
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDay = date("Y-m-d", $date);
        $res = UserDate::where('user_id', $userId)->where('date', '>=', $today);
        return $res->count();
    }

    private function hasDupes($array)
    {
        return count($array) !== count(array_unique($array));
    }

    /////////////////////////////////////////////////////////////////////Imtiaz////////////////////////////////////////////
    public function chooseRandomFoodByDate(Request $request)
    {

        try {
            $user = $this->getUser($request);
            $days = DB::table('user_dates')->where('date', '=', $request->day)->where('user_id', $user->id)->where('isMealSelected', 0)->where('freeze', 0)->select('*')->get();
            $package = Package::where('id', $user->package_id)->where('show_mobile', 1)->where('active', 1)->with(['meals' => function ($q) {
                $q->where('active', 1);
            }])->first();

            if (!isset($package) || empty($package)) {
                return  $this->sendResponse(205, ['data' => [], 'message' => trans('main.user_not_subscribe')]);
            }

            foreach ($days as $dayItem) {

                $date = $dayItem;
                $unixTimestamp = strtotime($date->date);
                $dayOfWeek = date("l", $unixTimestamp);
                $dayNumber = date("d", $unixTimestamp);

                $validDayName = $this->selectWeek($user->membership_start, $dayOfWeek, $dayNumber);

                $day = $this->getDayId($validDayName);

                $mainDay = $this->getDayId($dayOfWeek);


                $userDate = UserDate::find($dayItem->id);

                if (isset($dayItem->package_id)) {
                    $packId = $dayItem->package_id;
                } else {
                    $packId = $user->package_id;
                }



                $cats = Package::with(["categories" => function ($r) {
                    $r->where('active', 1);
                }])->whereId($packId)->first();



                if (isset($cats)) {
                    $catId = $cats->categories->pluck('id')->toArray();
                } else {
                    $catId = [];
                }

                // return $package->meals;

                if (isset($package->meals)) {
                    foreach ($package->meals as $meal) {

                        $res = DB::table('meals')
                            ->join('categories', 'meals.id', '=', 'categories.meal_id')
                            ->join('items', 'categories.id', '=', 'items.category_id')
                            ->join('items_days', 'items.id', '=', 'items_days.item_id')
                            ->where('items_days.day_id', $day->id)
                            ->where('categories.active', 1)
                            ->where('items.active', 1)
                            ->where('meals.id', $meal->id)
                            ->whereIn('categories.id', $catId)
                            ->orderBy('items.rating', 'desc')
                            ->select(['items.id as item_id', 'categories.id as category_id'])->first();

                        if (isset($res) && !empty($res)) {
                            $order = Order::firstOrNew(['user_id' => $user->id, 'day_id' => $mainDay->id, 'meal_id' => $meal->id, 'category_id' => $res->category_id, 'item_id' => $res->item_id, 'date_id' => $dayItem->id]);
                            $order->approved = 1;
                            $order->day_id = $mainDay->id;
                            $order->user_id = $user->id;
                            $order->meal_id = $meal->id;
                            $order->category_id = $res->category_id;
                            $order->item_id = $res->item_id;
                            $order->date_id = $dayItem->id;

                            $portion = $this->selectPortion($user->id, $meal->id, $packId);
                            if (isset($portion)) {
                                $order->portion_id = $portion;
                            }


                            $order->save();
                            $userDate->isMealSelected = 1;
                            //update pkg id if empty
                            if(empty($userDate->package_id) && !empty($packId)){
                            $userDate->package_id=$packId;
                            }
                            //end
                            $userDate->save();

                            $this->logUserActivity("V3 choseRandom food  for date " . $userDate->date . " ,PackageID=".$userDate->package_id." and meal==>" . $meal->id . "  userId==>" . $user->id, null, $user->deviceType);
                        } else {
                            $res = DB::table('meals')
                                ->join('categories', 'meals.id', '=', 'categories.meal_id')
                                ->join('items', 'categories.id', '=', 'items.category_id')
                                ->join('items_days', 'items.id', '=', 'items_days.item_id')
                                ->where('items_days.day_id', $mainDay->id)
                                ->where('categories.active', 1)
                                ->where('items.active', 1)
                                ->whereIn('categories.id', $catId)
                                ->orderBy('items.rating', 'desc')
                                ->select(['items.id as item_id', 'categories.id as category_id'])->first();

                            if (isset($res)) {
                                $order = Order::firstOrNew(['user_id' => $user->id, 'day_id' => $mainDay->id, 'meal_id' => $meal->id, 'category_id' => $res->category_id, 'item_id' => $res->item_id, 'date_id' => $dayItem->id]);
                                $order->approved = 1;
                                $order->day_id = $mainDay->id;
                                $order->user_id = $user->id;
                                $order->meal_id = $meal->id;
                                $order->category_id = $res->category_id;
                                $order->item_id = $res->item_id;
                                $order->date_id = $dayItem->id;

                                $portion = $this->selectPortion($user->id, $meal->id, $packId);
                                if (isset($portion)) {
                                    $order->portion_id = $portion;
                                }


                                $order->save();
                                $userDate->isMealSelected = 1;
                                //update pkg id if empty
                                if(empty($userDate->package_id) && !empty($packId)){
                                $userDate->package_id=$packId;
                                }
                                //end
                                $userDate->save();
                                $this->logUserActivity("V3 choseRandom food  for date " . $userDate->date . " ,PackageID=".$userDate->package_id." and meal==>" . $meal->id . "  userId==>" . $user->id, null, $user->deviceType);
                            }
                        }
                    }
                }
            }
            $days = $this->getListUserDays($user->id, $user->membership_start);
            return  $this->sendResponse(200, ['data' => ['user_days' => $days], 'message' => trans('main.user_date_update_success')]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, ['data' => [], 'message' => $e->getMessage()]);
        }
    }

    /////////////////////////////////////////////////////////////////////Imtiaz End///////////////////////////////////////////////


    private function getValidRenewPackageRequestDate($userid)
    {

        $lastDayDate  = UserDate::where('user_id', $userid)->where('freeze', 0)->orderBy('date', 'desc')->select(['date'])->first();


        $todayDate = date('Y-m-d');

        if (!empty($lastDayDate->date) && $lastDayDate->date > $todayDate) {


            $days = $this->daysFromDates($todayDate, $lastDayDate->date);

            if ($days >= 3) {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($lastDayDate->date . ' +1 day'));
            } else if ($days == 2) {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($lastDayDate->date . ' +2 day'));
            } else if ($days == 1) {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($lastDayDate->date . ' +3 day'));
            } else {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($todayDate . ' +3 day'));
            }
        } else {
            $firstValidDate = date('Y-m-d H:i:s', strtotime($todayDate . ' +3 day'));
        }
        return $firstValidDate;
    }


    private function daysFromDates($fdate, $tdate)
    {
        $datetime1 = new \DateTime($fdate);
        $datetime2 = new \DateTime($tdate);
        $interval  = $datetime1->diff($datetime2);
        $days      = $interval->format('%a');
        return $days;
    }



    public function saveGift(Request $request)
    {
        $user = $this->getUser($request);
        $userId = $user->id;
        $refId = $request->ref_id;
        $giftId = $request->gift_id;
        $packageId = $request->package_id;

        if (!empty($userId)) {
            if (!empty($refId) && !empty($giftId) && !empty($packageId)) {
                $checkGift = Gift::where('id', $giftId)->where('active', 1)->first();
                if (empty($checkGift)) {
                    return $this->sendResponse(404, ['data' => [], 'message' => 'Gift not available!']);
                }
                $package = Package::where('id', $packageId)->first();
                if ($package->is_giftable == 0) {
                    return $this->sendResponse(400, ['data' => [], 'message' => 'The selected package is not gitable!']);
                }
                $payment = Payment::where('user_id', $userId)->where('ref_id', $refId)->first();
                if (!empty($payment) && empty($payment->gift_id)) {
                    $payment->gift_id = $giftId;
                    $payment->save();
                    return $this->sendResponse(200, ['data' => [], 'message' => 'Gift added successfully']);
                } else {
                    return $this->sendResponse(404, ['data' => [], 'message' => 'Unable to add Gift']);
                }
            } else
                return $this->sendResponse(404, ['data' => [], 'message' => 'Please provide Ref ID, Gift ID and Package ID']);
        } else {
            return $this->sendResponse(404, ['data' => [], 'message' => 'User not logged in!!']);
        }
    }

    public function getExisitingGift($userId)
    {
        if (!empty($userId)) {
            $paidTrans = Payment::where('user_id', $userId)->where('paid', 1)->whereNotNull('gift_id')->first();
            if (!empty($paidTrans)) return null;
            $unpaidTrans = Payment::where('user_id', $userId)->where('paid', 0)->whereNotNull('gift_id')->first();
            if (!empty($unpaidTrans)) {
                $gift = Gift::where('id', $unpaidTrans->gift_id)->first();
                if (!empty($gift)) {
                    return $gift->id;
                }
            }
            return null;
        }
    }
}
