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
	<script type="text/javascript" language="javascript">
		function fixContentSizes() {
			var contentHeight = $('#botstripe').offset().top - $('#topstripe').offset().top - 9;
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
		function addGoat(imageJson, divNumber) {
			var imagePath = "http://www.goatbook.co.uk/goatfaces/" + imageJson["file"];
			var gridSize = Math.floor((Math.random()*3)+1);
			var goatWidth = gridSize * 80;
			var goatHeight = gridSize * 80;
			var currentDivId = "#goat" + divNumber;
			if($(currentDivId).css("background-image") == "url('" + imagePath + "')") {
				return false;
			} else {
				$(currentDivId).addClass("size"+gridSize);
				$(currentDivId).css("background-image", "url('"+imagePath+"')");
				$(currentDivId).css("background-size", "100% 100%");
			//$('<div class="goat size' + gridSize + '" id="goat' + divNumber + '"><img src="' + imagePath + '" /></div>').appendTo("#content");
			return true;
			}
		}
		function getGoats() {
           $.ajax({
                type: "GET",
                url: "getgoats.php",
                data: {
					lastgoat: 4
				},
                dataType:'text',
                success: function(response){
					var jsonResults = JSON.parse(response);
					for(var i=0; i < jsonResults.length; i++) {
						addGoat(jsonResults[i], i+1);
					}
                }
            });			
		}
function checkGoats() {
	window.setInterval(function(){getGoats();}, 10000);
}
	</script>
  </head>
  <body onload="fixContentSizes(); checkGoats(); ">
	<div id="header">
	  <div id="logo"></div>
	</div>
	<div class="stripe" id="topstripe"></div>
	<div id="wrap-content">
		<div id="content">
			<div id="howto">
				<h1>Send an SMS</h1>
				<h2>+44 7860 033156</h2>
				<h3>with the name of your friend</h3>
			</div>
			<div class="goat" id="goat1"></div>
			<div class="goat" id="goat2"></div>
			<div class="goat" id="goat3"></div>
			<div class="goat" id="goat4"></div>
		</div>
	</div>
	<div class="stripe" id="botstripe"></div>
	<div id="footer">
		<div id="footer-logo"></div>
	</div>
  </body>
</html>
