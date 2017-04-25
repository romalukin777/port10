<?php
	class Controller_Error extends Controller
	{	
		function __construct()
		{
			$this->view = new View();
		}
		public function action_index()
		{
			$this->view->generate('error.view.php', 'error.view.php');
		}
	}
?>