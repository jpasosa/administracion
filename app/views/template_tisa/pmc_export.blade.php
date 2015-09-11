
@extends('template_tisa/layout')

@section('page_content')
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-default">
				<div class="panel-heading">Pagomiscuentas :: Generación del excel subiendo el archivo que nos entregó Pagomiscuentas.</div>
				@if ($errors->has())
					<code>
						@foreach ($errors->all() as $error)
							{{ $error }} <br />
						@endforeach
					</code>
				@endif
				<div class="panel-body">
					<!-- <form data-parsley-validate> -->
					{{ Form::open(array('action'=>'PagomiscuentasController@toExport', 'method' => 'post', 'files'=>true)) }}
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label for="file" class="req">Archivo</label>
									<input type="file" name="file" id="val_first_name" class="form-control" data-parsley-required="true">
								</div>
							</div>
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



