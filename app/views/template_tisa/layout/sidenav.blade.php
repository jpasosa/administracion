
<ul>
	<li>
		<a href="{{ URL::to('homepage') }}"><span class="ion-speedometer"></span> <span class="nav_title">Homepage</span></a>
	</li>
	<li>
		<a href="index.html#">
			<span class="label label-danger">32</span>
			<span class="ion-clipboard"></span>
			<span class="nav_title">PAGOS</span>
		</a>
		<div class="sub_panel">
			<div class="side_inner">
				<h4 class="panel_heading panel_heading_first fondo_azul_ally">:: pagomiscuentas ::</h4>
				<ul>
					<li>
						<a href="{{ URL::to('pmc') }}" title="Generar archivo para entregar">
							<span class="side_icon ion-ios7-folder-outline"></span>
							<strong>FACTURACION</strong>
						</a>
					</li>
					<li>
						<a href="{{ URL::to('pmc_export_excel') }}" title="Generar excel para pasar al CALIPSO">
							<span class="side_icon ion-ios7-folder-outline"></span>
							<strong>COBRANZAS</strong>
						</a>
					</li>
					<li>
						<a href="https://empresas.pagomiscuentas.com/pmctas/PMCtasEmp_WebSite/login.aspx" title="ir a pagomiscuentas">
							<span class="side_icon ion-ios7-folder-outline"></span>
							<strong>SITIO</strong>
						</a>
					</li>
					<li>
						<a href="{{ URL::to('listado_facturas') }}" title="Listado de Archivos Para entregar ya realizados.">
							<span class="side_icon ion-ios7-folder-outline"></span>
							<strong>LISTADO FACTURACION</strong>
						</a>
					</li>
					<li>
						<a href="{{ URL::to('listado_cobranzas') }}" title="Listado de Archivos de cobranza. Excel generados.">
							<span class="side_icon ion-ios7-folder-outline"></span>
							<strong>LISTADO COBRANZAS</strong>
						</a>
					</li>
				</ul>
				<h4 class="panel_heading fondo_azul_ally">:: pagofacil ::</h4>
				<ul>
					<li>
						<a href="#">
							<span class="side_icon ion-ios7-folder-outline"></span>
							<span class="badge badge-primary">7</span>subir archivos
						</a>
					</li>
				</ul>
				<h4 class="panel_heading fondo_azul_ally">:: débitos automáticos ::</h4>
				<ul>
					<li>
						<a href="#">
							<span class="side_icon ion-ios7-folder-outline"></span>
							<span class="badge badge-primary">7</span>
							ingreso clientes
						</a>
					</li>
				</ul>
			</div>
		</div>
	</li>
	<li>
		<a href="http://intranet.allytech.com/Home" target="_blank">
			<span class="ion-paper-airplane"></span>
			<span class="nav_title">INTRANET</span>
		</a>
	</li>
	<li>
		<a href="http://10.0.0.234/appserver/explorar.asp" target="_blank">
			<span class="ion-bag"></span>
			<span class="nav_title">CALIPSO</span>
		</a>
	</li>
	<li>
		<a href="{{ URL::to('logout') }}">
			<span class="label label-success">14</span>
			<span class="ion-ios7-email-outline"></span>
			<span class="nav_title">SALIR</span>
		</a>
	</li>

</ul>
