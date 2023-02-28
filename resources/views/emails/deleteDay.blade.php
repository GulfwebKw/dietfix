
delete order and date by user <br>
<div >
    <br>UserId : {{$userId}}
    <br>Delete  Days
    @foreach($days as $item)
     <br>   {{$item}}
    @endforeach

    <h3>new created days</h3>

    @foreach($newDays as $new)
        <br>   {{$new}}
    @endforeach


</div>;
