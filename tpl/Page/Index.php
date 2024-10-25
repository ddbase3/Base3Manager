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
				<a class="toggle" href='#'></a>
<?php if (count($this->_['systemnavi']) > 0) { ?>
				<ul>
<?php foreach ($this->_['systemnavi'] as $button1) { ?>
					<li>
<?php if (isset($button1["link"])) { ?>
						<a href="<?php echo $button1["link"]; ?>" target="_blank"><?php echo $button1["name"]; ?></a>
<?php } ?>
<?php if (isset($button1["sub"])) { ?>
						<a href="#"><?php echo $button1["name"]; ?></a>
						<ul>
<?php foreach ($button1["sub"] as $button2) { ?>
							<li><a href="<?php echo $button2["link"]; ?>" target="_blank"><?php echo $button2["name"]; ?></a></li>
<?php } ?>
						</ul>
<?php } ?>
					</li>
<?php } ?>
				</ul>
<?php } ?>
			</div>

			<div id="modulenavi"></div>
			<div id="subnavi"></div>
			<div id="toolbar"></div>
			<div id="modulehead"></div>
			<div id="moduletabs"></div>
		</div>

		<form id="content" action="" method="post">
		</form>

	</body>
</html>
