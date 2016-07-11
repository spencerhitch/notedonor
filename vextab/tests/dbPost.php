<?php

$servername = "localhost";
$username = "sinfonia";
$password = "Ow8!A:VAEqmX";
$dbname = "note_queue";

$sql = "INSERT INTO " + $dbname + "(firstname, lastname, instrument, noteduration) VALUES ('Spencer', 'Hitchcock', '1', '8')";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
exit();

?>
