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
				<li class="active"><a href="#">Home</a></li>
				<li><a href="login.php">Log In</a></li>
			</ul>
		</div>
	</div>
</div>