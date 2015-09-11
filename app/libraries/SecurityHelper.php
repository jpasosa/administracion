<?php

class SecurityHelper
{


	static function isLogin()
	{
		if ( 	!Auth::check() )
		{
			return View::make('template_tisa/login');
		}

		return true;
	}

}
