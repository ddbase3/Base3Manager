<!DOCTYPE html>
<html>
	<head>

		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>BASE3 Manager</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<?php foreach ($this->_['assets'] as $asset) { ?>
<?php foreach ($asset as $file) { ?>
<?php if ($file['type'] == 'js') { ?>
		<script src="<?php echo $file['src']; ?>"></script>
<?php } ?>
<?php if ($file['type'] == 'css') { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $file['src']; ?>" />
<?php } ?>
<?php } ?>

<?php } ?>
		<link type="text/css" rel="stylesheet" href="plugin/Base3Manager/assets/layout/base.css" />
		<link type="text/css" rel="stylesheet" href="plugin/Base3Manager/assets/layout/<?php echo $this->_['layout']; ?>/style.css" />
		<script type="text/javascript" src="plugin/Base3Manager/assets/js/script.js"></script>

		<meta name="generator" content="BASE3 XRM" />

	</head>
	<body>

		<div id="head">

			<div id="systemnavi">

<?php /*
				<ul>
					<li>
						<a href="https://account.base3.de/" target="_blank">Benutzerverwaltung</a>
					</li>
				</ul>

				<ul>
					<li>
						<a href="#">Benutzer</a>
						<ul>
							<li><a href="#">Daten</a></li>
							<li><a href="#">Passwort</a></li>
							<li><a href="#">Logout</a></li>
						</ul>
					</li>
					<li>
						<a href="#">Verwaltung</a>
					</li>
					<li>
						<a href="#">Hilfe</a>
						<ul>
							<li><a href="#">Inhalt</a></li>
							<li><a href="#">Über</a></li>
						</ul>
					</li>
				</ul>
*/ ?>
			</div>

			<div id="modulenavi"></div>
			<div id="modulesub"></div>
			<div id="modulehead"></div>
			<div id="moduletabs"></div>
		</div>

		<form id="content" action="" method="post">
			<div id="wrap"></div>
		</form>

	</body>
</html>
