
@extends('template_tisa/layout')

@section('page_content')
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-default">
				<div class="panel-heading">Edición del Perfil.</div>
				@if ($errors->has())
					<code>
						@foreach ($errors->all() as $error)
							{{ $error }} <br />
						@endforeach
					</code>
				@endif
				<div class="panel-body">
					{{ Form::open(array('action'=>'PerfilController@index', 'method' => 'post', 'files'=>true)) }}
					<!-- <form data-parsley-validate="" novalidate=""> -->
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label for="val_first_name" class="req">Nombre</label>
									{{Form::text('name', $user->name, array('id'=>'val_first_name', 'class'=>'form-control', 'data-parsley-required'=>'true', 'data-parsley-id'=>'0569', ))}}
									<!-- <input type="text" name="val_first_name" id="val_first_name" class="form-control" data-parsley-required="true" data-parsley-id="0569"> -->
									<ul class="parsley-errors-list" id="parsley-id-0569"></ul>
								</div>
								<div class="col-lg-6">
									<label for="val_last_name" class="req">Apellido</label>
									{{Form::text('lastname', $user->last_name, array('id'=>'val_first_name', 'class'=>'form-control', 'data-parsley-required'=>'true', 'data-parsley-id'=>'9549', ))}}
									<!-- <input type="text" name="val_last_name" id="val_last_name" class="form-control" data-parsley-required="true" data-parsley-id="9549"> -->
									<!-- <ul class="parsley-errors-list" id="parsley-id-9549">error</ul> -->
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label for="val_first_name" class="req">Email</label>
									{{Form::email('email', $value = $user->email, $attributes = array('class'=>'form-control'))}}
									<!-- <input type="email"  class="form-control" required /> -->
								</div>
								<div class="col-lg-6">
									<label for="val_last_name" class="req">Clave</label>
									<input type="password" name="password" value="{{Crypt::decrypt($user->password)}}" id="val_last_name" class="form-control" data-parsley-required="true" data-parsley-id="9549"><ul class="parsley-errors-list" id="parsley-id-9549"></ul>
									<!-- {{Form::password('password',  array('id'=>'val_last_name', 'class'=>'form-control', 'value'=>'pepe', 'data-parsley-required'=>'true', 'data-parsley-id'=>'9549'))}} -->
									<!-- <input type="text" name="val_last_name" id="val_last_name" class="form-control" data-parsley-required="true" data-parsley-id="9549"><ul class="parsley-errors-list" id="parsley-id-9549"></ul> -->
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label for="file" class="req">Imágen</label>
									{{ Form::file('file', $attributes = array('class'=>'form-control', 'data-parsley-required'=>true, 'id'=>'val_first_name')) }}
									<!-- <input type="file" name="file" id="val_first_name" class="form-control" data-parsley-required="true"> -->
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<img src="{{ asset('uploads/perfil/' . $user->image) }}" width="100" height="100" />
								</div>
							</div>
						</div>

						<div class="form-sep">
						<button class="btn btn-primary">Aceptar</button>
						</div>
					</form>
					{{ Form::close() }}
				</div>
			</div>
		</div>

	</div>
</div>
@stop



