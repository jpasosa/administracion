
@extends('template_tisa/layout')

@section('page_content')
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Pagomiscuentas :: Generación de FACTURACIÓN</strong>
					(solamente podemos subir un archivo de facturación en el día.)
				</div>

				@if ($errors->has())
					<code>
						@foreach ($errors->all() as $error)
							{{ $error }} <br />
						@endforeach
					</code>
				@endif
				<div class="panel-body">
					<!-- <form data-parsley-validate> -->
					{{ Form::open(array('action'=>'PagomiscuentasController@index', 'method' => 'post', 'files'=>true)) }}
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label for="file" class="req">Archivo</label>
									{{ Form::file('file', $attributes = array('class'=>'form-control', 'data-parsley-required'=>true, 'id'=>'val_first_name')) }}
									<!-- <input type="file" name="file" id="val_first_name" class="form-control" data-parsley-required="true"> -->
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="radio-inline">
								<label>
									<!-- <input type="radio" value="antes" id="val_radio1" name="horario" data-parsley-errors-container="#inline_elements" data-parsley-required="true" data-parsley-multiple="radio_group"> -->
									{{Form::radio('horario', 'antes', Input::get('horario'),
											array('id'=>'val_radio1',
														'data-parsley-errors-container'=>'#inline_elements',
														'data-parsley-required'=>"true",
														'data-parsley-multiple'=>"radio_group"))
									}}
									Voy a subirlo antes de las 14.30hs
								</label>
							</div>
							<div class="radio-inline">
								<label>
									<!-- <input type="radio" value="despues" id="val_radio2" name="horario" data-parsley-errors-container="#inline_elements" data-parsley-multiple="radio_group"> -->
									{{Form::radio('horario', 'despues', Input::get('horario'),
											array('id'=>'val_radio2',
														'data-parsley-errors-container'=>'#inline_elements',
														'data-parsley-multiple'=>"radio_group"))
									}}
									Voy a subirlo después de las 14.30hs
								</label>
							</div>
							<div id="inline_elements"></div>
						</div>

						<div class="form-sep">
							<button class="btn btn-primary">Generar Archivo</button>
						</div>
					<!-- </form> -->
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>
@stop



