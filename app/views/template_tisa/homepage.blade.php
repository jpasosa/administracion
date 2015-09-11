@extends('template_tisa/layout')



@section('page_content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Mensajes de acciones realizadas.</div>
					<div class="panel-body">
						@if(Session::has('success') || Session::has('error'))
							@if(Session::has('success'))
								<div class="alert alert-success alert-dismissable fade in">
									<button type="button" class="close" data-dismiss="alert">&times;</button>
									<strong>Perfecto</strong>
									{{ Session::get('success') }}
								</div>
							@endif
							@if(Session::has('error'))
								<div class="alert alert-danger alert-dismissable fade in">
									<button type="button" class="close" data-dismiss="alert">&times;</button>
									<strong>Error.</strong>
									{{ Session::get('error') }}
								</div>
							@endif
						@else
							No hay mensajes activos.
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"></div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<a class="link_homepage" href="{{ URL::to('pmc') }}">
					<div class="panel panel-default hover_gris">
						<div class="panel-body">
							<div class="easy_chart easy_chart_pages pull-left" data-percent="95">
								<i class="ion-ios7-cloud-upload-outline"></i>
							</div>
							<div class="easy_chart_desc">
								<h4><strong>PMC::FACTURACION</strong></h4>
								<p>Generar archivo para entregar&hellip;</p>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-4">
				<a class="link_homepage" href="{{ URL::to('pmc_export_excel') }}">
					<div class="panel panel-default hover_gris">
						<div class="panel-body">
							<div class="easy_chart easy_chart_user pull-left" data-percent="95">
								<i class="ion-document-text"></i>
							</div>
							<div class="easy_chart_desc">
								<h4><strong>PMC::COBRANZAS</strong></h4>
								<p>Generar Excel para pasar al Calipso&hellip;</p>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-4">
				<a class="link_homepage" target="_blank" href="https://empresas.pagomiscuentas.com/pmctas/PMCtasEmp_WebSite/login.aspx">
					<div class="panel panel-default hover_gris">
						<div class="panel-body">
							<div class="easy_chart easy_chart_images pull-left" data-percent="95"><i class="ion-images"></i></div>
							<div class="easy_chart_desc">
								<h4><strong>PMC::SITIO</strong></h4>
								<p>Sitio de Pagomiscuentas&hellip;</p>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<a class="link_homepage" href="{{ URL::to('listado_facturas') }}">
					<div class="panel panel-default hover_gris">
						<div class="panel-body">
							<div class="easy_chart easy_chart_pages pull-left" data-percent="95">
								<i class="ion-navicon-round"></i>
							</div>
							<div class="easy_chart_desc">
								<h4><strong>PMC::LISTADOS DE FACTURACION</strong></h4>
								<p>Listado de Archivos Para entregar ya realizados.&hellip;</p>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-4">
				<a class="link_homepage" href="{{ URL::to('listado_cobranzas') }}">
					<div class="panel panel-default hover_gris">
						<div class="panel-body">
							<div class="easy_chart easy_chart_user pull-left" data-percent="95">
								<i class="ion-document-text"></i>
							</div>
							<div class="easy_chart_desc">
								<h4><strong>PMC::LISTADOS DE COBRANZAS</strong></h4>
								<p>Listado de Archivos de cobranza. Excel generados.&hellip;</p>
							</div>
						</div>
					</div>
				</a>
			</div>

		</div>


		<div class="row">
			<!-- <div class="col-md-8"></div> -->
			<div class="col-md-8">
				<div id="mini-clndr">
					<script>
						// todo calendar events
						var currentMonth = moment().format('YYYY-MM'),
							nextMonth    = moment().add('month', 1).format('YYYY-MM');

						todo_events = [

						]
					</script>
					<script id="mini-clndr-template" type="text/template">
						<div class="controls">
							<div class="clndr-previous-button"><span class="glyphicon glyphicon-chevron-left"></span></div><div class="month"><%= month+' '+year %></div><div class="clndr-next-button"><span class="glyphicon glyphicon-chevron-right"></span></div>
						</div>

						<div class="days-container">
							<div class="days">
								<div class="headers">
									<% _.each(daysOfTheWeek, function(day) { %><div class="day-header"><%= day %></div><% }); %>
								</div>
								<% _.each(days, function(day) { %><div class="<%= day.classes %>" id="<%= day.id %>"><%= day.day %></div><% }); %>
							</div>
							<div class="events">
								<div class="headers">
									<div class="x-button"><span class="glyphicon glyphicon-remove"></span></div>
									<div class="event-header">EVENTS</div>
								</div>
								<div class="events-list-wrapper">
									<div class="events-list">
										<% _.each(eventsThisMonth, function(event) { %>
											<div class="event">
												<a href="http://tisa-admin.tzdthemes.com/&lt;%=&#32;event.url&#32;%&gt;"><%= moment(event.date).format('MMM Do') %>: <%= event.title %></a>
											</div>
										  <% }); %>
									</div>
								</div>
							</div>
						</div>
					</script>
				</div>
			</div>
		</div>
	</div>
@stop
