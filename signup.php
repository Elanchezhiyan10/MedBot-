<?php
$host = "localhost";
$user = "root";
$password = "root";
$dbname = "medbot";

$conn = new mysqli($host, $user, $password, $dbname);

if($conn->connect_error) {
    die("connection failed:" .$conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$check = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($check);

if($result->num_rows>0){
    echo "User Already Exist";
}
else{
    $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

    if($conn->query($sql) == TRUE){
        echo "SignUp Successfull";
    }else{
        echo "ERROR:".$conn->error;
    }

}
$conn->close();
?>
