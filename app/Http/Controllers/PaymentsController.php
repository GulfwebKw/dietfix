<?php

namespace App\Http\Controllers;

use App\Models\Clinic\Order;
use App\Models\Clinic\Payment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use SoapClient;

class PaymentsController extends MainController
{
    public function __construct()
    {
        parent::__construct();

        // $this->knet = App::make('knet');
    }
    public function getIndex()
    {
        if(!Auth::user())
            return Redirect::to('/');

        $payments = Payment::with('user')
            ->with('order')
            ->with('order.items')
            ->with('order.items.product')
            ->where('user_id',Auth::user()->id)
            ->orderBy('created_at','desc')
            ->paginate(10);

        return View::make('payments.list')
            ->withTitle(trans('main.My Payments'))
            ->withPayments($payments);
    }
    public function getConfirm($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return Redirect::to(url('order/pay/'.$id))
                ->withError([trans('main.No Order Has Been Found With ID #').$id]);
        }

        $order_id = $order->id;
        $user = User::find($order->user_id);

        if (!$user) {
            return Redirect::to(url('order/pay/'.$id))
                ->withError([trans('main.No User Has Been Found For Order ID #').$id]);
        }

        $amount = $order->total;

        // if ($amount == '0') {
        // 	return Redirect::to(url('order/pay/'.$id))
        // 			->withError([trans('main.No Price Has Been Set For Order ID #').$id]);
        // }

        $trackID = $user->id.str_replace('.', '', $amount).rand(1000000,9999999);
        // Payment::where('order_id',$order_id)->delete();
        $newPayment = new Payment;
        $newPayment->order_id = $order_id;
        $newPayment->user_id = $user->id;
        $newPayment->paymentID = $trackID;
        $newPayment->total = $amount;
        $newPayment->type = 'knet';
        $newPayment->paid = 0;
        $newPayment->save();

        $paymentID = $newPayment->id;


        $result = $this->goTapNowPay([
            'Name' => 'Horse Speed Client',
            'Email' => $user->email,
            'Mobile' => $user->phone,
            'Price' => $order->total,
            'UnitName' => $order->id,
            'ReferenceID' => $trackID,
            'ReturnPath' => url('payments/response/'.$paymentID),
        ]);

        if (!$result)
            return Redirect::to(url('order/pay/'.$id))
                ->withError([trans('main.Can not pay for this order right now #').$id]);

        $payment = Payment::find($paymentID);
        $payment->paymentID = $result['referenceID'];
        $payment->save();

