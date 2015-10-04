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
		$r = $row["result"];
		$color = "black";
		if( strcmp($r,"Conservative") == 0 ) $color = "red";
		if( strcmp($r,"Liberal") == 0 ) $color = "blue";
		if( strcmp($r,"Moderate") == 0 ) $color = "grey";
		$r = strtoupper($r);
		$rstr = "<span style=color:$color>$r</span>";
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
		
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST' ){
	
	$url = $_POST['url'];
	$title = $_POST['title'];
	$conservative = $_POST['conservative'];
	$liberal = $_POST['liberal'];
	$score = $_POST['score'];
	$result = $_POST['result'];
	
	$url = mysqli_real_escape_string($conn, $url);
	$title = mysqli_real_escape_string($conn, $title);
	
	//$sql = "SELECT * FROM articles WHERE url='$url'";
	//$result = mysqli_query($conn, $sql);
	//if (mysqli_num_rows($result) > 0) {
		$sql = "INSERT IGNORE INTO articles (title,url,liberal,conservative,score,result) VALUES ('$title','$url','$liberal','$conservative','$score','$result');";
		$result = mysqli_query($conn, $sql);
	//}
	print json_encode("http://lampstack5-635795001885558423.cloudapp.net/politilt/tilt.php?url=" . $url);
}
?>
