@extends('layouts.calendarLayouts')

@section('content')
    <div>
        <select  id="month" name="month" class="form-control">
            <option value="00">Select Month</option>
            <option value='01'>January</option>
            <option value='02'>February</option>
            <option value='03'>March</option>
            <option value='04'>April</option>
            <option value='05'>May</option>
            <option value='06'>June</option>
            <option value='07'>July</option>
            <option value='08'>August</option>
            <option value='09'>September</option>
            <option value='10'>October</option>
            <option value='11'>November</option>
            <option value='12'>December</option>
        </select>
    </div>
    @if(isset($month))
        {!! $calendar->calendar($month) !!}
    @else
        {!! $calendar->calendar(date('m', strtotime('0 month'))) !!}
    @endif


@stop

@section('scripts')
    <script type="text/javascript">//        $(document).ready(function() {
        $('select[name="month"]').on('change', function () {
            var month = $(this).val();
            window.location.href = "{{url('calendar')}}?month="+month;

        });
    </script>
    {!! $calendar->script() !!}
    @stop