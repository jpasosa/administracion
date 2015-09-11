<!DOCTYPE html>

<html>

	<head>
		@include('template_tisa/layout/head')
	</head>

	<body>
		<!-- top bar -->
		<header class="navbar navbar-fixed-top" role="banner">
		@include('template_tisa/layout/header')
		</header>

		<!-- main content -->
		<div id="main_wrapper">
		@include('template_tisa/layout/wrapper')
		</div>

		<!-- side navigation -->
		<nav id="side_nav">
		@include('template_tisa/layout/sidenav')
		</nav>

		<!-- right slidebar -->
		<div id="slidebar">
		@include('template_tisa/layout/rightnav')
		</div>

		<!-- acá había incluidos js y css que los puse dentro de head -->
	</body>

</html>