        return Redirect::to($result['paymentUrl']);
    }
    public function goTapNowPay($data = array())
    {
        if (!$data['Name'])
            $_errors[] = 'Name is required';
        if (!$data['Email'])
            $_errors[] = 'Email is required';
        if (!$data['Mobile'])
            $_errors[] = 'Mobile is required';
        if (!$data['Price'])
            $_errors[] = 'Price is required';
        if (!$data['UnitName'])
            $_errors[] = 'Title is required';
        if (!$data['ReturnPath'])
            $_errors[] = 'Return Path is required';

        $currencyCode = (isset($data['Currency'])) ? $data['Currency'] : 'KWD'; // (Should be KWD or USD)
        $client = new SoapClient($this->paymentWebService);
        $params = [
            // 'PaymentRequest' => [
            'PayRequest' => [
                'CustomerDC' => [
                    'Name' => $data['Name'],
                    'Email' => $data['Email'],
                    'Mobile' => $data['Mobile'],
                ],
                'lstProductDC' => [
                    'ProductDC' => [
                        'UnitName' => $data['UnitName'],
                        'UnitPrice' => $data['Price'],
                        'Quantity' => '1',
                        'TotalPrice' => $data['Price'],
                        'CurrencyCode' => $currencyCode,
                    ],
                ],
                'MerMastDC' => [
                    'MerchantID' => $this->merchantID,
                    'UserName' => $this->merchantUserName,
                    'Password' => $this->merchantPassword,
                    'ReturnURL' => $data['ReturnPath'],
                    'ReferenceID' => $data['ReferenceID'],
                    'AutoReturn' => 'Y',
                ],
            ]
            // ]
        ];

        $request = $client->PaymentRequest($params);
        // dd($request);
        $paymentUrl = false;
        if ($request->PaymentRequestResult->ResponseCode == '0') {
            $paymentUrl = $request->PaymentRequestResult->PaymentURL;
            $referenceID = $request->PaymentRequestResult->ReferenceID;
            return ['paymentUrl' => $paymentUrl,'referenceID' => $referenceID];
            // var_dump($request->PaymentRequestResult);
        } else
            return false;

    }
    public function getResponse($id)
    {
        if(!Auth::user())
            return Redirect::to(url('user/login'));

        $payment = Payment::find($id);

        if(!$payment)
            return Redirect::to(url('/'));

        $order = Order::find($payment->order_id);

        if(!$order)
            return Redirect::to(url('/'));

        $client = new SoapClient($this->paymentWebService);
        $params = [
            // 'PaymentRequest' => [
            'OrderStatusRequest' => [
                'MerchantID' => $this->merchantID,
                'UserName' => $this->merchantUserName,
                'Password' => $this->merchantPassword,
                'ReferenceID' => $payment->paymentID,
            ]
            // ]
        ];
        $request = $client->GetOrderStatusRequest($params);
        $paymentUrl = false;
        if ($request->GetOrderStatusRequestResult->ResponseCode == '0') {
            if($request->GetOrderStatusRequestResult->ResponseMessage == 'CAPTURED') {
                $payment->paid = 1;
                $payment->payment_data = serialize(Input::all());
                switch ($request->GetOrderStatusRequestResult->Paymode) {
                    case 'KNET':
                        $payment->type = 'knet';
                        break;

                    case 'Master':
                        $payment->type = 'credit_card';
                        break;

                    case 'Visa':
                        $payment->type = 'credit_card';
                        break;
                }
                $payment->save();
                $order->status = 'paid';
                $order->save();
                return Redirect::to(url('payments/success/'.$payment->id));
            } else {
                $payment->payment_data = serialize(Input::all());
                $payment->save();
                return Redirect::to(url('payments/error/'.$payment->id));
            }
        } else
            return Redirect::to(url('payments/error/'.$payment->id));
    }
    public function getSuccess()
    {
        if(!Request::segment(3))
            return Redirect::to(url(''));

        $payment = Payment::where('user_id',Auth::user()->id)
            ->where('id',Request::segment(3))
            ->where('paid',1)
            ->first();

        $payment->payment_data = unserialize($payment->payment_data);
        $data = array_merge($payment->payment_data,$payment->toArray());
        $data['settings'] = $this->_setting;
        $data['user'] = User::find($data['user_id']);
        $data['order'] = Order::find($data['order_id']);
        Mail::send('emails.user_payment_success', $data, function($message) use ($data)
        {
            $message
                ->to(Auth::user()->email, $data['settings']['siteName'.ucfirst(LANG_SHORT)])
                ->subject(trans('main.Thank You For Your Payment'));
        });

        Mail::send('emails.admin_user_payment_success', $data, function($message) use ($data)
        {
            $message
                ->to($data['settings']['adminEmail'], $data['settings']['siteNameEn'])
                ->subject(trans('main.New Payment For Order') . ' #' . $data['order']->id);
        });

        return View::make('payments.success')
            ->with('data',$data)
            ->with('payment',$payment)
            ->with('order',$data['order'])
            ->with('title',trans('main.Payment Successfully'));
    }
    public function getFail($id,$ReferenceID)
    {
        if(!Auth::user())
            return Redirect::to(url('user/login'));

        $payment = Payment::where('paymentID',$ReferenceID)
            ->where('order_id',$id)
            ->first();
        if(!$payment)
            return Redirect::to(url('orders/list'));

        $payment->payment_data = serialize(Input::all());
        $payment->save();

        return Redirect::to(url('payments/error/'.$payment->id));


    }
    public function getError($id)
    {

        if(!Request::segment(3))
            return Redirect::to(url(''));

        $payment = Payment::where('user_id',Auth::user()->id)
            ->where('id',Request::segment(3))
            ->where('paid',0)
            ->first();

        $payment->payment_data = unserialize($payment->payment_data);
        $data = array_merge($payment->payment_data,$payment->toArray());
        $data['settings'] = $this->_setting;
        Mail::send('emails.user_payment_failed', $data, function($message) use ($data)
        {
            $message
                ->to(Auth::user()->email, $data['settings']['siteName'.ucfirst(LANG_SHORT)])
                ->subject(trans('main.Payment Failed - Try Again Later'));
        });

        return View::make('payments.error')
            ->with('data',$data)
            ->with('payment',$payment)
            ->with('order',$data['order'])
            ->with('title',trans('main.Payment Failed'));
    }
}
