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
  <body onload="fixHeightOfContent();">
	<div id="header">
	  <div id="logo"></div>
	</div>
	<div class="stripe" id="topstripe"></div>
	<div id="content">
		<div id="howto">
			<h1>Send an SMS</h1>
			<h2>+44 0000 00000</h2>
			<h3>with the name of your friend</h3>
		</div>
	</div>
	<div class="stripe" id="botstripe"></div>
	<div id="footer">
		<div id="footer-logo"></div>
	</div>
	<script type="text/javascript" language="javascript">
		function fixHeightOfContent() {
			var distance = $('#botstripe').offset().top - $('#topstripe').offset().top - 9;
			console.log(distance);
			if(distance%80!=0) {
				distance = distance + (80 - distance%80);
				$('#content').height(distance);
			}
		}
	</script>
  </body>
</html>
