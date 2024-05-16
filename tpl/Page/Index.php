<!DOCTYPE html>
<html>

	<head>

		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>BASE3 Manager</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

		<script src="plugin/Base3Manager/assets/touchpunch/jquery.ui.touch-punch.min.js"></script>

		<script type="text/javascript" src="plugin/Base3Manager/assets/arrangeable/jquery.arrangeable.js"></script>

		<link rel="stylesheet" type="text/css" href="plugin/Base3Manager/assets/jqueryuimultiselect/jquery.multiselect.css" />
		<link rel="stylesheet" type="text/css" href="plugin/Base3Manager/assets/jqueryuimultiselect/jquery.multiselect.filter.css" />
		<script type="text/javascript" src="plugin/Base3Manager/assets/jqueryuimultiselect/jquery.multiselect.min.js"></script>
		<script type="text/javascript" src="plugin/Base3Manager/assets/jqueryuimultiselect/jquery.multiselect.filter.js"></script>

		<link rel="stylesheet" href="plugin/Base3Manager/assets/contextmenu/src/jquery.contextMenu.css" type="text/css" />
		<script type="text/javascript" src="plugin/Base3Manager/assets/contextmenu/src/jquery.contextMenu.js"></script>

		<link rel="stylesheet" type="text/css" href="plugin/Base3Manager/assets/flexigrid/css/flexigrid.pack.css" />
		<script type="text/javascript" src="plugin/Base3Manager/assets/flexigrid/js/flexigrid.js"></script>

		<script type="text/javascript" src="plugin/Base3Manager/assets/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="plugin/Base3Manager/assets/ckeditor/adapters/jquery.js"></script>

		<script type="text/javascript" src="plugin/Base3Manager/assets/jqueryrating/jquery.rating.js"></script>
		<link rel="stylesheet" href="plugin/Base3Manager/assets/jqueryrating/jquery.rating.css" type="text/css" />

		<link rel="stylesheet" type="text/css" href="plugin/Base3Manager/assets/tagit/css/jquery.tagit.css" />
		<script type="text/javascript" src="plugin/Base3Manager/assets/tagit/js/tag-it.min.js"></script>

		<script type="text/javascript" src="plugin/Base3Manager/assets/jquerythrottledebounce/jquery.ba-throttle-debounce.min.js"></script>

		<script type="text/javascript" src="plugin/Base3Manager/assets/jquerymasonry/masonry.pkgd.min.js"></script>

		<link rel="stylesheet" type="text/css" href="plugin/Base3Manager/assets/searchfilter/jquery.searchfilter.css" />
		<script type="text/javascript" src="plugin/Base3Manager/assets/searchfilter/jquery.searchfilter.js"></script>

		<link type="text/css" rel="stylesheet" href="plugin/Base3Manager/assets/layout/base.css" />
		<link type="text/css" rel="stylesheet" href="plugin/Base3Manager/assets/layout/<?php echo $this->_['layout']; ?>/style.css" />
		<script type="text/javascript" src="plugin/Base3Manager/assets/js/script.js"></script>

		<script type="text/javascript" src="plugin/Base3Manager/assets/flexigridbase3manager/script.js"></script>

<?php /*
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
*/ ?>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.js"></script>
		<script type="text/javascript" src="plugin/Base3Manager/assets/coolmap/jquery.coolmap.js"></script>

		<script src="plugin/Base3Manager/assets/base3mindmap/base3mindmap.js"></script>
		<link rel="stylesheet" href="plugin/Base3Manager/assets/base3mindmap/base3mindmap.css" />

<?php /*
		<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
*/ ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">

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
