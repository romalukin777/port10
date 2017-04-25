<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8"/>
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/animate.css">
		<link rel="shortcut icon" href="assets/img/favicon.ico"/>
		<script src="assets/js/jquery.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/bootstrap-notify.min.js"></script>
		<script>
			$(document).ready(function()
			{
				$(document).on('submit', 'form', function()
				{
					$('input[type="submit"]').prop('disabled', true);
					$.post('?p1=login&p2=try',
					{
						login: $('[name="login"]').val(),
						password: $('[name="password"]').val()
					}, function(data)
					{
						if(data.indexOf('Success') != -1)
						{
							setTimeout(function()
							{
								window.location = '?p1=statistics';
							}, 1800);
						}
						else
						{
							setTimeout(function()
							{
								$("input[type='submit']").prop("disabled", false);
							}, 1000);
						}
						$.notify(
						{
							message: data
						},
						{
							animate:
							{
								enter: 'animated zoomInDown',
								exit: 'animated zoomOutUp'
							},
							type: data.indexOf('Success') != -1 ? 'success' : 'danger',
							delay: 2500,
							placement:
							{
								from: 'bottom',
								align: 'left'
							}
						});
					});
					return false;
				});
			});
		</script>
		<title>Authorization</title>
	</head>
	<body style="background: url('assets/img/bg.png')">
		<div class="container">
			<div class="row">
				<div style="margin-top: 100px;" class="col-md-6 col-md-offset-3">
					<div class="panel panel-default">
						<div class="panel-heading">Login</div>
						<div class="panel-body" style="margin-bottom: -15px;">
							<form>
								<div class="form-group">
									<input class="form-control" id="focusedInput" name="login" type="text" placeholder="Username">
								</div>
								<div class="form-group">
									<input class="form-control" id="focusedInput" name="password" type="password" placeholder="Password">
								</div>
								<div class="form-group">
									<input class="btn btn-default btn-block" type="submit" value="Ok">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>