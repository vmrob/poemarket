<!DOCTYPE html>
<html>
<head>
<title><?= htmlspecialchars($this->page_title) ?></title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<script src="js/jquery.min.js"></script>
</head>
<body>

<div id="wrap">

<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="index.php"><?= htmlspecialchars($this->site_name) ?></a>
			<ul class="nav">
				<li<?= basename($_SERVER['SCRIPT_NAME']) == 'index.php' ? ' class="active"' : '' ?>><a href="index.php">Home</a></li>
				<li<?= basename($_SERVER['SCRIPT_NAME']) == 'login.php' ? ' class="active"' : '' ?>><a href="login.php">Log In</a></li>
			</ul>
			<div class="pull-right navbar-text">
				<?php
				if ($this->logged_in_user) {
					?>
					Welcome, <b><?= htmlspecialchars($this->logged_in_user['user_name']) ?></b>.
					<?php
				} else {
					?>
					Not logged in.
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
