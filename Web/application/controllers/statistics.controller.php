<?php
	if(!isLogged())
	{
		header('Location: ?p1=login');
		exit;
	}
	class Controller_Statistics extends Controller
	{	
		function __construct()
		{
			$this->model = new Model_Statistics();
			$this->view = new View();
		}
		public function action_index()
		{
			$data = array('title' => 'Statistics',
						  'reports' => $this->model->reportsInfo(),
						  'os' => $this->model->osInfo(),
						  'country' => $this->model->countryInfo(),
						  'arc' => $this->model->arcInfo());
			$this->view->generate('statistics.view.php', 'template.view.php', $data);
		}
	}
?>