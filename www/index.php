<!DOCTYPE html>
  <head>
    <meta charset="UTF-8">
	<link href="style.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
    <title>Goatbook</title>
	<style type="text/css">
	#content{
		background: url('files/<?php echo rand(1,5); ?>.png') repeat;
		background-color: #666;
	}
	</style>	
  </head>
  <body onload="fixContentSizes();">
	<div id="header">
	  <div id="logo"></div>
	</div>
	<div class="stripe" id="topstripe"></div>
	<div id="wrap-content">
		<div id="content">
			<div id="howto">
				<h1>Send an SMS</h1>
				<h2>+44 0000 00000</h2>
				<h3>with the name of your friend</h3>
			</div>
		</div>
	</div>
	<div class="stripe" id="botstripe"></div>
	<div id="footer">
		<div id="footer-logo"></div>
	</div>
	<script type="text/javascript" language="javascript">
		function fixContentSizes() {
			var contentHeight = $('#botstripe').offset().top - $('#topstripe').offset().top - 9;
			//console.log(contentHeight);
			if(contentHeight % 80 != 0) {
				contentHeight = contentHeight + (80 - contentHeight%80);
				$('#content').height(contentHeight);
			}
			var contentWidth = $('#content').width();
			console.log(contentWidth);
			if((contentWidth/2) % 80 != 0) {
				contentWidth = contentWidth - ((80 - ((contentWidth/2)%80))/2);
				$('#content').width(contentWidth);
			}
		}
		function addGoat(imageJson) {
			var imagePath = "http://goat.vladh.net/backend/humanfaces/" + imageJson["file"];
			var gridSize = Math.floor((Math.random()*3)+1);
			var goatWidth = gridSize * 80;
			var goatHeight = gridSize * 80;
			$('<div class="goat size' + gridSize + '" style="background: url(\'' + imagePath + '\') no-repeat"></div>').appendTo("#content");
		}
		function getGoats() {
           $.ajax({
                type: "GET",
                url: "getgoats.php",
                data: lastgoat=53,
                dataType:'text',
                success: function(response){
					var jsonResults = JSON.parse(response);
					for(int i = 0; i < jsonResults.length; i++) {
						addGoat(jsonResults[i]);
					}
                }
            });			
		}
		
	</script>
  </body>
</html>
