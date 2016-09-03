<?php 
  include "../notequeueAuth.php";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 
  
  $sql = "SELECT name FROM notes 
    WHERE verified=1";
  $result = mysqli_query($conn, $sql);
  
  // Create query, if there is an unverified entry w/ matching email, update it.
  // Else, INSERT new entry
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      echo "<tr><td>" . str_replace("_", " ", $row['name']) . "</td><td></td></tr>";
    }
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
?>
