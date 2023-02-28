<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Invoice </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body class="page-header-fixed">

<div class="container">



    <div class="row">
        <div class="col">
            Invoice Number :{{$invoice->unique_id}}
        </div>
        <div class="col" style="margin-left: 490px">
           <img  src="https://www.dietfix.com/img/rsz_dietfix-logo.png">
            {{str_replace("-","/",substr($invoice->created_at,0,10))}}
        </div>

    </div>
    <div class="row">
        <div class="col">
            Username: {{$invoice->user->username}}
        </div>
    </div>
    <div class="row">
        <div class="col">
            Mobile: {{$invoice->user->mobile_number}}
        </div>
    </div>
    <div class="row">
        <div class="col">
            Phone: {{$invoice->user->phone}}
        </div>
    </div>
    <div class="row">
        <div class="col">
            Email: {{$invoice->user->email}}
        </div>
    </div>

     <br/>
     <br/>

    <div class="row">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Package</th>
                <th scope="col">Package Duration</th>
                <th scope="col">Count</th>
                <th scope="col">Sum</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>{{$invoice->package->titleEn}}</td>
                    <td>{{$invoice->packageDuration->titleEn}}</td>
                    <td>{{$invoice->count}}</td>
                    <td>{{$invoice->sum}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col">
             {{$invoice->description}}
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

