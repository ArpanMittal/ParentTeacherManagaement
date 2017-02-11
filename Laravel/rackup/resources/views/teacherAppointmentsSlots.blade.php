{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('teacher_id', 'Teacher_id:') !!}
			{!! Form::text('teacher_id') !!}
		</li>
		<li>
			{!! Form::label('duration', 'Duration:') !!}
			{!! Form::text('duration') !!}
		</li>
		<li>
			{!! Form::label('day', 'Day:') !!}
			{!! Form::text('day') !!}
		</li>
		<li>
			{!! Form::label('startTime', 'StartTime:') !!}
			{!! Form::text('startTime') !!}
		</li>
		<li>
			{!! Form::label('isBooked', 'IsBooked:') !!}
			{!! Form::text('isBooked') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}