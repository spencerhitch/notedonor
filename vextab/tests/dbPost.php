<?php

include "../notequeueAuth.php";

$name= "'Nicky Jaramillo'";
$email = "'test@gmail.com'";
$instrument = "'1'";
$duration = "'8'";

if($_POST['name']) {
	$name = "'".$_POST['name']."'";
}
if($_POST['email']) {
	$email = "'".$_POST['email']."'";
}
if($_POST['instrument']) {
	$instrument = "'".$_POST['instrument']."'";
}
if($_POST['duration']) {
	$duration = "'".$_POST['duration']."'";
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM notes 
  WHERE email=$email
  AND verified=0";
$result = mysqli_query($conn, $sql);

// Create query, if there is an unverified entry w/ matching email, update it.
// Else, INSERT new entry
if ($result->num_rows > 0) {
  $sql = "UPDATE notes SET name=$name, instrument=$instrument, duration=$duration) 
    WHERE email=$email 
    AND verified=0";

} else {
    $sql = "INSERT INTO notes (name, email, instrument, duration, verified) VALUES ($name, $email, $instrument, $duration, 0)";
}

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
exit();

?>
