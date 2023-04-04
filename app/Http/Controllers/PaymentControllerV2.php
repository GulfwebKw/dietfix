<?php

namespace App\Http\Controllers;

use App\Events\RegisterUser;
use App\Models\Clinic\Gift;
use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;
use App\Models\Clinic\Payment;
use App\Models\Clinic\UserDate;
use App\Models\Clinic\UserDateTemp;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use DB;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class PaymentControllerV2 extends MainController
{

    //protected $payStatic      = 'https://demo.myfatoorah.com/ie/';
    //TEST
    protected $username       = 'demoApiuser@myfatoorah.com';
    protected $password       = 'Mf@12345678';
    protected $apiPath        = 'https://apidemo.myfatoorah.com/ApiInvoices/CreateInvoiceIso';
    protected $apiCallBackUrl = 'https://apidemo.myfatoorah.com/ApiInvoices/Transaction/';
    protected $apiTokenUrl    = 'https://apidemo.myfatoorah.com/Token';

	protected $callBackUrl = 'https://demo.dietfix.com/callBackPayment2';
    protected $errorUrl    = 'https://demo.dietfix.com/callBackPayment2';

    //LIVE
//    protected $username = 'ahmadmyfatoorah@gmail.com';
//    protected $password = 'ahmadmyfatoorah@2019';
//    protected $apiPath = 'https://apikw.myfatoorah.com/ApiInvoices/CreateInvoiceIso';
//    protected $apiCallBackUrl = 'https://apikw.myfatoorah.com/ApiInvoices/Transaction/';
//    protected $apiTokenUrl = 'https://apikw.myfatoorah.com/Token';
//
//    protected $callBackUrl = 'https://dietfix.com/callBackPayment2';
//    protected $errorUrl    = 'https://dietfix.com/callBackPayment2';


    public function getToken()
    {
        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->apiTokenUrl);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('grant_type' => 'password', 'username' => $this->username, 'password' => $this->password)));
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);
            $json = json_decode($result, true);
            if (isset($json['access_token']) && !empty($json['access_token'])) {
                $access_token = $json['access_token'];
            } else {
                $access_token = null;
            }
            if (isset($json['token_type']) && !empty($json['token_type'])) {
                $token_type = $json['token_type'];
            } else {
                $token_type = null;
            }
            return [$access_token, $token_type];
        } catch (\Exception $e) {
            return false;
        }
    }
    public function mobilePayment(Request $request)
    {

        $discount_amount  = !empty($request->discount_amount) ? $request->discount_amount : '0';
        $coupon_code      = !empty($request->coupon_code) ? $request->coupon_code : '';

        //validation parameter user
        $today     = date("Y-m-d");
        $date      = strtotime("+3 day", strtotime($today));
        $firstDate = date("Y-m-d", $date);

        $user = $this->getUser($request);
        if (isset($request->wallet)) {
            $wallet = intval($request->wallet);
        } else {
            $wallet = 0;
        }

        $res = $this->validFuture($user->id);

        if (!$res) {
            return $this->sendResponse(205, ['data' => [], 'message' => '']);
        }


        $for_future = $this->forFuture($user->id);

        $validPayWallet = false;
        $payWayType = "Gateway";
        $totlaCredit = 0;





        $validator = Validator::make($request->all(), ['package_id' => 'required', 'package_duration_id' => 'required', 'api_token' => 'required', 'starting_date' => "required|date|date_format:Y-m-d|after_or_equal:$firstDate", 'email' => 'nullable|email', 'sex' => 'nullable|in:Male,Female', 'delivery_type' => 'nullable|exists:delivery_type,id']);
        if ($validator->fails()) {
            return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
        }


        if ($for_future) {
            if (isset($user->package_id)) {
                if ($request->package_id != $user->package_id) {
                    return $this->sendResponse(205, ['data' => [], 'message' => trans('main.Your_package_not_expired')]);
                }
            }
        }



        $lastDayReservation = UserDate::where('user_id', $user->id)->where('freeze', 0)->orderBy('date', 'desc')->select(['date'])->first();
        if (isset($lastDayReservation)) {
            // $tim=strtotime($lastDayReservation->date);
            //    $dateV = strtotime("+3 day",$tim);
            //      $validDay=date('Y-m-d',$dateV);
            if ($request->starting_date <= $lastDayReservation->date) {
                return $this->sendResponse(400, ['data' => [], 'message' => trans("main.The_valid_date", ['date' => $lastDayReservation->date])]);
            }
        }





        $change = false;
        if (($user->email == "" || $user->email == null) && isset($request->email)) {
            $user->email = $request->email;
            $change = true;
        }

        if (isset($request->sex)) {
            $user->sex = $request->sex;
            $change = true;
        }

        if (isset($request->dob)) {
            $user->dob = $request->dob;
            $change = true;
        }

        if (isset($request->weight) && ($user->weight == "" || $user->weight == null)) {
            $user->weight = $request->weight;
            $change = true;
        }
        if (isset($request->height) && ($user->height == "" || $user->height == null)) {
            $user->height = $request->height;
            $change = true;
        }
        if (isset($request->delivery_type) && ($user->delivery_type == "" || $user->delivery_type == null)) {
            $user->delivery_type = $request->delivery_type;
            $change = true;
        }
        if ($change) {
            $user->save();
        }


        if ($user->email == "" || $user->email == null) {
            return $this->sendResponse(400, ['data' => [], 'message' => trans("main.valid_email")]);
        }
        if ($user->height == "" || $user->height == null) {
            return $this->sendResponse(400, ['data' => [], 'message' => trans("main.valid_height")]);
        }
        if ($user->weight == "" || $user->weight == null) {
            return $this->sendResponse(400, ['data' => [], 'message' => trans("main.valid_weight")]);
        }

        Log::info("mobilePayment: Request Package = ".$request->package_id.",User ID=".$user->id);

        $package = Package::find($request->package_id);
        $packageDuration = PackageDurations::find($request->package_duration_id);
        $totalGateway = $this->validPricePackageDuration($packageDuration);
        if (intval($wallet) == 1) {
            $sumAva = $this->getBalanceWalletUser($user->id);
            if ($sumAva <= 0) {
                return $this->sendResponse(400, ['data' => [], 'message' => trans('main.empty_wallet')]);
            }
            if (isset($packageDuration)) {
                $p = $this->validPricePackageDuration($packageDuration, $discount_amount);
                $totalGateway = $p;
                if ($sumAva < $p) {
                    //return $this->sendResponse(400, ['data' => [], 'message' =>trans('main.empty_wallet')]);
                    $payWayType = "Combinatorial";
                    $totlaCredit = $sumAva;
                    $totalGateway = $p - $sumAva;
                } else {
                    $validPayWallet = true;
                    $payWayType = "Credit";
                }
            }
        }

        if (isset($user)) {
            $totalGateway = $totalGateway - $discount_amount;

            if ($wallet == 1 && $validPayWallet) {
                DB::table("credit_user")->insert(["operation" => "decrement", 'user_id' => $user->id, "value" => $totalGateway, 'description' => "success payment package wallet balance.  package_id ==>$package->id   package_duration_id ==> $packageDuration->id"]);

                $payment = new Payment();
                $payment->user_id = $user->id;
                $payment->package_id = $package->id;
                $payment->package_duration_id = $packageDuration->id;
                $payment->pay_way_type = 'Credit';
                $payment->total_credit = $totalGateway;
                $payment->total = 0;
                $payment->starting_date = $request->starting_date;
                $payment->status = 1;
                $payment->paid = 1;
                $payment->description = 'success full payment user wallet balance';
                $payment->for_future = $for_future;
                $payment->total_discount = $discount_amount; //discount amount
                $payment->coupon_code = $coupon_code;
                $payment->save();
                if ($for_future == 1) {
                    DB::table('renew_future')->insert(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'starting_date' => $request->starting_date, 'pay_id' => $payment->id, 'pay_type' => $payment->pay_way_type]);
                }

                $resultUpdate = $this->updatePackageUser($user->id, $package->id, $packageDuration->id, $request->starting_date, $for_future);
                if ($resultUpdate) {
                    return $this->sendResponse(200, ['data' => ['walletPayment' => true, 'webViewUrl' => ""], 'message' => trans('main.wallet_payment_success')]);
                }
            } else {
                $res = $this->getToken();
                if (is_array($res)) {
                    $token = $res[0];
                    if ($token != null && $token != "") {
                        if (isset($package) && isset($packageDuration)) {

                            $initPayment = $this->initPayment($user, $package, $packageDuration, 2, $token, $wallet, $totalGateway, $discount_amount);
                            if (is_array($initPayment)) {
                                $urlRedirect = $initPayment[1];

                                if ($wallet == 1) {
                                    Payment::create(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'payment_data' => $initPayment[3], 'ref_id' => $initPayment[0], 'pay_way_type' => 'Combinatorial', 'total_credit' => $totlaCredit, 'total' => $totalGateway, 'starting_date' => $request->starting_date, 'description' => 'Combinatorial payment use credit and payment gateway', 'for_future' => $for_future, 'total_discount' => $discount_amount, 'coupon_code' => $coupon_code]);
                                } else {
                                    Payment::create(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'payment_data' => $initPayment[3], 'total' => $totalGateway, 'ref_id' => $initPayment[0], 'starting_date' => $request->starting_date, 'for_future' => $for_future, 'total_discount' => $discount_amount, 'coupon_code' => $coupon_code]);
                                }

                                return $this->sendResponse(200, ['data' => ['walletPayment' => false, 'webViewUrl' => $urlRedirect, 'package_id' => $package->id, 'ref_id' => $initPayment[0]], 'message' => 'Update Personal information successful']);
                            } else {
                                return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                            }
                        } else {
                            return $this->sendResponse(400, ['data' => [], 'message' => trans("main.selected_package_is_not_valid")]);
                        }
                    } else {
                        return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                    }
                } else {
                    return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                }
            }
        } else {
            $message = 'user not found!';
            return $this->sendResponse(400, ['data' => [], 'message' => $message]);
        }
    }
    public function reNewSubscription(Request $request)
    {

        try {

            $discount_amount  = !empty($request->discount_amount) ? $request->discount_amount : '0';
            $coupon_code      = !empty($request->coupon_code) ? $request->coupon_code : '';

            $user = $this->getUser($request);

            $validator = Validator::make($request->all(), ['starting_date' => "required|date|date_format:Y-m-d|after_or_equal:$firstDate", 'delivery_type' => 'nullable|exists:delivery_type,id']);
            if ($validator->fails()) {
                return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
            }

            $today     = date('Y-m-d');
            //$date      = strtotime("+3 day",strtotime($today));
            $firstDate = date('Y-m-d', strtotime($today . ' + 3 days'));
            //dd($firstDate);

            if (isset($request->wallet)) {
                $wallet = intval($request->wallet);
            } else {
                $wallet = 0;
            }
            $totlaCredit = 0;
            $validPayWallet = false;


            /*
           $res= $this->validFuture($user->id);
           if(!$res){
           return $this->sendResponse(205, ['data' => [], 'message' =>'']);
           }
		   */

		    if (!empty($request->dob) || !empty($request->weight) || !empty($request->height)) {

		    if (!empty($request->dob)) {
            $user->dob = $request->dob;
            }

            if (!empty($request->weight)) {
                $user->weight = $request->weight;
            }
            if (!empty($request->height)) {
                $user->height = $request->height;
            }
            if (!empty($request->delivery_type)) {
                $user->delivery_type = $request->delivery_type;
            }
            $user->save();
		    }

            $for_future = $this->forFuture($user->id);


            $lastDayReservation = UserDate::where('user_id', $user->id)->where('freeze', 0)->orderBy('date', 'desc')->select(['date'])->first();

            if (isset($lastDayReservation)) {
                $firstDate = $this->getValidRenewPackage($lastDayReservation->date, $firstDate);
                if ($request->starting_date <= $lastDayReservation->date) {
                    return $this->sendResponse(400, ['data' => [], 'message' => trans("main.The_valid_date", ['date' => $lastDayReservation->date])]);
                }
            } else {
                return $this->sendResponse(400, ['data' => [], 'message' => trans("main.You_must_first_subscribe_new_package!") . '-1']);
            }


            if ($user->package_id == null || empty($user->package_id)) {
                return $this->sendResponse(400, ['data' => [], 'message' => trans("main.You_must_first_subscribe_new_package!") . '-2']);
            }


            //////////////////////////////upgrade other package for future ///////////////////////////////////////////
            if (!empty($user->package_id) && !empty($request->package_id) && !empty($request->package_duration_id) && $user->package_id <> $request->package_id) {
                $package         = Package::find($request->package_id);
                $packageDuration = PackageDurations::find($request->package_duration_id);


                Log::info("reNewSubscription: Request Package = ".$request->package_id.",User ID=".$user->id);

                if (!isset($package) || !isset($packageDuration)) {
                    return $this->sendResponse(400, ['data' => [], 'message' => trans("main.selected_package_is_not_valid")]);
                }
                //get last Date
                $lastDayReservation = UserDate::where('user_id', $user->id)->where('freeze', 0)->orderBy('date', 'desc')->select(['date'])->first();
                if (!empty($request->starting_date)) {
                    if ($request->starting_date <= $lastDayReservation->date) {
                        return $this->sendResponse(400, ['data' => [], 'message' => trans("main.The_valid_date", ['date' => $lastDayReservation->date])]);
                    }
                    $firstDate =  $this->getValidRenewPackageRequestDate($request->starting_date);
                } else {
                    if (!empty($lastDayReservation->date)) {
                        $firstDate = $this->getValidRenewPackage($lastDayReservation->date, $firstDate);
                    }
                }


                if (intval($wallet) == 1) {
                    $sumAva = $this->getBalanceWalletUser($user->id);
                    if ($sumAva <= 0) {
                        return $this->sendResponse(400, ['data' => [], 'message' => trans('main.empty_wallet')]);
                    }
                    if (isset($packageDuration)) {
                        $p = $this->validPricePackageDuration($packageDuration, $discount_amount);
                        $totalGateway = $p;
                        if ($sumAva < $p) {
                            $payWayType   = "Combinatorial";
                            $totlaCredit  = $sumAva;
                            $totalGateway = $p - $sumAva;
                        } else {
                            $validPayWallet = true;
                            $payWayType     = "Credit";
                        }
                    }
                } else {
                    $totalGateway = $this->validPricePackageDuration($packageDuration, $discount_amount);
                }

                //$totalGateway = $totalGateway;

                if ($wallet == 1 && $validPayWallet) {
                    DB::table("credit_user")->insert(["operation" => "decrement", 'user_id' => $user->id, "value" => $totalGateway, 'description' => "success payment package wallet balance.  package_id ==> $package->id package_duration_id ==>$packageDuration->id"]);

                    $payment = new Payment();
                    $payment->user_id             = $user->id;
                    $payment->package_id          = $package->id;
                    $payment->package_duration_id = $packageDuration->id;
                    $payment->pay_way_type        = 'Credit';
                    $payment->total_credit        = $totalGateway;
                    $payment->total               = 0;
                    $payment->starting_date       = $firstDate;
                    $payment->status              = 1;
                    $payment->paid                = 1;
                    $payment->description         = 'success full payment user wallet balance';
                    $payment->for_future_package  = $for_future; //future different package
                    $payment->for_future          = 0; //future same package
                    $payment->total_discount = $discount_amount; //discount amount
                    $payment->coupon_code = $coupon_code;
                    $payment->save();
                    //save future records
                    if ($for_future == 1) {
                        DB::table('renew_future_package')->insert(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'starting_date' => $firstDate, 'pay_id' => $payment->id, 'pay_type' => $payment->pay_way_type]);
                    }

                    $resultUpdate = $this->updatePackageUserTemp($user->id, $package->id, $packageDuration->id, $firstDate, $for_future);

                    if ($resultUpdate) {
                        return $this->sendResponse(200, ['data' => ['walletPayment' => true, 'webViewUrl' => ""], 'message' => trans('main.wallet_payment_success')]);
                    }
                } else {

                    $res = $this->getToken();
                    if (is_array($res)) {
                        $token = $res[0];
                        if ($token != null && $token != "") {

                            $initPayment = $this->initPayment($user, $package, $packageDuration, 2, $token, $wallet, $totalGateway, $discount_amount);

                            if (is_array($initPayment)) {
                                $urlRedirect = $initPayment[1];
                                if ($wallet == 1) {
                                    Payment::create(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'payment_data' => $initPayment[3], 'pay_way_type' => 'Combinatorial', 'total_credit' => $totlaCredit, 'total' => $totalGateway, 'ref_id' => $initPayment[0], 'starting_date' => $firstDate, 'for_future_package' => $for_future, 'total_discount' => $discount_amount, 'coupon_code' => $coupon_code]);
                                } else {
                                    Payment::create(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'payment_data' => $initPayment[3], 'total' => $totalGateway, 'ref_id' => $initPayment[0], 'starting_date' => $firstDate, 'for_future_package' => $for_future, 'total_discount' => $discount_amount, 'coupon_code' => $coupon_code]);
                                }
                                return $this->sendResponse(200, ['data' => ['walletPayment' => false, 'webViewUrl' => $urlRedirect], 'message' => 'Update Personal information successful']);
                            } else {
                                return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                            }
                        } else {
                            return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                        }
                    } else {
                        return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                    }
                }
            }


            ///////////////////////end upgrade package for future //////////////////////////////////////////////
            $package = Package::find($user->package_id);

            if (isset($request->package_duration_id) && $request->package_duration_id != 0) {
                $packageDuration = PackageDurations::find($request->package_duration_id);
            } else {
                $packageDuration = PackageDurations::find($user->package_duration_id);
            }
            if (!isset($package) || !isset($packageDuration)) {
                return $this->sendResponse(400, ['data' => [], 'message' => trans("main.selected_package_is_not_valid")]);
            }

            if (intval($wallet) == 1) {
                $sumAva = $this->getBalanceWalletUser($user->id);
                if ($sumAva <= 0) {
                    return $this->sendResponse(400, ['data' => [], 'message' => trans('main.empty_wallet')]);
                }
                if (isset($packageDuration)) {
                    $p = $this->validPricePackageDuration($packageDuration, $discount_amount);
                    $totalGateway = $p;
                    if ($sumAva < $p) {
                        //return $this->sendResponse(400, ['data' => [], 'message' =>trans('main.empty_wallet')]);
                        $payWayType = "Combinatorial";
                        $totlaCredit = $sumAva;
                        $totalGateway = $p - $sumAva;
                    } else {
                        $validPayWallet = true;
                        $payWayType = "Credit";
                    }
                }
            } else {
                $totalGateway = $this->validPricePackageDuration($packageDuration, $discount_amount);
            }

            $totalGateway = $totalGateway - $discount_amount;

            if ($wallet == 1 && $validPayWallet) {
                DB::table("credit_user")->insert(["operation" => "decrement", 'user_id' => $user->id, "value" => $totalGateway, 'description' => "success payment package wallet balance.  package_id ==> $package->id package_duration_id ==>$packageDuration->id"]);

                $payment = new Payment();
                $payment->user_id = $user->id;
                $payment->package_id = $package->id;
                $payment->package_duration_id = $packageDuration->id;
                $payment->pay_way_type = 'Credit';
                $payment->total_credit = $totalGateway;
                $payment->total = 0;
                $payment->starting_date = $firstDate;
                $payment->status = 1;
                $payment->paid = 1;
                $payment->description = 'success full payment user wallet balance';
                $payment->for_future = $for_future;
                $payment->total_discount = $discount_amount; //discount amount
                $payment->coupon_code = $coupon_code;
                $payment->save();
                if ($for_future == 1) {
                    DB::table('renew_future')->insert(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'starting_date' => $request->starting_date, 'pay_id' => $payment->id, 'pay_type' => $payment->pay_way_type]);
                }

                $resultUpdate = $this->updatePackageUser($user->id, $package->id, $packageDuration->id, $firstDate, $for_future);
                if ($resultUpdate) {
                    return $this->sendResponse(200, ['data' => ['walletPayment' => true, 'webViewUrl' => ""], 'message' => trans('main.wallet_payment_success')]);
                }
            } else {
                $res = $this->getToken();
                if (is_array($res)) {
                    $token = $res[0];
                    if ($token != null && $token != "") {

                        $initPayment = $this->initPayment($user, $package, $packageDuration, 2, $token, $wallet, $totalGateway, $discount_amount);
//dd($initPayment);
                        if (is_array($initPayment)) {
                            $urlRedirect = $initPayment[1];
                            if ($wallet == 1) {
                                Payment::create(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'payment_data' => $initPayment[3], 'pay_way_type' => 'Combinatorial', 'total_credit' => $totlaCredit, 'total' => $totalGateway, 'ref_id' => $initPayment[0], 'starting_date' => $firstDate, 'for_future' => $for_future, 'total_discount' => $discount_amount, 'coupon_code' => $coupon_code]);
                            } else {
                                Payment::create(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'payment_data' => $initPayment[3], 'total' => $totalGateway, 'ref_id' => $initPayment[0], 'starting_date' => $firstDate, 'for_future' => $for_future, 'total_discount' => $discount_amount, 'coupon_code' => $coupon_code]);
                            }
                            return $this->sendResponse(200, ['data' => ['walletPayment' => false, 'webViewUrl' => $urlRedirect], 'message' => 'Update Personal information successful']);
                        } else {
                            return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                        }
                    } else {
                        return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                    }
                } else {
                    return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                }
            }
        } catch (\Exception $e) {
            ///dd($e);
            return $this->sendResponse(500, ['data' => [], 'message' => trans('Server_Error')]);
        }
    }

    public function validPricePackageDuration($packageDuration, $discount_amount = 0)
    {
        if (!empty($discount_amount)) {
            return floatval(($packageDuration->price - $discount_amount));
        } elseif (floatval(optional($packageDuration)->price_after_discount) > 0) {
            return floatval(optional($packageDuration)->price_after_discount);
        } else {
            return floatval($packageDuration->price);
        }
    }
    private function initPayment($user, $package, $packageDuration, $lang = 2, $accessToken, $wallet = 0, $totalGateWay, $discount_amount = 0)
    {

        try {
            if ($wallet == 1) {
                $price = $totalGateWay;
            } else {
                $price = $this->validPricePackageDuration($packageDuration, $discount_amount);
                //$price = $price - $discount_amount;
            }
            $fullName = !empty($user->fullname) ? $user->fullname : $user->mobile_number;

            $t = time();
            $post_string = '{
            "InvoiceValue": "' . $price . '",
            "CustomerName": "' . $fullName . '",
            "CustomerBlock": "' . $user->block . '",
            "CustomerStreet": "' . $user->street . '",
            "CustomerHouseBuildingNo": "' . $user->house_number . '",
            "CustomerCivilId": "",
            "CustomerAddress": "' . $user->address . '",
            "CustomerReference": "' . $t . '",
            "DisplayCurrencyIsoAlpha": "KWD",
            "CountryCodeId": "+965",
            "CustomerMobile": "' . $user->mobile_number . '",
            "CustomerEmail": "' . $user->email . '",
            "DisplayCurrencyId": 3,
            "SendInvoiceOption": 1,
            "InvoiceItemsCreate": [
              {
                "ProductId":null,
                "ProductName": "' . $packageDuration->titleEn . '",
                "Quantity":1,
                "UnitPrice": "' . $price . '"

              }
            ],
                "CallBackUrl":  "' . $this->callBackUrl . '",
                 "Language": "' . $lang . '",
                 "ExpireDate": "2030-12-31T13:30:17.812Z",
                 "ApiCustomFileds": "",
                 "ErrorUrl": "' . $this->errorUrl . '"
          }';
            $soap_do     = curl_init();
            curl_setopt($soap_do, CURLOPT_URL, $this->apiPath);
            curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
            curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($soap_do, CURLOPT_POST, true);
            curl_setopt($soap_do, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($soap_do, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Content-Length: ' . strlen($post_string),  'Accept: application/json', 'Authorization: Bearer ' . $accessToken));
            $result1 = curl_exec($soap_do);
            //echo "<pre>";print_r($result1);die;
            $err    = curl_error($soap_do);
            $json1 = json_decode($result1, true);


            if (isset($json1['IsSuccess']) && $json1['IsSuccess'] == true) {

                $RedirectUrl = $json1['RedirectUrl'];



                if (is_array($json1['PaymentMethods'])) {
                    $ref_Ex = $json1['PaymentMethods'][0];
                    if (array_key_exists('PaymentMethodUrl', $ref_Ex)) {
                        $t = explode("?", $ref_Ex["PaymentMethodUrl"]);
                        if (is_array($t)) {
                            $res = str_replace("invoiceKey=", "", explode("&", $t[1]));
                            //$referenceId =  $res[0];
                            $referenceId = $json1['Id'];
                            curl_close($soap_do);
                            return [$referenceId, $RedirectUrl, $ref_Ex, $post_string];
                        }
                    }
                }
            } else {
                die($json1['Message']);
                return false;
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return false;
        }
    }
    public function callBackPayment(Request $request)
    {
        try {
            if (isset($request->paymentId)) {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $this->apiTokenUrl);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('grant_type' => 'password', 'username' => $this->username, 'password' => $this->password)));
                $result = curl_exec($curl);
                $error = curl_error($curl);
                $info = curl_getinfo($curl);
                curl_close($curl);
                $json = json_decode($result, true);
                $access_token = $json['access_token'];
                $token_type = $json['token_type'];
                if (isset($_GET['paymentId'])) {
                    $id = $_GET['paymentId'];
                }
                $url = $this->apiCallBackUrl . $id;
                $soap_do1 = curl_init();
                curl_setopt($soap_do1, CURLOPT_URL, $url);
                curl_setopt($soap_do1, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($soap_do1, CURLOPT_TIMEOUT, 10);
                curl_setopt($soap_do1, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($soap_do1, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($soap_do1, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($soap_do1, CURLOPT_POST, false);
                curl_setopt($soap_do1, CURLOPT_POST, 0);
                curl_setopt($soap_do1, CURLOPT_HTTPGET, 1);
                curl_setopt($soap_do1, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Accept: application/json', 'Authorization: Bearer ' . $access_token));
                $result_in = curl_exec($soap_do1);
                $err_in = curl_error($soap_do1);
                $file_contents = htmlspecialchars(curl_exec($soap_do1));
                curl_close($soap_do1);
                $getRecorById = json_decode($result_in, true);
                $ref = $getRecorById['ReferenceId'];
                // dd($result_in,$getRecorById);
                $refID  = $getRecorById['InvoiceId'];
                $error  = $getRecorById['Error'];
                $status = $getRecorById['TransactionStatus'];

                $payment = Payment::where('ref_id', $refID)->with('user')->first();
                if (isset($payment)) {

                    if ($payment->status == 1 || $payment->status == 2) {
                        $message = 'The result of the transaction has already been marked.';
                        return view("payments.retry", compact('payment', 'message'));
                    }


                    $packageDuration = PackageDurations::find($payment->package_duration_id);
                    $paymentsArray = [];


                    if (!empty($getRecorById['PaidCurrency'])) {
                        $paymentsArray['PaidCurrency'] = $getRecorById['PaidCurrency'];
                    }
                    if (!empty($getRecorById['InvoiceDisplayValue'])) {
                        $paymentsArray['InvoiceDisplayValue'] = $getRecorById['InvoiceDisplayValue'];
                    }
                    if (!empty($getRecorById['PaidCurrencyValue'])) {
                        $paymentsArray['PaidCurrencyValue'] = $getRecorById['PaidCurrencyValue'];
                    }
                    if (!empty($getRecorById['CustomerServiceCharge'])) {
                        $paymentsArray['CustomerServiceCharge'] = $getRecorById['CustomerServiceCharge'];
                    }
                    if (!empty($getRecorById['DueValue'])) {
                        $paymentsArray['DueValue'] = $getRecorById['DueValue'];
                    }
                    if (!empty($getRecorById['Currency'])) {
                        $paymentsArray['Currency'] = $getRecorById['Currency'];
                    }

                    if (!empty($getRecorById['PaymentGateway'])) {
                        $paymentsArray['type'] = $getRecorById['PaymentGateway'];
                    }
                    if (!empty($getRecorById['transationValue'])) {
                        $paymentsArray['transationValue'] = $getRecorById['transationValue'];
                    }
                    if (!empty($TrackId)) {
                        $paymentsArray['TrackId'] = $TrackId;
                    }
                    if (!empty($getRecorById['TransactionId'])) {
                        $paymentsArray['paymentID'] = $getRecorById['TransactionId'];
                    }
                    if (!empty($ref)) {
                        $paymentsArray['ref'] = $ref;
                    }
                    if (!empty($status)) {
                        $paymentsArray['gateway_status'] = $status;
                    }

                    $this->updateStatusPayment($payment, $paymentsArray);

                    if ($status == 2) {
                        // $isValidPayment=$this->isValidPayment($getRecorById['TransationValue'],$packageDuration->price);
                        $isValidPayment = true;
                        if ($isValidPayment) {
                            $resC = $this->checkIsValidCombinatorialPayment($payment);
                            if ($resC) {
                                $res =  $this->updateStatusPayment($payment, ['status' => 1, 'paid' => 1, 'description' => 'success full payment and verfiy']);
                                if ($payment->for_future == 1) {
                                    DB::table('renew_future')->insert(['user_id' => $payment->user_id, 'package_id' => $payment->package_id, 'package_duration_id' => $payment->package_duration_id, 'starting_date' => $payment->starting_date, 'pay_id' => $payment->id, 'pay_type' => $payment->pay_way_type]);
                                }
                                if ($payment->for_future_package == 1) {
                                    DB::table('renew_future_package')->insert(['user_id' => $payment->user_id, 'package_id' => $payment->package_id, 'package_duration_id' => $payment->package_duration_id, 'starting_date' => $payment->starting_date, 'pay_id' => $payment->id, 'pay_type' => $payment->pay_way_type]);
                                }

                                if (empty($payment->for_future_package)) {
                                    $resultUpdate = $this->updatePackageUser($payment->user_id, $payment->package_id, $payment->package_duration_id, $payment->starting_date, $payment->for_future);
                                } else {
                                    $resultUpdate = $this->updatePackageUserTemp($payment->user_id, $payment->package_id, $payment->package_duration_id, $payment->starting_date, $payment->for_future_package);
                                }
                                if ($resultUpdate) {
                                    $gift = Gift::where('id', $payment->gift_id)->first();
                                    if (!empty($gift)) {
                                        $giftTitle = $gift->titleEn;
                                        return view("payments.success", compact('payment', 'giftTitle'));
                                    } else {
                                        return view("payments.success", compact('payment'));
                                    }
                                }
                                return view("payments.newError", compact('payment'));
                            } else {
                                //not valid payment CombinatorialPayment
                                return view("payments.newError", compact('payment'));
                            }
                        } else {
                            $res =  $this->updateStatusPayment($payment, ['status' => 2, 'description' => 'price payment not equal package price!']);
                            $message = 'not valid  payment price';
                            return view("payments.newError", compact('payment', 'message'));
                        }
                    } else {
                        $res =  $this->updateStatusPayment($payment, ['status' => 2, 'description' => 'error payment gateway ' . $getRecorById['Error']]);
                        $payment->status = 2;
                        $payment->save();
                        return view("payments.newError", compact('payment'));
                    }
                } else {
                    $message = 'your payment record not found ';
                    return view("payments.emptyError", compact('message'));
                }
            }
        } catch (\Exception $e) {
            $message = 'payment service is not available';
            return view("payments.emptyError", compact('message'));
            //return error page
        }
    }
    public function isValidPayment($pricePayment, $pricePackage)
    {
        return floatval($pricePackage) === floatval($pricePayment);
    }
    private function updateStatusPayment($payment, $keyValue)
    {
        try {
            if (isset($payment)) {
                foreach ($keyValue as $key => $value) {
                    $payment->{$key} = $value;
                }
                $res = $payment->save();
                if (isset(optional($payment->user)->email)) {
                    Mail::to(optional($payment->user)->email)->send(new \App\Mail\Payment($payment, $payment->paid));
                }
                Mail::to(env('MAIL_FROM_ADDRESS'))->send(new \App\Mail\Payment($payment, $payment->paid));

                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
    private function updatePackageUser($userId, $packageId, $packageDurationId, $startingDay, $forFuture = 0)
    {

        $user = User::find($userId);
        $packageDuration = PackageDurations::find($packageDurationId);
        if (isset($user)) {
            event(new RegisterUser($user));
            if ($forFuture == 0) {
                $user->package_id = $packageId;
                $user->package_duration_id = $packageDurationId;
                $user->is_subscription = 1;
                $user->doctor_id = 9929;
            }
            if ($forFuture == 0) {
                $user->membership_start = $startingDay;
            } else {
                if (!isset($user->membership_start) || is_null($user->membership_start)) {
                    $user->membership_start = $startingDay;
                }
            }


            if (isset($startingDay)) {
                $end = new DateTime($startingDay);
                $end->modify('+360 day');
                $end = $end->format('Y-m-d');
                $user->membership_end = $end;
            }
            // $user->membership_start=$startingDay;
            $user->save();
            if (isset($packageDuration)) {
                $this->makeUserWeekProgress($packageDuration->count_day, $user->id, $startingDay, $packageId, $forFuture);
            }
            return true;
        }
        return false;
    }
    //update temp dates
    private function updatePackageUserTemp($userId, $packageId, $packageDurationId, $startingDay, $forFuture = 0)
    {

        $user = User::find($userId);
        $packageDuration = PackageDurations::find($packageDurationId);

        if (isset($user)) {

            if (empty($forFuture)) {
                $user->package_id = $packageId;
                $user->package_duration_id = $packageDurationId;
                $user->is_subscription = 1;
                $user->doctor_id = !empty($user->doctor_id) ? $user->doctor_id : 9929;
                $user->membership_start = $startingDay;

                if (isset($startingDay)) {
                    $end = new DateTime($startingDay);
                    $end->modify('+360 day');
                    $end = $end->format('Y-m-d');
                    $user->membership_end = $end;
                }
                $user->save();
            }


            if (isset($packageDuration)) {
                $this->makeUserWeekProgressTemp($packageDuration->count_day, $user->id, $startingDay, $packageId, $forFuture);
            }

            return true;
        }

        return false;
    }

    private function makeUserWeekProgress($countDay, $userId, $startingDay = null, $packageId, $forFuture)
    {

        if (intval($countDay) > 1) {

            if ($startingDay == null) {
                $today =   date("Y-m-d");
                $date = strtotime("+3 day", strtotime($today));
                $firstDate = date("Y-m-d", $date);
            } else {
                $firstDate = $startingDay;
            }


            $arrayDay = [];
            array_push($arrayDay, $firstDate);
            for ($j = 1; $j < $countDay; $j++) {
                $nextDay = date('Y-m-d', strtotime("+$j day", strtotime($firstDate)));
                array_push($arrayDay, $nextDay);
            }

            foreach ($arrayDay as $item) {
                DB::table('user_dates')->insert(['date' => $item, 'user_id' => $userId, 'package_id' => $packageId]);
            }

            if ($forFuture == 0) {
                DB::table("user_week_progress")->where('user_id', $userId)->delete();
                $countWeek = ceil($countDay / 7);
                for ($i = 1; $i <= $countWeek; $i++) {
                    DB::table('user_week_progress')->insert(['user_id' => $userId, 'titleEn' => ' Week ' . $i . ' Progress', 'titleAr' => 'Week ' . $i . ' Progress', 'water' => 0, 'commitment' => 0, 'exercise' => 0]);
                }
            }
        }
    }

    //temp
    private function makeUserWeekProgressTemp($countDay, $userId, $startingDay = null, $packageId, $forFuture)
    {

        if (intval($countDay) > 1) {

            if ($startingDay == null) {
                $today     = date("Y-m-d");
                $date      = strtotime("+1 day", strtotime($today));
                $firstDate = date("Y-m-d", $date);
            } else {
                $firstDate = $startingDay;
            }


            $arrayDay = [];
            array_push($arrayDay, $firstDate);
            for ($j = 1; $j < $countDay; $j++) {
                $nextDay = date('Y-m-d', strtotime("+$j day", strtotime($firstDate)));
                array_push($arrayDay, $nextDay);
            }

            if (empty($forFuture)) {

                foreach ($arrayDay as $item) {
                    DB::table('user_dates')->insert(['date' => $item, 'user_id' => $userId, 'package_id' => $packageId]);
                }


                DB::table("user_week_progress")->where('user_id', $userId)->delete();
                $countWeek = ceil($countDay / 7);
                for ($i = 1; $i <= $countWeek; $i++) {
                    DB::table('user_week_progress')->insert(['user_id' => $userId, 'titleEn' => ' Week ' . $i . ' Progress', 'titleAr' => 'Week ' . $i . ' Progress', 'water' => 0, 'commitment' => 0, 'exercise' => 0]);
                }
            } else {

                foreach ($arrayDay as $item) {
                    DB::table('user_dates_temp')->insert(['date' => $item, 'user_id' => $userId, 'package_id' => $packageId]);
                }

                //future progress

                DB::table("user_week_progress_temp")->where('user_id', $userId)->delete();
                $countWeek = ceil($countDay / 7);
                for ($i = 1; $i <= $countWeek; $i++) {
                    DB::table('user_week_progress_temp')->insert(['user_id' => $userId, 'titleEn' => ' Week ' . $i . ' Progress', 'titleAr' => 'Week ' . $i . ' Progress', 'water' => 0, 'commitment' => 0, 'exercise' => 0]);
                }
            }
        }
    }
    private function getBalanceWalletUser($userId)
    {
        $increment = DB::table("credit_user")->where('user_id', $userId)->where('operation', "increment")->sum('value');
        $decrement = DB::table("credit_user")->where('user_id', $userId)->where("operation", "decrement")->sum("value");
        return floatval($increment) - floatval($decrement);
    }
    private function validFuture($userId)
    {
        $res = DB::table('renew_future')->where('user_id', $userId)->first();
        if (isset($res)) {
            return false;
        }
        return true;
    }
    private function forFuture($userId)
    {
        $today = date('Y-m-d');
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDayForEdit = date("Y-m-d", $date);

        $res =  DB::table('user_dates')->where('user_id', $userId)->where('date', '>=', $firstValidDayForEdit)->count();
        if ($res >= 1) {
            return 1;
        }
        return 0;
    }


    private function checkIsValidCombinatorialPayment($payment)
    {
        if ($payment->pay_way_type == "Gateway") {
            return true;
        } elseif ($payment->pay_way_type == "Combinatorial") {
            $sumCreadit = $payment->total_credit;
            if (isset($payment->user_id)) {
                $sumAva = $this->getBalanceWalletUser($payment->user_id);
                if ($sumAva >= $sumCreadit) {
                    DB::table("credit_user")->insert(['user_id' => $payment->user_id, 'value' => $sumCreadit, 'operation' => 'decrement', 'description' => " Combinatorial payment subscribe package .  pay_id --> $payment->id"]);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return false;
    }

    private function getValidRenewPackage($lastDayDate, $firstValidDate)
    {

        $todayDate = date('Y-m-d');

        if (!empty($lastDayDate) && $lastDayDate > $todayDate) {
            $days = $this->daysFromDates($todayDate, $lastDayDate);

            if ($days >= 3) {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($lastDayDate . ' +1 day'));
            } else if ($days == 2) {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($lastDayDate . ' +2 day'));
            } else if ($days == 1) {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($lastDayDate . ' +3 day'));
            } else {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($todayDate . ' +3 day'));
            }
        } else {
            $firstValidDate = date('Y-m-d H:i:s', strtotime($todayDate . ' +3 day'));
        }
        return $firstValidDate;
    }

    private function getValidRenewPackageRequestDate($requestDate)
    {

        $todayDate = date('Y-m-d');

        if (!empty($requestDate) && $requestDate > $todayDate) {
            $days = $this->daysFromDates($todayDate, $requestDate);

            if ($days > 3) {
                $firstValidDate = $requestDate;
            } else if ($days == 3) {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($requestDate . ' +1 day'));
            } else if ($days == 2) {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($requestDate . ' +2 day'));
            } else if ($days == 1) {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($requestDate . ' +3 day'));
            } else {
                $firstValidDate = date('Y-m-d H:i:s', strtotime($todayDate . ' +3 day'));
            }
        } else {
            $firstValidDate = date('Y-m-d H:i:s', strtotime($todayDate . ' +3 day'));
        }
        return $firstValidDate;
    }


    public function daysFromDates($fdate, $tdate)
    {
        $datetime1 = new \DateTime($fdate);
        $datetime2 = new \DateTime($tdate);
        $interval  = $datetime1->diff($datetime2);
        $days      = $interval->format('%a');
        return $days;
    }

    //********************new registration with package */

    public function newregister(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'package_id'    => 'required|gt:0',
                'package_duration_id' => 'required|gt:0',
                'starting_date' => 'required',
                'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8|unique:users,mobile_number',
                'password'      => 'required|min:8',
                'deviceType'    => 'required|in:android,ios,web',
                'area'          => 'required',
                'block'         => 'nullable',
                'fullname'      => 'required',
                'email'         => 'nullable',
                'sex'           => 'required|in:Male,Female',
                'height'        => 'required',
                'weight'        => 'required',
                'delivery_type' => 'nullable|exists:delivery_type,id'
            ]
        );

        if ($validator->fails()) {
            return $this->sendResponse(400, ['data' => [], 'message' => $validator->errors()->first()]);
        }

        $discount_amount  = !empty($request->discount_amount) ? $request->discount_amount : '0';
        $coupon_code      = !empty($request->coupon_code) ? $request->coupon_code : '';

        $user = new User();
        $user->phone     = $request->mobile_number;
        $user->clinic_id = 1;
        $user->doctor_id = 9929;
        $user->email     = $request->email;
        $user->mobile_number = $request->mobile_number;
        $user->password  = bcrypt($request->password);
        $user->country_id = 120;
        $user->deviceType = $request->deviceType;
        if (isset($request->fcm_token)) {
            $user->deviceToken = $request->fcm_token;
        }
        $user->api_token = hash('sha256', Str::random(60));

        if (!empty($request->dob)) {
            $user->dob = $request->dob;
        }
        if (!empty($request->sex)) {
            $user->sex = $request->sex;
        }
        if (!empty($request->weight)) {
            $user->weight = $request->weight;
        }
        if (!empty($request->height)) {
            $user->height = $request->height;
        }
        if (!empty($request->delivery_type)) {
            $user->delivery_type = $request->delivery_type;
        }

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

        if (isset($request->fullname)) {
            $user->fullname = $request->fullname;
        }

        $user->save();

        $res = $this->getToken();
        if (is_array($res)) {
            $token = $res[0];
            if ($token != null && $token != "") {

                $wallet = 0;
                $package = Package::find($request->package_id);

                $packageDuration = PackageDurations::find($request->package_duration_id);
                $totalGateway = $this->validPricePackageDuration($packageDuration, $discount_amount);
                $firstDate = $request->starting_date;
                $initPayment = $this->initPayment($user, $package, $packageDuration, 2, $token, $wallet, $totalGateway, $discount_amount);

                if (is_array($initPayment)) {
                    $urlRedirect = $initPayment[1];
                    Payment::create(['user_id' => $user->id, 'package_id' => $package->id, 'package_duration_id' => $packageDuration->id, 'payment_data' => $initPayment[3], 'total' => $totalGateway, 'ref_id' => $initPayment[0], 'starting_date' => $firstDate, 'for_future' => 0, 'total_discount' => $discount_amount, 'coupon_code' => 0]);
                    return $this->sendResponse(200, ['data' => ['walletPayment' => false, 'webViewUrl' => $urlRedirect, 'user' => $user, 'package_id' => $package->id, 'ref_id' => $initPayment[0]], 'message' => 'Update Personal information successful']);
                } else {
                    return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
                }
            } else {
                return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
            }
        } else {
            return $this->sendResponse(400, ['data' => [], 'message' => trans("main.payment_not_available")]);
        }
        //$res=$this->getPackageCountAndReferralPoint($user->id,$user->package_duration_id,$user->membership_start);
        $this->logUserActivity("new register User id-->" . $user->id, null, $user->deviceType);
        return $this->sendResponse(200, ['data' => ['user' => User::find($user->id), 'remind_day' => $this->getCountRemDaysUser($user->id, $user->membership_start), 'sum_point' => $res[0], 'count_day' => $res[1], 'sum_cash_back' => $res[2], 'subscription_end_date' => ''], 'message' => trans('main.register_success')]);
    }
}
