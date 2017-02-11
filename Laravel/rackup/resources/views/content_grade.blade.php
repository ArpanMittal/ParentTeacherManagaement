{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('grade_id', 'Grade_id:') !!}
			{!! Form::text('grade_id') !!}
		</li>
		<li>
			{!! Form::label('content_id', 'Content_id:') !!}
			{!! Form::text('content_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}