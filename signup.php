<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fname = $_POST["fname"];
  $lname = $_POST["lname"];
  $emailaddress = $_POST["emailaddress"];
  $password = $_POST["password"];
  $region = $_POST["region"];
  $province = $_POST["province"];
  $city = $_POST["city"];
  $barangay = $_POST["barangay"];
  $phonenumber = $_POST["phonenumber"];
  $otherinfo = $_POST["otherinfo"];


  // Establish database connection
  $conn = mysqli_connect("localhost", "root", "", "sc_db");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Prepare SQL statement to prevent SQL injection
  $sql = "INSERT INTO sctbl(fname, lname, emailaddress,password,region, province, city, barangay, phonenumber, otherinfo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  mysqli_stmt_bind_param($stmt, "ssssssssss", $fname, $lname, $emailaddress, $password,  $region, $province, $city, $barangay, $phonenumber, $otherinfo);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);

 echo "<img src='path_to_image.jpg' alt='Logged In Image'>";

  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
?>