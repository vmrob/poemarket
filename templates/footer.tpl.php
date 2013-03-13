	<div id="push"></div>
</div>

<div id="footer">
	<div class="container">
		<p><?= exec('git log -1 --format="%H %ar"') ?></p>
	</div>
</div>

<script src="js/bootstrap.min.js"></script>
</body>
</html>