<!DOCTYPE html>
  <head>
    <meta charset="UTF-8">
	<link href="style.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
    <title>Goatbook</title>
	<style type="text/css">
	#content{
		background: url('files/<?php echo rand(1,5); ?>.png') repeat;
	}
	</style>
	
  </head>
  <body onload="calculateHeightOfContent();">
	<div id="header">
	  <div id="logo"></div>
	</div>
	<div class="stripe" id="topstripe"></div>
	<div id="content">
	</div>
	<div class="stripe" id="botstripe"></div>
	<div id="footer">
	</div>
	<script type="text/javascript" language="javascript">
		function calculateHeightOfContent() {
			var distance = $('#botstripe').offset().top - $('#topstripe').offset().top - 9;
			console.log(distance);
			if(distance%72!=0) {
				distance = distance + (72 - distance%72);
				$('#content').height(distance);
			}
		}
	</script>
  </body>
</html>
