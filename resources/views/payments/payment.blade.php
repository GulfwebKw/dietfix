<!DOCTYPE html>
<html lang="en">
<head>
    <title>MyFatoorah Payment Demo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    <!-- Inline CSS based on choices in "Settings" tab -->
    <style>.bootstrap-iso .formden_header h2, .bootstrap-iso .formden_header p, .bootstrap-iso form{font-family: Arial, Helvetica, sans-serif; color: black}.bootstrap-iso form button, .bootstrap-iso form button:hover{color: white !important;} .asteriskField{color: red;}</style>
</head>
<body>

<div class="container">

    <!-- HTML Form (wrapped in a .bootstrap-iso div) -->
    <div class="bootstrap-iso">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 text-center">
                    <div class="form-group ">
                        <label class="control-label " for="text">
                            TEST API URL:
                            https://apidemo.myfatoorah.com/swagger/ui/index#/ApiInvoices
                        </label>
                        <label class="control-label " for="text">
                            Test Account: apiaccount@myfatoorah.com<br>
                            Password: api12345*
                        </label>
                    </div>
                    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                        <div class="form-group ">
                            <label class="control-label requiredField" for="name">
                                Enter User Name
                                <span class="asteriskField">*</span>
                            </label>
                            <input class="form-control" id="username" name="username" placeholder="username" type="text" required/>
                        </div>
                        <div class="form-group ">
                            <label class="control-label requiredField" for="name">
                                Enter Password
                                <span class="asteriskField">*</span>
                            </label>
                            <input class="form-control" id="password" name="password" placeholder="password" type="passsword" required/>
                        </div>
                        <div class="form-group">
                            <div>
                                <button class="btn btn-primary " name="SubmitButton" type="submit">
                                    Generate Token
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <br>
                    <a href="/payment"><button type="button" id=""  class="btn btn-success">Click here to Test Payment Link</button></a>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 text-center">
                    <?php
                    if(isset($_POST['SubmitButton'])){
                        //check if form was submitted
                        if(empty($_POST['username']) || empty($_POST['password']))
                        {
                            echo "You did not fill out the required fields.";
                            die();  // Note this
                        }else{

                            $username=$_POST['username'];
                            $password=$_POST['password'];
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL,'https://apidemo.myfatoorah.com/Token');
                            curl_setopt($curl, CURLOPT_POST, 1);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('grant_type' => 'password','username' => $username,'password' =>$password)));
                            $result = curl_exec($curl);
                            $info = curl_getinfo($curl);
                            curl_close($curl);
                            $json = json_decode($result, true);
                            if(isset($json['access_token']) && !empty($json['access_token'])){
                                $access_token= $json['access_token'];
                            }else{
                                $access_token='';
                            }
                            if(isset($json['token_type']) && !empty($json['token_type'])){
                                $token_type= $json['token_type'];
                            }else{
                                $token_type='';
                            }
                            //  echo $access_token;

                            if(isset($json['access_token']) && !empty($json['access_token']))
                            {
                                echo "Token Generated Successfully.<br>";

                                $t= time();
                                $name = "Demo Name";
                                $post_string = '{
                                                "InvoiceValue": 10,
                                                "CustomerName": "'.$name.'",
                                                "CustomerBlock": "Block",
                                                "CustomerStreet": "Street",
                                                "CustomerHouseBuildingNo": "Building no",
                                                "CustomerCivilId": "123456789124",
                                                "CustomerAddress": "Payment Address",
                                                "CustomerReference": "'.$t.'",
                                                "DisplayCurrencyIsoAlpha": "KWD",
                                                "CountryCodeId": "+965",
                                                "CustomerMobile": "1234567890",
                                                "CustomerEmail": "test@gmail.com",
                                                "DisplayCurrencyId": 3,
                                                "SendInvoiceOption": 1,
                                                "InvoiceItemsCreate": [
                                                  {
                                                    "ProductId": null,
                                                    "ProductName": "Product01",
                                                    "Quantity": 1,
                                                    "UnitPrice": 10
                                                  }
                                                ],
                                               "CallBackUrl":  "http://dietfix.net/payment",
                                                "Language": 2,
                                                 "ExpireDate": "2022-12-31T13:30:17.812Z",
                                      "ApiCustomFileds": "weight=10,size=L,lenght=170",
                                      "ErrorUrl": "http://www.bing.com"
                                              }';
                                $soap_do     = curl_init();
                                curl_setopt($soap_do, CURLOPT_URL, "https://apidemo.myfatoorah.com/ApiInvoices/CreateInvoiceIso");
                                curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
                                curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
                                curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
                                curl_setopt($soap_do, CURLOPT_POST, true);
                                curl_setopt($soap_do, CURLOPT_POSTFIELDS, $post_string);
                                curl_setopt($soap_do, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($post_string),  'Accept: application/json','Authorization: Bearer '.$access_token));
                                $result1 = curl_exec($soap_do);
                                // echo "<pre>";print_r($result1);die;
                                $err    = curl_error($soap_do);
                                $json1= json_decode($result1,true);
                                $RedirectUrl= $json1['RedirectUrl'];
                                $ref_Ex=explode('/',$RedirectUrl);
                                $referenceId =  $ref_Ex[4];
                                curl_close($soap_do);
                                echo '<br><button type="button" id="paymentRedirect"  class="btn btn-success">Click here to Payment Link</button>';
                                echo'<script type="text/javascript">
    $("#paymentRedirect").click(function(e) {
      window.location="'.$RedirectUrl.'";
    });
    </script>';
                            }else{
                                //print_r($json);
                                print_r("Error: ".$json['error']."<br>Description: ".$json['error_description']);
                            }

                        }
                    }
                    //call back to same index page after payment page redirect
                    if(isset($_GET['paymentId'])){
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL,'https://apidemo.myfatoorah.com/Token');
                        curl_setopt($curl, CURLOPT_POST, 1);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('grant_type' => 'password','username' => 'apiaccount@myfatoorah.com','password' => 'api12345*')));
                        $result = curl_exec($curl);
                        $error= curl_error($curl);
                        $info = curl_getinfo($curl);
                        curl_close($curl);
                        $json = json_decode($result, true);
                        $access_token= $json['access_token'];
                        $token_type= $json['token_type'];
                        if(isset($_GET['paymentId']))
                        {
                            $id=$_GET['paymentId'];
                        }
                        $password= 'api12345*';
                        $url = 'https://apidemo.myfatoorah.com/ApiInvoices/Transaction/'.$id;
                        $soap_do1 = curl_init();
                        curl_setopt($soap_do1, CURLOPT_URL,$url );
                        curl_setopt($soap_do1, CURLOPT_CONNECTTIMEOUT, 10);
                        curl_setopt($soap_do1, CURLOPT_TIMEOUT, 10);
                        curl_setopt($soap_do1, CURLOPT_RETURNTRANSFER, true );
                        curl_setopt($soap_do1, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($soap_do1, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($soap_do1, CURLOPT_POST, false );
                        curl_setopt($soap_do1, CURLOPT_POST, 0);
                        curl_setopt($soap_do1, CURLOPT_HTTPGET, 1);
                        curl_setopt($soap_do1, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Accept: application/json','Authorization: Bearer '.$access_token));
                        $result_in = curl_exec($soap_do1);
                        $err_in = curl_error($soap_do1);
                        $file_contents = htmlspecialchars(curl_exec($soap_do1));
                        curl_close($soap_do1);
                        $getRecorById = json_decode($result_in, true);
                        echo'<div class="form-group "><label class="control-label" for="name">
        Invoice id : '.$getRecorById['InvoiceId'].'
         </label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
         InvoiceReference : '.$getRecorById['InvoiceReference'].'
          </label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
          CreatedDate : '.$getRecorById['CreatedDate'].'
           </label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
             ExpireDate : '.$getRecorById['ExpireDate'].'
              </label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
                   InvoiceValue : '.$getRecorById['InvoiceValue'].'
                    </label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  Comments : '.$getRecorById['Comments'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  CustomerName : '.$getRecorById['CustomerName'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  CustomerMobile : '.$getRecorById['CustomerMobile'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  CustomerEmail : '.$getRecorById['CustomerEmail'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  TransactionDate : '.$getRecorById['TransactionDate'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  TransactionDate : '.$getRecorById['TransactionDate'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  PaymentGateway : '.$getRecorById['PaymentGateway'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  ReferenceId : '.$getRecorById['ReferenceId'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  TrackId : '.$getRecorById['TrackId'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  TransactionId : '.$getRecorById['TransactionId'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  PaymentId : '.$getRecorById['PaymentId'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  AuthorizationId : '.$getRecorById['AuthorizationId'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  OrderId : '.$getRecorById['OrderId'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  TransactionStatus : '.$getRecorById['TransactionStatus'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  Error : '.$getRecorById['Error'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  PaidCurrency : '.$getRecorById['PaidCurrency'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  PaidCurrencyValue : '.$getRecorById['PaidCurrencyValue'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  TransationValue : '.$getRecorById['TransationValue'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  CustomerServiceCharge : '.$getRecorById['CustomerServiceCharge'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  DueValue : '.$getRecorById['DueValue'].'</label><br>';
                        echo'<div class="form-group "><label class="control-label" for="name">
  Currency : '.$getRecorById['Currency'].'</label><br>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
