<!doctype html>
<html>
  <head>
    <title>POLI TILT</title>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script>
function analyze(data) {
	var obj = JSON.parse(data);

	var con = obj.results.Conservative/* * .4*/;
	var lib = obj.results.Liberal/* * .4*/;
	var green = obj.results.Green/* * .1*/;
	var tar = obj.results.Libertarian/* * .1*/;
	
	window.result = "";
	
	window.finalRightist = con + tar;
	window.finalLeftist = lib + green;
	window.theMax = Math.max( finalLeftist, finalRightist );
	var theDifference = Math.abs( finalLeftist - finalRightist );
	if (theDifference <= 0.26){
		window.result = "Moderate";
		return "<span style='color: grey;'>Moderate</span>";
	}
	if (window.theMax == window.finalRightist){
		window.result = "Conservative";
		return "<span style='color: red;'>Conservative</span>";
	}
	else if (window.theMax == window.finalLeftist){
		window.result = "Liberal";
		return "<span style='color: blue;'>Liberal</span>";
	}
	else {
		return "Indeterminate";
	}
}
    
    function doIt() {
		var text = $("#tbbox").val();
		//alert(text);
		window.title = "";
		// If the user has an actual HTTP or HTTPS URL, submit it to the the APIs. This works in two
		// stages: first, we have to extract the article's text using AlchemyAPI, then we pass the
		// data to Indico, which will analyze the text and tell us the political slant.
		$.post( "http://gateway-a.watsonplatform.net/calls/url/URLGetTitle?apikey=cc5ce465c9859bb7f7fcfdfba29dbd257140dc94", { outputMode: "json", url: text } , function(data) {
			window.title = data.title;//alert(data.title);
		});
	
		
		
		$.post( "http://gateway-a.watsonplatform.net/calls/url/URLGetText?apikey=cc5ce465c9859bb7f7fcfdfba29dbd257140dc94", { outputMode: "json", url: text }, function( data ) {
			//alert( tab.title );
			
			$.post( "http://apiv2.indico.io/political?key=5a270b8e83870c2dab1e6fb14fc6adc5", { data: data.text }, function( data ) {
				//alert( "Data Loaded: " + data );
				
				analyze(data);
				/*$("#wait-cursor").hide();
				$("#content").append("<div align=\"center\"><p>Based on our analysis, the political slant of this article is</p></div>");
				$("#content").append("<div align=\"center\"><p>" + analyze(data) + "</p></div>");
				$("#content").append("<div id='chartdiv'></div> ");*/
				
				//var gaugeChart = createChart();
				//$("a[title='JavaScript charts']").hide();

				// Calculate how far to move up the gauge on the political spectrum graphic
				var value = 0;
				if (window.theMax == window.finalRightist) {
					value = window.theMax * 190;
					console.log(value + " " + window.theMax*190 + "  inside of if")
				} else if (window.theMax == window.finalLeftist) {
					if (window.theMax * 190 < 95) value = (window.theMax * 190);
					else value = 190 - (window.theMax * 190);
	            	console.log(value + " " + window.theMax*190 + "  inside of else")	
				}

				// Print debugging info
				//$("#content").append("<p>" + value + "</p>");
				//$("#content").append("<p>" + window.finalLeftist + " " + window.finalRightist + "</p>");
				
				// Submit the political data to the server to be stored.
				$.ajax({
					type: 'POST',
					url: 'http://lampstack5-635795001885558423.cloudapp.net/politilt/index.php',
					data: { title: title, url: text, liberal: window.finalLeftist, conservative: window.finalRightist, score: value, result: window.result},
					dataType: 'json'
				}).done( function( data ) {
					var iframe = document.getElementById('shittyiframe');
					iframe.src = iframe.src;
					
					var siframe = document.getElementById('secondiframe');
					var newlocation = "http://lampstack5-635795001885558423.cloudapp.net/politilt/index.php?url=" + text;
					siframe.src = newlocation;
					//alert(newlocation);
					//window.location.assign(newlocation);
					console.log('done');
					//$("#content").append("<p><a id='learn-more' href=\"" + data + "\", target=\"_blank\")'>Learn More</a></p>");
					console.log( data );
				}).fail( function( data ) {
					console.log('fail');
					console.log(data);
				});
			});
		});
	}
    </script>
  </head>
  <!-- background="img/intro-bg.jpg" background-repeat="no-repeat" backgroud-position="middle top"> -->
  <body style="width:100%; vertical-align: middle;">
	<h1 align="center">POLI TILT</h1>
	<h2  align="center">Article analysis for the politically inebriated.</h2>
	<div align="center"><input align="center" type="text" id="tbbox" placeholder="Enter URL..." />
	<button onclick="doIt();">Submit</button></div>
	<p id="content"></content>
	
	<?php 
	
	$servername = "localhost";
	$username = "root";
	$password = "bitnami";
	$dbname = "politilt";

	/*$servername = "216.66.104.3";
	$username = "politilt";
	$password = "N7@d0OrDwoQd";*/


	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if($_SERVER['REQUEST_METHOD'] == 'GET' ){
	$url = $_GET['url'];
	$url = mysqli_real_escape_string($conn, $url);
	
	/*echo "<div align=\"center\"><table>";
	echo "<td style=text-align:center><b>Title</b></td>";
	echo "<td style=text-align:center><b>Site</b></td>";
	echo "<td style=text-align:center><b>Tilt</b></td>";
	echo "<td style=text-align:center><b>Liberal</b></td>";
	echo "<td style=text-align:center><b>Conservative</b></td>";*/
	$sql = "SELECT * FROM articles WHERE url='$url'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = $result->fetch_assoc();
		echo "<div align=\"center\"><h3>" . $row["title"] . "</h3></div>";
		
		//echo "Based on our analysis the political slant of this article is " . $row["result"] );
		echo "<div align=\"center\"><p>Based on our analysis the political slant of this article is: </p>";
		$str = $row["result"];
		$color = "black";
		if( strcmp($str,"Conservative") == 0 ) $color = "red";
		if( strcmp($str,"Liberal") == 0 ) $color = "blue";
		if( strcmp($str,"Moderate") == 0 ) $color = "grey";
		$str = strtoupper($str);
		$rstr = "<span style=color:$color>$str</span>";
		echo $rstr;
		echo "</div>";
				
		/*echo "<tr>";
		echo "<td>" . substr($row["title"],0,50) . " ... </td>";
		echo "<td>URL here</td>";
		//echo "<td><a href=\"". $row["url"]) ."\">link</a></td>";
		echo "<td>".$row["result"]."</td>";
		echo "<td>".$row["liberal"]."</td>";
		echo "<td>".$row["conservative"]."</td>";*/
	} else {
		echo "<iframe align=\"center\" id=\"secondiframe\" src=\"\" width=\"90%\" height=\"150\" frameBorder=\"0\"></iframe>";
	}
}
?>
	
	<iframe align="center" id="shittyiframe" src="http://lampstack5-635795001885558423.cloudapp.net/politilt/mysql.php" width="90%" height="600" frameBorder="0"></iframe>
  </body>
</html>
