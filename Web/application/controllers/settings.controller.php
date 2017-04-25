<?php
	if(!isLogged())
	{
		header('Location: ?p1=login');
		exit;
	}
	class Controller_Settings extends Controller
	{	
		function __construct()
		{
			$this->model = new Model_Settings();
			$this->view = new View();
		}
		public function action_index()
		{
			$content = array('title' => 'Settings');
			$this->view->generate('settings.view.php', 'template.view.php', $content);
		}
		public function action_change()
		{
			if(!isset($_POST['us_login']) || !isset($_POST['us_password']) || !isset($_POST['ac_passwords']) || !isset($_POST['ac_cookies']) || !isset($_POST['ac_docs']) ||
			   !isset($_POST['ac_docs_ft']) || !isset($_POST['ac_docs_dr']) || !isset($_POST['ac_docs_sz']) || !isset($_POST['ac_docs_st']) || !isset($_POST['ac_wallets']) || 
			   !isset($_POST['ac_messengers']) || !isset($_POST['ac_ftp_cl']) || !isset($_POST['ac_rdp']) || !isset($_POST['ac_mail']) || !isset($_POST['al_duplicate'])) exit;
			echo $this->model->saveChanges($_POST['us_login'], $_POST['us_password'], $_POST['ac_passwords'], $_POST['ac_cookies'], $_POST['ac_docs'], 
										   $_POST['ac_docs_ft'], $_POST['ac_docs_dr'], $_POST['ac_docs_sz'], $_POST['ac_docs_st'], $_POST['ac_wallets'], 
										   $_POST['ac_messengers'], $_POST['ac_ftp_cl'], $_POST['ac_rdp'], $_POST['ac_mail'], $_POST['al_duplicate']);;
		}
		public function action_clean()
		{
			$this->model->cleanAll();
		}
	}
?>