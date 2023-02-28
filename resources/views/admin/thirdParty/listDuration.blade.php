<option  selected value="none" >None</option>
@foreach($packageDuration as $duration)
    <option @if($duration->id==$selectedDuration) selected @endif value="{{$duration->id}}">{{$duration->titleEn}}</option>
@endforeach

