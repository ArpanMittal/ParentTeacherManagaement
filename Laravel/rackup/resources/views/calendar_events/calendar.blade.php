@extends('layouts.calendarLayouts')

@section('content')
    <div>
        <select  id="month" name="month" class="form-control">
            <option value="00">Select Month</option>
            <option value={{'01'.$year = date('Y')-1}}>January{{date('Y')-1}}</option>
            <option value={{'02'.$year = date('Y')-1}}>February{{date('Y')-1}}</option>
            <option value={{'03'.$year = date('Y')-1}}>March{{date('Y')-1}}</option>
            <option value={{'04'.$year = date('Y')-1}}>April{{date('Y')-1}}</option>
            <option value={{'05'.$year = date('Y')-1}}>May{{date('Y')-1}}</option>
            <option value={{'06'.$year = date('Y')-1}}>June{{date('Y')-1}}</option>
            <option value={{'07'.$year = date('Y')-1}}>July{{date('Y')-1}}</option>
            <option value={{'08'.$year = date('Y')-1}}>August{{date('Y')-1}}</option>
            <option value={{'09'.$year = date('Y')-1}}>September{{date('Y')-1}}</option>
            <option value={{'10'.$year = date('Y')-1}}>October{{date('Y')-1}}</option>
            <option value={{'11'.$year = date('Y')-1}}>November{{date('Y')-1}}</option>
            <option value={{'12'.$year = date('Y')-1}}>December{{date('Y')-1}}</option>
            <option value={{'01'.$year = date('Y')}}>January{{date('Y')}}</option>
            <option value={{'02'.$year = date('Y')}}>February{{date('Y')}}</option>
            <option value={{'03'.$year = date('Y')}}>March{{date('Y')}}</option>
            <option value={{'04'.$year = date('Y')}}>April{{date('Y')}}</option>
            <option value={{'05'.$year = date('Y')}}>May{{date('Y')}}</option>
            <option value={{'06'.$year = date('Y')}}>June{{date('Y')}}</option>
            <option value={{'07'.date('Y')}}>July{{date('Y')}}</option>
            <option value={{'08'.date('Y')}}>August{{date('Y')}}</option>
            <option value={{'09'.date('Y')}}>September{{date('Y')}}</option>
            <option value={{'10'.date('Y')}}>October{{date('Y')}}</option>
            <option value={{'11'.date('Y')}}>November{{date('Y')}}</option>
            <option value={{'12'.date('Y')}}>December{{date('Y')}}</option>
            <option value={{'01'.(date('Y')+1)}}>January{{date('Y')+1}}</option>
            <option value={{'02'.(date('Y')+1)}}>February{{date('Y')+1}}</option>
            <option value={{'03'.(date('Y')+1)}}>March{{date('Y')+1}}</option>
            <option value={{'04'.(date('Y')+1)}}>April{{date('Y')+1}}</option>
            <option value={{'05'.(date('Y')+1)}}>May{{date('Y')+1}}</option>
            <option value={{'06'.(date('Y')+1)}}>June{{date('Y')+1}}</option>
            <option value={{'07'.(date('Y')+1)}}>July{{date('Y')+1}}</option>
            <option value={{'08'.(date('Y')+1)}}>August{{date('Y')+1}}</option>
            <option value={{'09'.(date('Y')+1)}}>September{{date('Y')+1}}</option>
            <option value={{'10'.(date('Y')+1)}}>October{{date('Y')+1}}</option>
            <option value={{'11'.(date('Y')+1)}}>November{{date('Y')+1}}</option>
            <option value={{'12'.(date('Y')+1)}}>December{{date('Y')+1}}</option>
        </select>
    </div>
    @if(isset($month,$year1))
        {!! $calendar->calendar($month,$year1) !!}
    @else
        {!! $calendar->calendar(date('m', strtotime('0 month')),date('Y', strtotime('0 year'))) !!}
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