{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name') !!}
		</li>
		<li>
			{!! Form::label('age', 'Age:') !!}
			{!! Form::text('age') !!}
		</li>
		<li>
			{!! Form::label('grade_id', 'Grade_id:') !!}
			{!! Form::text('grade_id') !!}
		</li>
		<li>
			{!! Form::label('parent_id', 'Parent_id:') !!}
			{!! Form::text('parent_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}