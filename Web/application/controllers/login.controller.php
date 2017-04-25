<?php
	if(isLogged())
	{
		header('Location: ?p1=statistics');
		exit;
	}
	class Controller_Login extends Controller
	{	
		function __construct()
		{
			$this->model = new Model_Login();
			$this->view = new View();
		}
		public function action_index()
		{
			$this->view->generate('login.view.php', 'login.view.php');
		}
		public function action_try()
		{
			if(!isset($_POST['login']) || !isset($_POST['password'])) exit;
			echo $this->model->login($_POST['login'], $_POST['password']);
		}
	}
?>