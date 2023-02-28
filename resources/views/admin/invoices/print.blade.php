<html>
<head><meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <style type="text/css">
        <!--
        span.cls_003{font-family:Arial,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
        div.cls_003{font-family:Arial,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
        span.cls_004{font-family:Arial,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
        div.cls_004{font-family:Arial,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
        span.cls_005{font-family:Arial,serif;font-size:14.1px;color:rgb(255,255,255);font-weight:bold;font-style:normal;text-decoration: none}
        div.cls_005{font-family:Arial,serif;font-size:14.1px;color:rgb(255,255,255);font-weight:bold;font-style:normal;text-decoration: none}
        span.cls_006{font-family:Arial,serif;font-size:10.1px;color:rgb(255,255,255);font-weight:bold;font-style:normal;text-decoration: none}
        div.cls_006{font-family:Arial,serif;font-size:10.1px;color:rgb(255,255,255);font-weight:bold;font-style:normal;text-decoration: none}
        span.cls_007{font-family:Arial,serif;font-size:10.1px;color:rgb(255,255,255);font-weight:normal;font-style:normal;text-decoration: none}
        div.cls_007{font-family:Arial,serif;font-size:10.1px;color:rgb(255,255,255);font-weight:normal;font-style:normal;text-decoration: none}
        span.cls_008{font-family:Arial,serif;font-size:9.1px;color:rgb(255,255,255);font-weight:bold;font-style:normal;text-decoration: none}
        div.cls_008{font-family:Arial,serif;font-size:9.1px;color:rgb(255,255,255);font-weight:bold;font-style:normal;text-decoration: none}
        span.cls_002{font-family:Arial,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
        div.cls_002{font-family:Arial,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
        -->
    </style>
    <script type="text/javascript" src="30014288-11ed-11ea-9d71-0cc47a792c0a_id_30014288-11ed-11ea-9d71-0cc47a792c0a_files/wz_jsgraphics.js"></script>
</head>
<body>
<div style="position:absolute;left:50%;margin-left:-306px;top:0px;width:612px;height:792px;border-style:outset;overflow:hidden">
    <div style="position:absolute;left:0px;top:0px">
</div>
        <div style="position:absolute;left:49.65px;top:38.37px" class="cls_003"><span class="cls_003">Client :{{$invoice->user->username}}</span></div>
        <div style="position:absolute;left:49.65px;top:52.15px" class="cls_004"><span class="cls_004">Mobile: {{$invoice->user->mobile_number}}</span></div>
        <div style="position:absolute;left:49.65px;top:52.15px" class="cls_004"><span class="cls_004">Mobile: {{$invoice->user->email}}</span></div>

        <div style="position:absolute;left:49.65px;top:65.93px" class="cls_004"><span class="cls_004">Package: {{$invoice->package->title_ent}}</span></div>
        <div style="position:absolute;left:49.65px;top:107.27px" class="cls_004"><span class="cls_004">Date: {{$invoice->created_at}}</span></div>
        <div style="position:absolute;left:49.65px;top:121.05px" class="cls_004"><span class="cls_004"> </span><A HREF="http://www.gulfclick.net/">dietfix.com</A> </div>


        <div style="position:absolute;left:49.50px;top:320.73px" class="cls_003"><span class="cls_003"></span><span class="cls_004">{{$invoice->user->id}} -  {{$invoice->user->username}}</span></div>
        <div style="position:absolute;left:435.76px;top:320.73px" class="cls_004"><span class="cls_004">{{$invoice->unique_id}}</span></div>

        <div style="position:absolute;left:473.83px;top:320.73px" class="cls_004"><span class="cls_004">{{$invoice->sum}}</span></div>
        <div style="position:absolute;left:537.38px;top:320.73px" class="cls_004"><span class="cls_004">{{$invoice->created_at}}</span></div>
        <div  class="cls_004"><span class="cls_004">{{$invoice->description}}</span></div>

    </div>

</body>
</html>
