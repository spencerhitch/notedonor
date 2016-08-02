<?php

include "../notequeueAuth.php";

echo $username ;

$newtext = "";//"'" . file_get_contents("public_html/score_modified.txt") . "'";
$firstname = "'Nicky'";
$lastname = "'Jaramillo'";

if($_POST['first_name']) {
	$firstname = "'".$_POST['first_name']."'";
}
if($_POST['last_name']) {
	$lastname = "'".$_POST['last_name']."'";
}

$sql = "INSERT INTO notes (firstname, lastname, instrument, duration, verified) VALUES ($firstname, $lastname, '1', '8', 0)";

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
