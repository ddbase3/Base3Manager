<!DOCTYPE html>
<html lang="<?php echo $this->_['language']; ?>">
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
	<body id="base3manager">

		<div id="head">

			<div class="systemnavi">
				<a class="toggle" href='#'></a>
				<ul>
<?php
	foreach ($this->_['systemnavi'] as $navi) {
		$this->setPath(DIR_PLUGIN . $navi['plugin']);
		foreach ($navi['data'] as $button1) {
	                $name = $button1['name'];
	                if (isset($button1['translate']) && $button1['translate'] == 1) {
	                        $this->loadBricks('Bricks');
	                        $name = $this->_['bricks']['bricks'][$button1['name']];
	                }
?>
					<li>
<?php if (isset($button1["link"])) { ?>
						<a href="<?php echo $button1["link"]; ?>" target="_blank"><?php echo $name; ?></a>
<?php } ?>
<?php if (isset($button1["sub"])) { ?>
						<a href="#"><?php echo $name; ?></a>
						<ul>
<?php
	foreach ($button1["sub"] as $button2) {
		$name = $button2['name'];
		if (isset($button2['translate']) && $button2['translate'] == 1) {
			$this->loadBricks('Bricks');
			$name = $this->_['bricks']['bricks'][$button2['name']];
		}
?>
							<li><a href="<?php echo $button2["link"]; ?>" target="_blank"><?php echo $name; ?></a></li>
<?php
	}
?>
						</ul>
<?php } ?>
					</li>
<?php
		}
	}
?>
				</ul>
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
