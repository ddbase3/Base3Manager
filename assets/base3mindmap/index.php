<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>BASE3 MindMap Test</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<script src="base3mindmap/base3mindmap.js"></script>
		<link rel="stylesheet" href="base3mindmap/base3mindmap.css" />

		<script>
			$(function() {
				var data = {
					"name": "Instagram",
					"sub": [
						{
							"name": "Projects",
							"dir": "left",
							"sub": [{"name": "LED Cube"}, {"name": "Home cinema"}]
						},
						{
							"name": "Software",
							"dir": "left",
							"sub": [{"name": "CMS"}, {"name": "XMS"}, {"name": "Formation planner"}, {"name": "BlockCoder"}]
						},
						{
							"name": "Journeys",
							"dir": "left",
							"sub": [{"name": "Scotland"}, {"name": "New Zealand"}, {"name": "Maldives"}, {"name": "Mallorca"}, {"name": "Ibiza"}]
						},
						{
							"name": "Arts",
							"dir": "left",
							"sub": [{"name": "Aquarell"}, {"name": "Op Art"}]
						},
						{
							"name": "Sports",
							"dir": "right",
							"sub": [{"name": "Formation Dance"}, {"name": "Marathon"}]
						},
						{
							"name": "Fun",
							"dir": "right",
							"sub": [{"name": "Faires"}, {"name": "Cooking"}, {"name": "Joggling"}]
						},
						{
							"name": "Math",
							"dir": "right",
							"sub": [{"name": "Squaring numbers"}, {"name": "Magic squares/hexagons"}, {"name": "Conway's Game of Life"}]
						}
					]
				};
				$("#mindmap").mindmap({"data": data});
			});
		</script>

		<style>
			html, body { margin:0; }
		</style>

	</head>
	<body>
		<div id="mindmap"></div>
	</body>
</html>
