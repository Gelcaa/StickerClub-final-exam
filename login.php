<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $emailaddress = $_POST["emailaddress"];
  $password = $_POST["password"];

  
	if (empty($emailaddress)) {
		header("Location: index.php?error=Email Address is required");
	    exit();
	}else if(empty($password)){
        header("Location: index.php?error=Password is required");
	    exit();
  }
	
  // Establish database connection
  $conn = mysqli_connect("localhost", "root", "", "sc_db");
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Prepare SQL statement to prevent SQL injection
  $sql = "SELECT * FROM sctbl WHERE emailaddress = ? AND password = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "ss", $emailaddress, $password);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) == 1) {
    // Login successful
    echo "<img src='path_to_image.jpg' alt='Logged In Image'>";
  } else {
    // Invalid username or password
    header("Location: index.php?Invalid username or password. Please try again.");
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
?>