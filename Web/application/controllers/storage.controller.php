<?php
	if(!isLogged())
	{
		header('Location: ?p1=login');
		exit;
	}
	class Controller_Storage extends Controller
	{	
		function __construct()
		{
			$this->model = new Model_Storage();
			$this->view = new View();
		}
		public function action_index()
		{
			$path = isset($_POST['path']) ? $_POST['path'] : null;
			$page = isset($_POST['page']) ? $_POST['page'] : 0;
			$pcount = 1;
			$data = array('title' => 'Storage',
						  'body' => $this->model->getContents(str_replace('\\', '/', $path), $page, $pcount),
						  'path' => $path,
						  'pcount' => $pcount,
						  'pageid' => $page);
			$this->view->generate('storage.view.php', 'template.view.php', $data);
		}
		public function action_get()
		{
			if(!isset($_GET['p4']) || !isset($_GET['p3'])) exit;
			echo $this->model->getResource(str_replace('\\', '/', urldecode(base64_decode($_GET['p4']))), $_GET['p3']);
		}
		public function action_rem()
		{
			if(!isset($_POST['path']) || !isset($_POST['type'])) exit;
			echo $this->model->remResource(str_replace('\\', '/', $_POST['path']), $_POST['type']);
		}
	}
?>