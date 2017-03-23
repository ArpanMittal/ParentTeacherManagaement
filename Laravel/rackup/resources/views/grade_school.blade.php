{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('grad_id', 'Grad_id:') !!}
			{!! Form::text('grad_id') !!}
		</li>
		<li>
			{!! Form::label('school_id', 'School_id:') !!}
			{!! Form::text('school_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}