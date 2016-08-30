<!DOCTYPE html>
<html>
<body>

<h1> Donors </h1>
<ul>
  <?php 
    include "../notequeueAuth.php";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    $sql = "SELECT * FROM notes 
      WHERE verified=1";
    $result = mysqli_query($conn, $sql);
    
    // Create query, if there is an unverified entry w/ matching email, update it.
    // Else, INSERT new entry
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<li>" . $row['name'] . "</li>";
      }
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  ?>
<ul>

</body>
</html>
