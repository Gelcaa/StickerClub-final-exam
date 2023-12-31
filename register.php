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

 if (empty($fname)) {
		header("Location: Registerform.php?error=First name is required");
	    exit();
   
    }else if (empty($lname)) {
		header("Location: Registerform.php?error=Last Name is required");
	    exit();

     }else if (empty($emailaddress)) {
		header("Location: Registerform.php?error=Email Address is required");
	    exit();

    }else if (empty($password)) {
		header("Location: Registerform.php?error=Password is required");
	    exit();


    }else if (empty($region)) {
		header("Location: Registerform.php?error=Region is required");
	    exit();

	}else if(empty($province)){
        header("Location: Registerform.php?error=Province is required");
	    exit();
  }
  else if(empty($city)){
        header("Location: Registerform.php?error=City is required");
	    exit();
  }
  else if(empty($barangay)){
        header("Location: Registerform.php?error=Barangay is required");
	    exit();
  }
   else if(empty($phonenumber)){
        header("Location: Registerform.php?error=Phone Number is required");
	    exit();
  }
   else if(empty($otherinfo)){
        header("Location: Registerform.php?error=Other Info is required");
	    exit();
  }
  

	
  // Establish database connection
  $conn = mysqli_connect("localhost", "root", "", "sc_db");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Prepare SQL statement to prevent SQL injection
  $sql = "INSERT INTO sctbl(fname, lname, emailaddress, password, region, province, city, barangay, phonenumber, otherinfo) VALUES (?,?,?,?,?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  mysqli_stmt_bind_param($stmt, "ssssssssss",$fname, $lname, $emailaddress, $password, $region, $province, $city, $barangay, $phonenumber, $otherinfo);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);
  
 header("Location: home_products.php");
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
?>
