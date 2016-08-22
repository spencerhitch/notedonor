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

function queryByEmail($email) {

	$servername = "localhost";
	$username = "sinfonia";
	$password = "Belarus Kiev";
	$dbname = "note_queue";

  $sql = "SELECT * FROM notes 
    WHERE email='" . $email . "'
    AND verified=0";

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
          $result = array(
            "name" => $row["name"]
            "instrument" => $row["instrument"]
            "duration" => $row["duration"]
          );
          return $result 
		    }
		} else {
		    echo "0 results \n";
		}
	mysqli_close($con);
}

?>
