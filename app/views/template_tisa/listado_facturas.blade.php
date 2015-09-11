
@extends('template_tisa/layout')

@section('page_content')

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">Listado de los archivos de Facturación para entregar en Pagomiscuentas</div>
			<div class="panel-body">
				<table id="dt_basic" class="table">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Nombre</th>
							<th>Usuario</th>
							<th>Subido ?</th>
							<th>Acciones</th>
						</tr>
					</thead>


					<tbody>
						@foreach ($files AS $file)
							<tr <?php if ($file->upload == 1) echo 'class="fondo_verde_agua"';  ?> >
								<td>{{ $file->date_generated }}</td>
								<td>{{ $file->name }}</td>
								<td>{{ $file->nombre_usuario }}</td>
								<td>
									@if ($file->upload == 1)
											SUBIDO
									@else
											NO
									@endif
								</td>
								<td>
									<a style="text-decoration: none;" href="{{ URL::to('download_file/' . $file->name  . '/facturacion' ) }}" title="Descargar archivo">
										<li class="ion-arrow-down-a"></li>
									</a>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									@if ($file->upload == 0)
										<a style="text-decoration: none;" href="{{ URL::to('marcar_subido/' . $file->id . '/facturacion' ) }}" title="Marcar como subido">
											<li class="ion-arrow-up-c"></li>
										</a>
									@else
										<a style="text-decoration: none;" href="{{ URL::to('desmarcar_subido/' . $file->id . '/facturacion' ) }}" title="Me confundí. Marco otra vez que no lo subí">
											<li class="ion-alert"></li>
										</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>

				</table>

				{{$files->links()}}


			</div>
		</div>
	</div>







</div>



<div class="row">
	<div class="col-lg-12"></div>
</div>
<div class="row">
	<div class="col-lg-6"></div>
</div>


@stop
