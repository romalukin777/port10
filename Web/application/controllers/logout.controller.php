<?php
	if(!isLogged())
	{
		header('Location: ?p1=login');
		exit;
	}
	class Controller_Logout extends Controller
	{
		public function action_index()
		{
			$_SESSION = array();
			header('Location: ?p1=login');
		}
	}
?>