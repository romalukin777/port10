<?php
	header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8"/>
		<title>Page Not Found</title>
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="assets/css/error-page.css"/>
		<link rel="shortcut icon" href="assets/img/favicon.ico"/>
		<script type="text/javascript" src="assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="error-template">
						<h1>
							Oops!</h1>
						<h2>
							404 Not Found</h2>
						<div class="error-details">
							Sorry, an error has occured, Requested page not found!
						</div>
						<div class="error-actions">
							<a href="?p1=statistics" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span> Take Me Home</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>