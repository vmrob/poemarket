<?= Template::Render('header', $this->args) ?>

<div class="container">
	<div class="page-header">
		<h1><?= htmlspecialchars($this->header) ?></h1>
	</div>
	
	<p class="lead"><?= htmlspecialchars($this->message) ?></p>
</div>

<?= Template::Render('footer', $this->args) ?>
