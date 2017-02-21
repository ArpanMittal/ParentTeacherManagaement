{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('parent_id', 'Parent_id:') !!}
			{!! Form::text('parent_id') !!}
		</li>
		<li>
			{!! Form::label('teacher_id', 'Teacher_id:') !!}
			{!! Form::text('teacher_id') !!}
		</li>
		<li>
			{!! Form::label('isApproved', 'IsApproved:') !!}
			{!! Form::text('isApproved') !!}
		</li>
		<li>
			{!! Form::label('isCancel', 'IsCancel:') !!}
			{!! Form::text('isCancel') !!}
		</li>
		<li>
			{!! Form::label('teacherAppointmentsSlot_id', 'TeacherAppointmentsSlot_id:') !!}
			{!! Form::text('teacherAppointmentsSlot_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}