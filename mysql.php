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

$sql = "SELECT * FROM articles ORDER BY score";
$result = mysqli_query($conn, $sql);
echo "<html><body>";
echo "<table border=\"0\" align=\"center\" style=\"width:100%\">";
echo "<td style=text-align:center><b>Title</b></td>";
echo "<td style=text-align:center><b>Tilt</b></td>";
echo "<td style=text-align:center><b>Liberal</b></td>";
echo "<td style=text-align:center><b>Conservative</b></td>";
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		echo "<tr>";
		
        echo "<td style=text-align:center>" . substr($row["title"],0,60) . " ... </td>";
		//echo "<td><a href=\"". $row["url"]) ."\">link</a></td>";
		$r = $row["result"];
		$color = "black";
		if( strcmp($r,"Conservative") == 0 ) $color = "red";
		if( strcmp($r,"Liberal") == 0 ) $color = "blue";
		if( strcmp($r,"Moderate") == 0 ) $color = "grey";
		$rstr = "<span style=color:$color>$r</span>";
		echo "<td style=text-align:center>".$rstr."</td>";
		$lib = $row["liberal"]*100;
		echo "<td style=text-align:center>".intval($lib)."%</td>";
		$con = $row["conservative"]*100;
		echo "<td style=text-align:center>".intval($con)."%</td>";
		echo "</tr>";
    }
	echo "</table></div>";
} else {
    //echo "0 results";
}
$conn->close();
echo "</body></html>";
?>