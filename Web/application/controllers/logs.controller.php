<?php
	if(!isLogged())
	{
		header('Location: ?p1=login');
		exit;
	}
	class Controller_Logs extends Controller
	{	
		function __construct()
		{
			$this->model = new Model_Logs();
			$this->view = new View();
		}
		public function action_index()
		{
			$pcount = 0;
			$page = isset($_POST['page']) ? $_POST['page'] : 0;
			$data = array('title' => 'Logs',
						  'logs' => $this->model->getLogs($page, $pcount),
						  'pageid' => $page,
						  'pcount' => $pcount);
			$this->view->generate('logs.view.php', 'template.view.php', $data);
		}
		public function action_clean()
		{
			$this->model->cleanList();
		}
		public function action_delete()
		{
			if(!isset($_POST['unix'])) exit;
			$this->model->delete_($_POST['unix']);
		}
		public function action_download_()
		{
			$this->model->download_();
		}
		public function action_upload()
		{
			if(!isset($_GET['p3']) || !isset($_GET['p4']) || !isset($_GET['p5'])) exit;
			if($this->model->upload_($_GET['p3'], $_GET['p4'], $_GET['p5'], $_GET['p6']) == "jarah")
			{
				$this->view->generate('error.view.php', 'error.view.php', null);
			}
		}
	}
?>