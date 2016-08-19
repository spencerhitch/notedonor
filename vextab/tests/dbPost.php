<?php

include "../notequeueAuth.php";

echo $username ;

$newtext = "";//"'" . file_get_contents("public_html/score_modified.txt") . "'";
$firstname = "'Nicky'";
$lastname = "'Jaramillo'";
$instrument = "'1'";
$duration = "'8'";

if($_POST['first_name']) {
	$firstname = "'".$_POST['first_name']."'";
}
if($_POST['last_name']) {
	$lastname = "'".$_POST['last_name']."'";
}
if($_POST['instrument_number']) {
	$instrument = "'".$_POST['instrument_number']."'";
}
if($_POST['note_duration']) {
	$duration = "'".$_POST['note_duration']."'";
}

$sql = "INSERT INTO notes (firstname, lastname, instrument, duration, verified) VALUES ($firstname, $lastname, $instrument, $duration, 0)";

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
