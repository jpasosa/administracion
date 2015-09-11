<div class="container-fluid">
	<div class="navbar-header">
		<a href="{{ URL::to('homepage') }}" class="navbar-brand">
		<img src="assets/img/blank.gif" alt="Logo"></a>
	</div>
	<ul class="top_links">
	</ul>
	<ul class="nav navbar-nav navbar-right">
		<li class="lang_menu">
			<a href="index.html#" class="dropdown-toggle" data-toggle="dropdown">
				<span class="flag-BR"></span> <span class="caret"></span>
			</a>
			<ul class="dropdown-menu dropdown-menu-right">
			<li><a href="#"><span class="flag-FR"></span> France</a></li>
			<li><a href="#"><span class="flag-IN"></span> India</a></li>
				<li><a href="#"><span class="flag-BR"></span> Brasil</a></li>
				<li><a href="#"><span class="flag-GB"></span> UK</a></li>
			</ul>
		</li>
		<li class="user_menu">
			<a href="index.html#" class="dropdown-toggle" data-toggle="dropdown">
				<!-- <span class="navbar_el_icon ion-person"></span> -->
				<img src="{{ asset('uploads/perfil/' . Session::get('image_user')) }}" width="28" height="28" />
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu dropdown-menu-right">
				<li><a href="{{ URL::to('perfil') }}">Mi Perfil</a></li>
				<li class="divider"></li>
				<li><a href="{{ URL::to('logout') }}">Salir</a></li>
			</ul>
		</li>
	</ul>
</div>