<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8"/>
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/animate.css">
		<link rel="stylesheet" href="assets/css/august.css">
		<link rel="shortcut icon" href="assets/img/favicon.ico"/>
		<script src="assets/js/jquery.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/bootstrap-notify.min.js"></script>
		<title>AUGUST: <?=mb_strtoupper($data['title']);?></title>
		<script>
			$(document).ready(function()
			{
				var title = $('title').text().split(':')[1].trim();
				$('ul[name="mainbar"] li').each(function()
				{
					if(!$(this).hasClass("dropdown") && !$(this).hasClass("divider"))
					{
						var liTitle = $(this).text().toUpperCase();
						if(title == liTitle)
						{
							$(this).addClass("active");
						}
					}
				});
			});
		</script>
	</head>
	<body>
		<div class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand">August</a>
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse" id="navbar-main">
					<ul name="mainbar" class="nav navbar-nav">
						<li><a href="?p1=statistics">Statistics</a></li>
						<li><a href="?p1=storage">Storage</a></li>
						<li><a href="?p1=logs">Logs</a></li>
						<li><a href="?p1=settings">Settings</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="?p1=logout">Logout</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="page-header">
				<div class="row">
					<div class="col-lg-8 col-md-7 col-sm-6" style="margin-top: 50px;">
						<h1>August</h1>
					</div>
				</div>
			</div>
			<?php include_once("application/views/$content_view"); ?>
		</div>
	</body>
</html>