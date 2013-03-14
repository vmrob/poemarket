<div class="container">
	<div class="page-header">
		<h1>Welcome!</h1>
	</div>

<?php
if ($this->error) {
	?>
	<div class="alert alert-error">
		<strong>Whoops!</strong> <?= htmlspecialchars($this->error) ?>
	</div>
	<?php
}
?>

	<div class="row">
		<div class="span8">
			<p>You can sign in using your Path of Exile account and the form to the right.</p>
			<p>Yes, we are asking for your Path of Exile email address and password.</p>
			<p>Yes, giving out your Path of Exile email address and password is a <i>really, really</i> bad idea 99.9% of the time.</p>
			<p>However, giving it to us is safe, and we believe we can earn your trust. We won't store your password in any form, so even in case of a full database breach, your account is perfectly safe.</p>
			<p>We'll put more effort into earning your trust as the site nears completion, but for now hopefully it'll suffice to provide you with the <a href="https://github.com/vmrob/poemarket" target="_blank">unabridged source code of the site</a>.</p>
		</div>
		<div class="span4">
			<form action="" method="post">
				<input type="text" class="input-block-level" placeholder="email address" name="email"<?= post_value_attrib('email') ?> />
				<input type="password" class="input-block-level" placeholder="password" name="password"<?= post_value_attrib('password') ?> />
				<button class="btn btn-large btn-primary" type="submit" name="login">Log In</button>
			</form>
		</div>
	</div>
</div>
