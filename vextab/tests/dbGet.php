<?php


function queryUnverified($instrument) {
	$servername = "localhost";
	$username = "";
	$password = "";
	$dbname = "note_queue";
	$sql = "SELECT duration FROM notes WHERE verified=0 AND instrument='". $instrument . "';";


	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
           
		$result = mysqli_query($conn, $sql);
		if ($result->num_rows > 0) {
		  // output data of each row
		  $durations = array();
		  while($row = $result->fetch_assoc()) {
		    array_push($durations,$row['duration']);
		  }
                  return $durations;
		} else{
		  return array();
		}
	mysqli_close($con);

}

function queryByName($firstname, $lastname) {

	$servername = "localhost";
	$username = "sinfonia";
	$password = "Belarus Kiev";
	$dbname = "note_queue";

	$sql = "SELECT new_text FROM notes WHERE firstname='". $firstname . "' AND lastname='" . $lastname . "'";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
           
		$result = mysqli_query($conn, $sql);
		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
			echo "newtext: " . $row["new_text"]. "\n";
			return $row["new_text"];
		    }
		} else {
		    echo "0 results \n";
		}
	mysqli_close($con);
}

?>
