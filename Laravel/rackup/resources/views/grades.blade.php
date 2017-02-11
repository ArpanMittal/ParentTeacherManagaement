{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('grade_name', 'Grade_name:') !!}
			{!! Form::text('grade_name') !!}
		</li>
		<li>
			{!! Form::label('room_number', 'Room_number:') !!}
			{!! Form::text('room_number') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}