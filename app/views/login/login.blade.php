




{{--'email:' . $email --}}
{{-- 'password:' . $password --}}


{{ Form::open(array('url'=>'login', 'method' => 'post')) }}


	{{ Form::label('email', 'Email') }}
	{{ Form::text('email') }}


	{{ Form::label('password', 'Clave') }}
	{{ Form::password('password') }}

	{{ Form::submit('Save') }}

{{ Form::close() }}


@foreach($errors->all() as $message)
	<li>{{ $message }}</li>
@endforeach



